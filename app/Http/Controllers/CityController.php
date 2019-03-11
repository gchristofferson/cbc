<?php

namespace App\Http\Controllers;

use App\City;
use App\State;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\City  $city
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(City $city)
    {
        //
        City::create([
            'state_id' => request('state_id'),
            'city' => request('city'),
        ]);

        session()->flash('success', 'City Added Successfully');

        return back();
    }

    public function create()
    {
        abort(404);
    }

    public function show()
    {
        abort(404);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        //
        abort_if(auth()->user()->admin != 'on' || auth()->user()->super_admin != 'on', 403 );
        $data = [];
        $data['city'] = $city;
        $states = State::all();
        $data['states'] = $states;
        $city_state = $city->state;
        $data['city_state'] = $city_state;
        return view('cities.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        //
        $state_id = request('state_id');
        $city->update(request()->validate([
            'city' => 'required',
            'state_id' => 'required',
        ]));

        session()->flash('success', 'City Updated');

        return redirect("/states/$state_id");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        //
        $state_id = $city->state_id;

        // delete notifications for city
        $city->notifications()->delete();

        $city->delete();

        session()->flash('success', 'City and Related User Notifications Deleted');

        return redirect("/states/$state_id");
    }
}
