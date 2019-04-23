<?php

namespace App\Http\Controllers;

use App\Saved;
use Illuminate\Http\Request;
use App\Received;

class SavedController extends BaseController
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
        $savedInquires = (array)Saved::getUserSaveInquires(\Auth::user());
        $viewData = $this->getViewData($savedInquires);
        return view('saved.index', $viewData);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
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
     * @param  \App\Saved $saved
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
