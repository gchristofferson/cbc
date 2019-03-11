<?php

namespace App\Http\Controllers;

use App\Saved;
use Illuminate\Http\Request;

class SavedController extends Controller
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

        // for each id, get the corresponding inquiry
        $received_inquiries = [];
        foreach($received_inquiry_ids as $received_inquiry_id) {
            $inquiry = \App\Inquiry::where('id', $received_inquiry_id['inquiry_id'])->get();

            $inquiry['read'] = $received_inquiry_id['read'];
            foreach ($received_inquiry_rows as $row) {
                if ($row->inquiry_id == $received_inquiry_id['inquiry_id']) {
                    $inquiry['received_id'] = $row->id;
                }
            }
            array_push($received_inquiries, $inquiry);
        }

        $data['received_inquiries'] = $received_inquiries;

        // get saved inquiries
        $saved_inquiry_rows = \App\Saved::orderBy('created_at', 'desc')->where('user_id', $user->id)->paginate(10);
        $data['saved_inquiry_rows'] = $saved_inquiry_rows; // for pagination links

        // get the inquiry id's from each row
        $saved_inquiry_ids = [];
        foreach ($saved_inquiry_rows as $row) {
            array_push($saved_inquiry_ids, $row->inquiry_id);
        }

        // for each id, get the corresponding inquiry
        $saved_inquiries = [];
        foreach($saved_inquiry_ids as $id) {
            $inquiry = \App\Inquiry::where('id', $id)->get();
            foreach ($saved_inquiry_rows as $row) {
                if ($row->inquiry_id == $id) {
                    $inquiry['saved_id'] = $row->id;
                }
            }
            array_push($saved_inquiries, $inquiry);
        }

        $data['saved_inquiries'] = $saved_inquiries;

        return view('saved.index', $data);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        request()->validate([
            'save' => 'required',
        ]);
        if ((isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']))) {
            if (strtolower(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST)) != strtolower($_SERVER['HTTP_HOST'])) {
            // referer not from the same domain
                $prev_url = $_SERVER['HTTP_REFERER'];
            } else {
                $prev_url = request()->back_url;
            }
        }


        if (isset($prev_url)) {
            session()->flash('prev_url', $prev_url);
        }



        $saved = new Saved();
        $saved->inquiry_id = request()->save;
        $saved->user_id = auth()->id();
        $saved->save();

        session()->flash('success', 'Inquiry Saved!');

        return back();
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Saved  $saved
     * @return \Illuminate\Http\Response
     */
    public function destroy(Saved $saved)
    {
        //
        $saved->delete();

        session()->flash('success', 'Saved inquiry removed from list');

        return back();
    }
}
