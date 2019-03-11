<?php

namespace App\Http\Controllers;

use App\Document;
use foo\bar;
use Illuminate\Http\Request;

class DocumentController extends Controller
{

    public function index()
    {
        abort(404);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $files = request()->attachment;

        if (request()->hasFile('attachment')) {
            foreach ($files as $file) {
                $filename = $file->hashName();
                $file->storeAs('/public/attachments/' . auth()->id(), $filename);
                $path = url('storage/attachments/' . auth()->id() . '/' . $filename);

                // insert into documents table
                $attachment = new Document();

                $attachment->inquiry_id = request()->inquiry_id;
                $attachment->document_link = $path;

                $attachment->save();

            }
        } else {
            session()->flash('error', 'No document was attached.');
            return back();
        }
        session()->flash('success', 'Document attached!');
        return back();
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        //
        $inquiry_id = $document->inquiry_id;
        $document->delete();

        session()->flash('success', 'Document deleted!');

        return redirect("/inquiries/$inquiry_id");
    }
}
