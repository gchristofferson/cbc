@extends('layouts.app')
{{--TODO: When editing inquiry, can't edit citis sent to, but can edit message/documents and resend--}}

@section('title')
    <title>Edit Inquiry | Commercial Broker Connections</title>
@endsection

@section('content')
    @if($back_url = Session::get('prev_url'))
        <a href="{{ $back_url }}" id="save" class="btn btn-secondary btn-sm mt-4 save">
    @else
        <a href="{{ URL::previous() }}" id="save" class="btn btn-secondary btn-sm mt-4 save">
    @endif

    <i class="material-icons">keyboard_backspace</i> Back</a>

    <div class="row mt-4">
        <div class="col">
            <div class="mb-4">
                <div class="card card-small h-100">
                    <div class="card-header border-bottom">
                        <h6 class="m-0">Edit Inquiry</h6>
                    </div>
                    <div class="d-flex flex-column">
                        <form method="POST" action="/inquiries/{{ $inquiry->id }}" enctype="multipart/form-data" class="card-body mb-0" id="create-inquiry">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <input type="text" class="form-control" id="feInputSubject" placeholder="Subject" name="subject" required value="{{ $inquiry->subject }}">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" placeholder="Message" name="body">{{ $inquiry->body }}</textarea>
                            </div>
                            <!-- Select -->

                            <input type="hidden" id="cities_array" name="cities_array[]" value>
                            <label>Sent To: </label>
                            <div class="form-group mb-1 row  mx-auto add-select">
                                <ul>
                                    @foreach($cities as $city)
                                        <li>{{ $city->city }}, {{ $city->state->state }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <!-- / Select -->
                            <div class="form-group row container">
                                <button type="submit" id="update" class="btn btn-accent save mr-2">
                                    <i class="material-icons">update</i> Update Inquiry</button>
                            </div>
                            <!--- /Errors --->
                            @if($errors->any())
                                <div class="form-control is-invalid mt-4 mb-4">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            {{--@if(session('data.msg'))--}}
                                {{--<div class="form-control is-invalid mt-4 mb-4">--}}
                                    {{--<ul>--}}
                                        {{--<li>{{ session('data.msg') }}</li>--}}
                                    {{--</ul>--}}
                                {{--</div>--}}

                            {{--@endif--}}

                        <!--- End Errors --->
                            {{--@if (session('data.paths'))--}}
                            {{--@foreach(session('data.paths') as $path)--}}
                            {{--<input type="hidden" id="cities_array" name="documents_array[]" value="{{ $path }}">--}}
                            {{--<img src="{{ $path }}" style="max-width: 50px;">--}}
                            {{--@endforeach--}}
                            {{--@endif--}}
                        </form>
                        <div class="form-group ml-0 row container">
                            <form method="POST" action="/inquiries/{{ $inquiry->id }}">
                                @method('DELETE')
                                @csrf
                                    <button id="delete" class="btn btn-danger save" type="submit" onclick="return confirm('You will also delete any saved or received inquiries for other users of this inquiry as well as attached documents. Are you sure you want to delete this inquiry?');">
                                        <i class="material-icons">delete</i> Delete Inquiry
                                    </button>
                            </form>
                        </div>
                        <div class="border-top card-footer">
                            <!-- Custom File Upload -->
                            <label for="customFile2">Attachments</label>

                            <!----- File Upload Group ----->
                            <div class="input-group mb-3" id="">
                                @if($documents->count())
                                    <div class="col-md-12 mb-3">
                                        <strong class="text-muted d-block mb-2"></strong>
                                        <table class="table mb-0">
                                            <thead class="bg-light">
                                            <tr>
                                                <th scope="col" class="border-0">#</th>
                                                <th scope="col" class="border-0">Attachment</th>
                                                <th scope="col" class="border-0">Delete</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($documents as $document)
                                                <tr>
                                                    <td>{{ $document->id }}</td>
                                                    <td><a href="{{ $document->document_link }}" target="_blank">Attachement {{ $loop->index + 1}}</a></td>
                                                    <td>
                                                        <div class="btn-group btn-group-md">
                                                            <form method="POST" action="/documents/{{ $document->id }}">
                                                                @method('DELETE')
                                                                @csrf
                                                                <input type='hidden' value={{ $inquiry->id }} name='inquiry_id'>
                                                                <button type="submit" class="btn btn-white" onclick="return confirm('This will result in broken links to this document in sent emails. Are you sure you want to delete this attachment?');">
                                                                <span class="text-danger">
                                                                    <i class="material-icons">delete</i>
                                                                </span> Delete
                                                                </button>
                                                            </form>

                                                            {{--<button type="button" onclick='window.location.replace("/documents/{{ $document->id }}/edit")' class="btn btn-white">--}}
                                                            {{--<span class="text-light">--}}
                                                            {{--<i class="material-icons">more_vert</i>--}}
                                                            {{--</span> Edit--}}
                                                            {{--</button>--}}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                                {{--<ul>--}}
                                {{--@foreach($documents as $document)--}}
                                {{--<li><a href="{{ $document->document_link }}">Attachment {{ $loop->index }}</a> </li>--}}
                                {{--@endforeach--}}
                                {{--</ul>--}}
                            </div>
                            <!----- End File Upload Group ----->


                        {{--<button type="submit" class="btn btn-sm btn-secondary">Attach File</button>--}}
                        <!-- / Custom File Upload -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- create new city --}}

    <div class="card card-small mb-4">
        <div class="card-header border-bottom">
            <h6 class="m-0">Add Attachment</h6>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item p-3">
                <div class="row">
                    <div class="col">
                        <form method="POST" action="/documents" enctype="multipart/form-data">
                            @csrf

                            <!----- File Upload Group ----->
                                <div class="input-group mb-3" id="">
                                    <div class="custom-file">
                                        <input type='hidden' value={{ $inquiry->id }} name='inquiry_id'>
                                        <input type="file" class="custom-file-input col-10 mb-1" data-doc="js-doc" id="" name="attachment[]">
                                        <label class="custom-file-label" for="" id="">Choose file...</label>
                                    </div>
                                </div>
                                <!----- End File Upload Group ----->

                            <button type="submit" class="btn btn-primary">Add Attachment</button>

                            <!--- /Errors --->
                            @if($errors->any())
                                <div class="form-control is-invalid mt-4 mb-4">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                        @endif
                        <!--- End Errors --->

                        </form>
                    </div>
                </div>
            </li>
        </ul>
    </div>
@endsection
