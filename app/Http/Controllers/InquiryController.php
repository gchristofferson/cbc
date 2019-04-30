<?php

namespace App\Http\Controllers;

use App\Inquiry;
use App\Mail\InquireReceived;
use App\Mail\NewInquiry;
use App\Received;
use App\Notification;
use App\State;
use App\City;
use App\Document;
use App\Sent;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;
use Validator;

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


        abort_if($user->rejected == 'on', 403);


        // get received inquiry rows
        $received_inquiry_rows = \App\Received::orderBy('created_at', 'desc')->where('user_id', $user->id)->get();


        // get the inquiry id's from each row
        $received_inquiry_ids = [];
        foreach ($received_inquiry_rows as $row) {
            array_push($received_inquiry_ids, ['inquiry_id' => $row->inquiry_id, 'read' => $row->read]);
        }
//        return $received_inquiry_ids;


        $data['received_inquiries'] = Received::userInquiries(auth()->user()); //  = Received::where('user_id', auth()->user()->id)->get(); // = $received_inquiries;

        $inquiries = Inquiry::orderBy('created_at', 'DESC')->paginate(10);
        $data['inquiries'] = $inquiries;

        // check admin role
        if (auth()->user()->admin == 'on' || auth()->user()->super_admin == 'on') {
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


        abort_if($user->rejected == 'on', 403);


        // get received inquiry rows
        $received_inquiry_rows = Received::orderBy('created_at', 'desc')->where('user_id', $user->id)->get();


        // get the inquiry id's from each row
        $received_inquiry_ids = [];
        foreach ($received_inquiry_rows as $row) {
            array_push($received_inquiry_ids, ['inquiry_id' => $row->inquiry_id, 'read' => $row->read]);
        }

        // for each id, get the corresponding inquiry
        $received_inquiries = [];
        foreach ($received_inquiry_ids as $received_inquiry_id) {
            $inquiry = Inquiry::take(1)->where('id', $received_inquiry_id['inquiry_id'])->get();

            $inquiry['read'] = $received_inquiry_id['read'];
            array_push($received_inquiries, $inquiry);

        }


        $data['received_inquiries'] = Received::userInquiries(auth()->user()); //  = Received::where('user_id', auth()->user()->id)->get(); // = $received_inquiries;

        return view('inquiries.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $request_fields = $request->all();
        $current_user = auth()->user();
        $validator = Validator::make($request_fields,
            [
                'subject' => 'min:1',
                'body' => 'min:1',
                'cities_array' => 'required',
                'cities_array.*' => 'min:1',
                'attachment.*' => 'mimetypes:image/jpeg,image/png,application/pdf'
            ],
            [
                'cities_array.*.min' => 'You must select at least one city',
                'attachment.*.mimes' => 'The file type you tried uploading is not allowed. Please try again.'
            ]
        );

        //? Check validation
        if ($validator->fails()) {
            echo "<pre>";
            echo json_encode($validator->getMessageBag()->toArray(), JSON_PRETTY_PRINT);
            echo "</pre>";
            die("remove this");
            return redirect()->back()->withErrors($validator->getMessageBag()->toArray());
        }
        $request_fields = (object)$request_fields;
        $dto = (object)[
            'subject' => $request_fields->subject,
            'body' => $request_fields->body,
            'city_ids' => explode(',', $request_fields->cities_array[0]),
            'attachments' => isset($request_fields->attachment) ? $request_fields->attachment : []
        ];

        //? Check that cities exist on the DB
        $city_array = City::find($dto->city_ids);
        if (sizeof($city_array) != sizeof($dto->city_ids)) {
            return redirect()->back()->withErrors(["Invalid request"]);
        }
        //? Data is valid after here

        try {
            //? Create inquiry
            $inquiry = new Inquiry();
            $inquiry->subject = $dto->subject;
            $inquiry->body = $dto->subject;
            $inquiry->body = $dto->subject;
            $inquiry->user_id = auth()->id();
            $inquiry->save();

            //? Process Files
            foreach ($dto->attachments as $file) {
                // Save file
                $folder = $current_user->id;
                $filename = $file->hashName();
                $file->storeAs('/public/attachments/' . $folder, $filename);
                $path = url('storage/attachments/' . $folder . '/' . $filename);

                // insert into documents table
                $attachment = new Document();
                $attachment->inquiry_id = $inquiry->id;
                $attachment->document_link = $path;
                $attachment->save();
            }

            $notified = [];
            foreach ($city_array as $cityDo) {
                //? Push to inquiry to city
                $city_inquiry = new Sent();
                $city_inquiry->user_id = $current_user->id;
                $city_inquiry->city_id = $cityDo->id;
                $city_inquiry->inquiry_id = $inquiry->id;
                $city_inquiry->save();

                $users_to_notify = Notification::where([
                    'city_id' => $cityDo->id,
                    'notify' => true
                ])->get();


                foreach ($users_to_notify as $user_city_subscription) {
                    //? Push to notifications
                    $key = $user_city_subscription->user_id . '' . $inquiry->id;
                    if (isset($notified[$key])) {
                        continue;
                    }
                    $city_inquiry = new Received();
                    $city_inquiry->user_id = $user_city_subscription->user_id;
                    $city_inquiry->inquiry_id = $inquiry->id;
                    $city_inquiry->read = false;
                    $city_inquiry->save();
                    $notified[$key] = true;
                    if (env("SENDEMAIL")) {
                        Mail::to($users_to_notify->email)
                            ->queue(new InquireReceived(\App\Inquiry::find($inquiry->id)));
                    }
                }
            }
        } catch (\Exception $e) {
            throw $e;
            //return redirect()->back()->withErrors(["Please try again"]);
        }

        //? Return
        session()->flash('success', 'Inquiry Created and Sent!');
        return redirect('/dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Inquiry $inquiry
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
        foreach ($data['cities'] as $key => $row) {
            $state_id[$key] = $row['state_id'];
        }
        array_multisort($state_id, SORT_DESC, $data['cities']);

        $data['view'] = '';

        // get the user
        $user = auth()->user();


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


        $received = Received::where([
            'user_id' => auth()->user()->id,
            'inquiry_id' => $inquiry->id
        ])->update(['read' => true]);

        $data['received_inquiries'] = Received::userInquiries(auth()->user()); //  = Received::where('user_id', auth()->user()->id)->get(); // = Received::where('user_id', auth()->user()->id)->get();


        // get documents

        $attachments = Document::where('inquiry_id', $inquiry->id)->get();
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
     * @param  \App\Inquiry $inquiry
     * @return \Illuminate\Http\Response
     */
    public function edit(Inquiry $inquiry)
    {
        //
        $data = [];
        $data['inquiry'] = $inquiry;

        $user = auth()->user();
        abort_if(auth()->user()->admin != 'on' || auth()->user()->super_admin != 'on' || $inquiry->user_id != auth()->id(), 403);

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
            foreach ($data['cities'] as $key => $row) {
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
            foreach ($received_inquiry_ids as $received_inquiry_id) {
                $inquiry = \App\Inquiry::where('id', $received_inquiry_id['inquiry_id'])->get();

                $inquiry['read'] = $received_inquiry_id['read'];
                array_push($received_inquiries, $inquiry);

            }
//        return $received_inquiries;

            $data['received_inquiries'] = Received::userInquiries(auth()->user()); //  = Received::where('user_id', auth()->user()->id)->get(); // = $received_inquiries;


            return view('inquiries.edit', $data);
        } else {
            return back();
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Inquiry $inquiry
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
     * @param  \App\Inquiry $inquiry
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inquiry $inquiry)
    {
        //
        // delete attachments
//        $documents = Document::all()->where('inquiry_id' , $inquiry->id)->delete();
//        $inquiry->documents()->delete();
//
//        // delete all saved and received inquiries
//        $inquiry->received_inquiries()->delete();
//        $inquiry->saved_inquiries()->delete();
//        $inquiry->sent_inquiry()->delete();
//
//        // delete any documents
//        $inquiry->documents()->delete();

        // delete inquiry
        $inquiry->deleter();

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
