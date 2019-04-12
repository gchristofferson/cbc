<?php

namespace App\Http\Controllers;

use App\Inquiry;
use App\Received;
use bar\baz\source_with_namespace;
use Illuminate\Http\Request;

class ReceivedController extends Controller
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

        if ($user->approved == 'off' ) {
            session()->flash('error', 'Your account is still pending approval');
            return redirect('/update-profile');
        }

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



        return view('received.index', $data);
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Received  $received
     * @return \Illuminate\Http\Response
     */
    public function destroy(Received $received)
    {
        //

        // delete inquiry
        $received->delete();

        session()->flash('success', 'Inquiry Removed from List');

        return back();

    }
}
