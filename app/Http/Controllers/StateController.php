<?php

namespace App\Http\Controllers;

use App\Inquiry;
use App\State;
use foo\bar;
use http\Exception\BadConversionException;
use Illuminate\Http\Request;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        abort_if(auth()->user()->admin != 'on' || auth()->user()->super_admin != 'on', 403 );
        $data = [];
        $states = State::orderBy('created_at', 'desc')->paginate(10);
        $data['states'] = $states;

        $data['view'] = '';

        // get user
        $user = auth()->user();

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
            return view('states.index-admin', $data);
        } else {
            return back();
        }


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        abort_if(auth()->user()->admin != 'on' || auth()->user()->super_admin != 'on', 403 );
        return view('states.create');
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
        $data = [];
        State::create(request()->validate([
            'state' => 'required',
            'price' => 'required',
        ]));

        session()->flash('success', 'State Created!');

        return redirect('/states');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\State  $state
     * @return \Illuminate\Http\Response
     */
    public function show(State $state)
    {
        //
        abort_if(auth()->user()->admin != 'on' || auth()->user()->super_admin != 'on', 403 );
        $data = [];
        $data['state'] = $state;

        $data['view'] = '';

        // get user
        $user = auth()->user();

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

        if (auth()->user()->admin == 'on' || auth()->user()->super_admin == 'on') {
            return view('states.show', $data);
        } else {
            return back();
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\State  $state
     * @return \Illuminate\Http\Response
     */
    public function edit(State $state)
    {
        //
        abort_if(auth()->user()->admin != 'on' || auth()->user()->super_admin != 'on', 403 );
        $data = [];
        $data['state'] = $state;
        return view('states.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\State  $state
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, State $state)
    {
        //
        $state->update(request()->validate([
            'state' => 'required',
            'price' => 'required',
        ]));

        session()->flash('success', 'State Updated Successfully');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\State  $state
     * @return \Illuminate\Http\Response
     */
    public function destroy(State $state)
    {
        //
        // delete cities of state
        $state->cities()->delete();

        // delete subscriptions
        $state->subscriptions()->delete();

        $state->delete();

        session()->flash('success', 'State, related cities and notifications deleted');

        return redirect('/states');
    }
}
