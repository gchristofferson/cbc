<?php

namespace App\Http\Controllers;

use App\Sent;
use Illuminate\Http\Request;
use App\Received;

class SentController extends Controller
{


    public function create()
    {
        abort(404);
    }

    public function show()
    {
        abort(404);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = [];


//        $inquiries = Inquiry::orderBy('created_at', 'DESC')->paginate(10);
//        $data['inquiries'] = $inquiries;

        $data['view'] = '';

        // get the user
        $user = auth()->user();

        // get received inquiries for user
        // check admin role
        if ($user->admim == 'on' || $user->super_admin == 'on') {
            $data['view'] = 'admin_options';
        }
        $data['user'] = $user;


        abort_if($user->rejected == 'on', 403);

        // get received inquiry rows
        $received_inquiry_rows = \App\Received::orderBy('created_at', 'desc')->where('user_id', $user->id)->paginate(10);

        $data['received_inquiry_rows'] = $received_inquiry_rows; // for pagination links
//        return $received_inquiry_rows;


        // get the inquiry id's from each row
        $received_inquiry_ids = [];
        foreach ($received_inquiry_rows as $row) {
            array_push($received_inquiry_ids, ['inquiry_id' => $row->inquiry_id, 'read' => $row->read]);
        }
//        return $received_inquiry_ids;



        $data['received_inquiries'] = Received::userInquiries(auth()->user()); //  = Received::where('user_id', auth()->user()->id)->get(); // = $received_inquiries;

        // get saved inquiries
        $sent_inquiry_rows = Sent::orderBy('created_at', 'desc')->where('user_id', $user->id)->paginate(10);
        $data['sent_inquiry_rows'] = $sent_inquiry_rows; // for pagination links

        // get the inquiry id's from each row
        $sent_inquiry_ids = [];
        foreach ($sent_inquiry_rows as $row) {
            array_push($sent_inquiry_ids, $row->inquiry_id);
        }

        // remove duplicates from sent list
        $sent_inquiry_ids = array_unique($sent_inquiry_ids);

        // for each id, get the corresponding inquiry
        $sent_inquiries = [];
        foreach($sent_inquiry_ids as $id) {
            $inquiry = \App\Inquiry::where('id', $id)->get();
            foreach ($sent_inquiry_rows as $row) {
                if ($row->inquiry_id == $id) {
                    $inquiry['sent_id'] = $row->id;
                }
            }
            array_push($sent_inquiries, $inquiry);
        }

        $data['sent_inquiries'] = $sent_inquiries;

        return view('sent.index', $data);
    }





    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sent  $sent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sent $sent)
    {
        //
        // get all the sent rows with this inquiry id.
        $sent_arr = Sent::where('inquiry_id', $sent->inquiry_id)->get();

//        return $sent_arr;

        foreach($sent_arr as $sent) {
            $sent->delete();
        }


        session()->flash('success', 'Sent inquiry removed from list');

        return back();
    }
}
