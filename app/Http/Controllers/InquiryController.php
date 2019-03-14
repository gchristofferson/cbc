<?php

namespace App\Http\Controllers;

use App\Inquiry;
use App\Mail\NewInquiry;
use App\Received;
use App\State;
use App\City;
use App\Document;
use App\Sent;
use App\User;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = [];

        $data['view'] = '';

        // get user
        $user = auth()->user();

        // check admin role
        if ($user->admim == 'on' || $user->super_admin == 'on') {
            $data['view'] = 'admin_options';
        }
        $data['user'] = $user;

        if ($user->approved == 'off' ) {
            session()->flash('error', 'Your account is still pending approval');
            return redirect('/update-profile');
        }

        abort_if($user->rejected == 'on', 403);


        // get received inquiry rows
        $received_inquiry_rows = \App\Received::orderBy('created_at', 'desc')->where('user_id', $user->id)->get();


        // get the inquiry id's from each row
        $received_inquiry_ids = [];
        foreach ($received_inquiry_rows as $row) {
            array_push($received_inquiry_ids, ['inquiry_id' => $row->inquiry_id, 'read' => $row->read]);
        }
//        return $received_inquiry_ids;

        // for each id, get the corresponding inquiry
        $received_inquiries = [];
        foreach($received_inquiry_ids as $received_inquiry_id) {
            $inquiry = \App\Inquiry::take(1)->where('id', $received_inquiry_id['inquiry_id'])->get();

            $inquiry['read'] = $received_inquiry_id['read'];
            foreach ($received_inquiry_rows as $row) {
                if ($row->inquiry_id == $received_inquiry_id['inquiry_id']) {
                    $inquiry['received_id'] = $row->id;
                }

            }
            array_push($received_inquiries, $inquiry);
        }

        $data['received_inquiries'] = $received_inquiries;

        $inquiries = Inquiry::orderBy('created_at', 'DESC')->paginate(10);
        $data['inquiries'] = $inquiries;

        // check admin role
        if (auth()->user()->admin == 'on'  || auth()->user()->super_admin == 'on') {
            return view('inquiries.index-admin', $data);
        } else {
            return view('inquiries.index', $data);
        }


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];

        // get cities to send to from user subscriptions
        $subscriptions = auth()->user()->subscriptions->sortBy('state_id');
        if (sizeof($subscriptions) == 0) {
            session()->flash('error', 'You are not subscribed to any markets yet.');
            return back();
        }
        foreach ($subscriptions as $subscription) {
            $data['states'][] = $subscription->state;
            foreach ($subscription->state->cities as $city) {
                $data['cities'][] = $city;
            }
        }

        $data['msg'] = '';

        $data['view'] = '';

        // get user
        $user = auth()->user();

        // check admin role
        if ($user->admim == 'on' || $user->super_admin == 'on') {
            $data['view'] = 'admin_options';
        }
        $data['user'] = $user;

        if ($user->approved == 'off' ) {
            session()->flash('error', 'Your account is still pending approval');
            return redirect('/update-profile');
        }

        abort_if($user->rejected == 'on', 403);


        // get received inquiry rows
        $received_inquiry_rows = Received::orderBy('created_at', 'desc')->where('user_id', $user->id)->get();


        // get the inquiry id's from each row
        $received_inquiry_ids = [];
        foreach ($received_inquiry_rows as $row) {
            array_push($received_inquiry_ids, ['inquiry_id' => $row->inquiry_id, 'read' => $row->read]);
        }
//        return $received_inquiry_ids;

        // for each id, get the corresponding inquiry
        $received_inquiries = [];
        foreach($received_inquiry_ids as $received_inquiry_id) {
            $inquiry = Inquiry::take(1)->where('id', $received_inquiry_id['inquiry_id'])->get();

            $inquiry['read'] = $received_inquiry_id['read'];
            array_push($received_inquiries, $inquiry);

        }
//        return $received_inquiries;

        $data['received_inquiries'] = $received_inquiries;

        return view('inquiries.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        //
//        return dd(request());
        $data = [];
        $success_msg = '';
        request()->validate([
            'subject' => 'required',
        ]);
        if (request()->cities_array[0] == null) {
            $msg = "You must send to at least one city";
            $data['msg'] = $msg;
            $data['subject'] = request()->subject;
            $data['body'] = request()->body;

            return redirect()->back()->with('data', $data);
        } else {

            // create the inquiry
            $inquiry = new Inquiry();

            $inquiry->subject = request()->subject;

            if (request()->body == null) {
                $inquiry->body = '';
            } else {
                $inquiry->body = request()->body;
            }

            $inquiry->sent = true;

            $inquiry->user_id = auth()->id();

            $inquiry->save();

            // retrieve the inquiry id
            $inquiry_id = $inquiry->id;

            // for each city, create a sent record
            $cities_str = request()->cities_array[0];
            $cities = explode(",", $cities_str);

            $notification_emails = [];
            $notification_user_ids = [];
            foreach ($cities as $city) {

                // get the city object
                $objects = City::all()->where('id', $city);

                $city_obj = '';

                foreach ($objects as $object) {
                    $city_obj = $object;
                }

                // for each city, push user ids from notifications to array
                $city_notifications = $city_obj->notifications;
//                return $city_notifications;
                if ($city_notifications != []) {
                    foreach ($city_notifications as $city_notification) {
                        if ($city_notification->notify == true) {
                            // add to array if not there already
                            if (! in_array($city_notification->user->email, $notification_emails)) {
                                array_push($notification_emails, $city_notification->user->email);
                            }
                            if (! in_array($city_notification->user->id, $notification_user_ids)) {
                                array_push($notification_user_ids, $city_notification->user->id);
                            }
                        }
                    }
                }


                // add inquiry to sent table for user
                $sent = new Sent();

                $sent->user_id = auth()->id();
                $sent->inquiry_id = $inquiry_id;
                $sent->city_id = $city;

                $sent->save();

            }


            // add this inquiry to the received table for each user id
            foreach ($notification_user_ids as $notification_user_id) {
                $received = new Received();

                $received->user_id = $notification_user_id;
                $received->inquiry_id = $inquiry_id;
                $received->save();
            }





            // for each attachment, create a document record
            $files = request()->attachment;

            // validate the uploads
            $input_data = $request->all();

            $validator = \Validator::make(
                $input_data, [
                'attachment.*' => 'max:10000|mimes:jpeg,png,bmp,gif,svg,doc,dot,docx,xls,xlsx,csv,ppt,pptx,txt,pdf,aac,epub,mp3,mpeg,ods,wav,mp4'
            ],[
                    'attachment.*.mimes' => 'The file type you tried uploading is not allowed. Please try again.',
                    'attachment.*.max' => 'Sorry! Maximum allowed size for a file upload is 10MB',
                ]
            );

            if ($validator->fails())
            {
                return response()->json(array(
                    'success' => false,
                    'errors' => $validator->getMessageBag()->toArray()

                ), 400);             }


            $attachment_links = [];
            if (request()->hasFile('attachment')) {

                foreach ($files as $file) {
                    // validate the uploads

                    if (auth()->user()->admin == 'on' || auth()->user()->super_admin == true && $user->id != auth()->id()) {
                        $directory = $user->id;
                    } else {
                        $directory = auth()->id();
                    }
                    $filename = $file->hashName();
                    $file->storeAs('/public/attachments/' . $directory, $filename);
                    $path = url('storage/attachments/' . $directory . '/' . $filename);
                    array_push($attachment_links, $path);

                    // insert into documents table
                    $attachment = new Document();

                    $attachment->inquiry_id = $inquiry->id;
                    $attachment->document_link = $path;

                    $attachment->save();

                }
            }

            //construct and send inquiry as email to all users with notification
            // TODO: add this to queue
            $user = auth()->user();
            foreach ($notification_emails as $email) {
                \Mail::to($email)->send(
                  new NewInquiry($inquiry, $user, $attachment_links)
                );
            }



            // return the view
            session()->flash('success', 'Inquiry Created and Sent!');

            return redirect('/dashboard');
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Inquiry  $inquiry
     * @return \Illuminate\Http\Response
     */
    public function show(Inquiry $inquiry)
    {
        $data = [];
        $data['inquiry'] = $inquiry;

        // get inquiry cities
        $cities = Sent::all()->where('inquiry_id', "$inquiry->id");
        foreach ($cities as $city) {
            $city = City::all()->where('id', "$city->city_id");
            foreach ($city as $item) {
                $data['cities'][] = $item;
            }
        }

        $state_id = array();
        foreach ($data['cities'] as $key=>$row) {
            $state_id[$key] = $row['state_id'];
        }
        array_multisort($state_id, SORT_DESC, $data['cities']);


//        return $inquiry;

        $data['view'] = '';

        // get the user
        $user = auth()->user();

        if ($user->approved == 'off' ) {
            session()->flash('error', 'Your account is still pending approval');
            return redirect('/update-profile');
        }

        abort_if($user->rejected == 'on', 403);


        // get the saved inquiries
        // if inquiry is in saved inquiries, hide the save button
        $saved_inquiry_rows = \App\Saved::all()->where('user_id', $user->id);

        // get the inquiry id's from each row
        $saved_inquiry_ids = [];
        foreach ($saved_inquiry_rows as $row) {
            array_push($saved_inquiry_ids, $row->inquiry_id);
        }

        if (in_array($inquiry->id, $saved_inquiry_ids)) {
            $data['saved'] = true;
        } else {
            $data['saved'] = false;
        }

        // mark inquiry as read
        // get the corresponding received inquiry
        $user_received = Received::where('user_id', $user->id)->get();

//        return $data;

        // if the user received inquiry id equals this inquiry id, mark it as read
        foreach ($user_received as $item) {
            if ($item->inquiry_id == $inquiry->id) {
                Received::where('inquiry_id', $inquiry->id)->update(array('read' => true));
            }
        }


        // get received inquiries for user
        // check admin role
        if ($user->admim == 'on' || $user->super_admin == 'on') {
            $data['view'] = 'admin_options';
        }
        $data['user'] = $user;


        // get received inquiry rows
        $received_inquiry_rows = \App\Received::orderBy('created_at', 'desc')->where('user_id', $user->id)->get();



        // get the inquiry id's from each row
        $received_inquiry_ids = [];
        foreach ($received_inquiry_rows as $row) {
            array_push($received_inquiry_ids, ['inquiry_id' => $row->inquiry_id, 'read' => $row->read]);
        }
//        return $received_inquiry_ids;

        // for each id, get the corresponding inquiry
        $received_inquiries = [];
        foreach($received_inquiry_ids as $received_inquiry_id) {
            $inquiry = \App\Inquiry::where('id', $received_inquiry_id['inquiry_id'])->get();

            $inquiry['read'] = $received_inquiry_id['read'];
            array_push($received_inquiries, $inquiry);

        }
//        return $received_inquiries;

        $data['received_inquiries'] = $received_inquiries;


        // get documents
        $attachments = $inquiry->documents()->get();
        $data['attachments'] = $attachments;

        foreach ($attachments as $attachment) {
            $data['paths'][] = $attachment->document_link;
        }
//        dd($data);
        return view('inquiries.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Inquiry  $inquiry
     * @return \Illuminate\Http\Response
     */
    public function edit(Inquiry $inquiry)
    {
        //
        $data = [];
        $data['inquiry'] = $inquiry;

        $user = auth()->user();
        abort_if(auth()->user()->admin != 'on' || auth()->user()->super_admin != 'on' || $inquiry->user_id != auth()->id(), 403 );

        if ($inquiry->user_id == auth()->id() || $user->admin == 'on' || $user->super_admin == 'on') {
            // get inquiry cities
            $cities = Sent::all()->where('inquiry_id', "$inquiry->id");
            foreach ($cities as $city) {
                $city = City::all()->where('id', "$city->city_id");
                foreach ($city as $item) {
                    $data['cities'][] = $item;
                }
            }

            $state_id = array();
            foreach ($data['cities'] as $key=>$row) {
                $state_id[$key] = $row['state_id'];
            }
            array_multisort($state_id, SORT_DESC, $data['cities']);


            // get inquiry documents
            $documents = $inquiry->documents()->get();
            $data['documents'] = $documents;

            $data['view'] = '';

            // get the user
            $user = auth()->user();

            // get received inquiries for user
            // check admin role
            if ($user->admim == 'on' || $user->super_admin == 'on') {
                $data['view'] = 'admin_options';
            }
            $data['user'] = $user;

            // get received inquiry rows
            $received_inquiry_rows = \App\Received::orderBy('created_at', 'desc')->where('user_id', $user->id)->get();



            // get the inquiry id's from each row
            $received_inquiry_ids = [];
            foreach ($received_inquiry_rows as $row) {
                array_push($received_inquiry_ids, ['inquiry_id' => $row->inquiry_id, 'read' => $row->read]);
            }
//        return $received_inquiry_ids;

            // for each id, get the corresponding inquiry
            $received_inquiries = [];
            foreach($received_inquiry_ids as $received_inquiry_id) {
                $inquiry = \App\Inquiry::where('id', $received_inquiry_id['inquiry_id'])->get();

                $inquiry['read'] = $received_inquiry_id['read'];
                array_push($received_inquiries, $inquiry);

            }
//        return $received_inquiries;

            $data['received_inquiries'] = $received_inquiries;


            return view('inquiries.edit', $data);
        } else {
            return back();
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Inquiry  $inquiry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inquiry $inquiry)
    {
        //
        $inquiry->update(request([
            'subject',
            'body',
        ]));

        session()->flash('success', 'Inquiry Updated!');

        return redirect("/inquiries/$inquiry->id");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Inquiry  $inquiry
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inquiry $inquiry)
    {
        //
        // delete attachments
//        $documents = Document::all()->where('inquiry_id' , $inquiry->id)->delete();
        $inquiry->documents()->delete();

        // delete all saved and received inquiries
        $inquiry->received_inquiries()->delete();
        $inquiry->saved_inquiries()->delete();
        $inquiry->sent_inquiry()->delete();

        // delete any documents
        $inquiry->documents()->delete();

        // delete inquiry
        $inquiry->delete();

        session()->flash('success', 'Inquiry Deleted');

        // if admin, redirect back to inquiries list
        if (auth()->user()->admin == 'on' || auth()->user()->super_admin == 'on') {
            return redirect('/inquiries');
        } else {
            return redirect('/dashboard');
        }
    }

    public function attachment_store(Request $request, Inquiry $inquiry, Document $document)
    {
        $data = [];
        $files = request()->attachment;

        if (request()->hasFile('attachment')) {
            foreach ($files as $file) {
                $filename = $file->hashName();
                $file->storeAs('/public/attachments/' . auth()->id(), $filename);
                $path = url('storage/attachments/' . auth()->id() . '/' . $filename);
                $data['filenames'][] = $filename;
                $data['paths'][] = $path;

                // insert into documents table

                // Validate the request...

                $attachment = new Document();

                $attachment->inquiry_id = $inquiry->id;
                $attachment->document_link = $path;

                $attachment->save();

                $data['attachment'] = $attachment;



            }
        }

        return $data;

    //    return redirect()->back()->with('data', $data);

    }
}
