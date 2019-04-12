@extends('layouts.app')

@section('title')
    <title>Create Inquiry | Commercial Broker Connections</title>
@endsection

@section('content')
    <div class="row mt-4">
        <div class="col">
            <div class="mb-4">
                <div class="card card-small h-100">
                    <div class="card-header border-bottom">
                        <h6 class="m-0">Make Inquiry</h6>
                    </div>
                    <div class="d-flex flex-column">
                        <form method="POST" action="/inquiries" enctype="multipart/form-data" class="card-body"
                              id="create-inquiry">
                            @csrf
                            <div class="form-group">
                                <input type="text" class="form-control" id="feInputSubject" placeholder="Subject"
                                       name="subject" required
                                       value="{{ old('subject') }}{{ session('data.subject') }}">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" placeholder="Message"
                                          name="body">{{ old('body') }}{{ session('data.body') }}</textarea>
                            </div>
                            <!-- Select -->
                            @if (session('data.paths'))
                                <?php $documents = array(); ?>
                                @foreach(session('data.paths') as $path)
                                    <?php array_push($documents, $path); ?>
                                @endforeach
                                <input type="hidden" id="documents_array" name="documents_array[]"
                                       value="@foreach ($documents as $document){{ $document }},@endforeach">
                            @endif
                            <input type="hidden" id="cities_array" name="cities_array[]" value>
                            <label>Send To: </label>
                            <div class="form-group mb-1 row  mx-auto add-select">

                                <select data-city="js-input-city" id="" class="form-control col-10 city">
                                    <option disabled selected value="">Choose City...</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->city }}
                                            , {{ $city->state->state }}</option>
                                    @endforeach
                                </select>
                                <a href="#" class="col-2 js-delBtn" id=""><i
                                            class="material-icons text-danger">clear</i></a>

                            </div>
                            <div class="add-link mb-0 mt-2 row container">
                                <a href="#" data-toggle="tooltip" id="add-city" data-placement="top" title="Add City"><i
                                            class="material-icons">add_circle_outline</i></a>
                                <div class="form-control is-invalid mt-4 mb-4 city-error col-10" style="display: none;">
                                    <ul class="row">
                                        <li class="col-10">You must send to at least on market.</li>
                                        <a href="#" class="col-2 js-city-error-delete" id=""><i
                                                    class="material-icons text-danger">clear</i></a>
                                    </ul>

                                </div>
                            </div>

                            <!-- / Select -->


                            <div class="form-group mb-4 row container">
                                <button type="submit" class="btn btn-accent">Send Inquiry</button>
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
                            @if(session('data.msg'))
                                <div class="form-control is-invalid mt-4 mb-4">
                                    <ul>
                                        <li>{{ session('data.msg') }}</li>
                                    </ul>
                                </div>

                            @endif

                        <!--- End Errors --->
                            {{--@if (session('data.paths'))--}}
                            {{--@foreach(session('data.paths') as $path)--}}
                            {{--<input type="hidden" id="cities_array" name="documents_array[]" value="{{ $path }}">--}}
                            {{--<img src="{{ $path }}" style="max-width: 50px;">--}}
                            {{--@endforeach--}}
                            {{--@endif--}}

                            <div class="border-top card-footer">
                                <!-- Custom File Upload -->
                                <label for="customFile2">Attach File To Inquiry</label>

                                <!----- File Upload Group ----->
                                <div class="input-group mb-3" id="">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input col-10 mb-1" data-doc="js-doc" id=""
                                               name="attachment[]">
                                        <label class="custom-file-label" for="" id="">Choose file...</label>
                                    </div>
                                    <a href="#" class="col-2 js-delBtn-2" id=""><i class="material-icons text-danger">clear</i></a>
                                </div>
                                <!----- End File Upload Group ----->

                                <div class="add-link mb-0 row container">
                                    <a href="#" data-toggle="tooltip" data-placement="top" id="add-doc"
                                       title="Add Attachment"><i class="material-icons">attachment</i></a>
                                    <div class="form-control is-invalid mt-4 mb-4 upload-error col-10"
                                         style="display: none;">
                                        <ul class="row">
                                            <li class="col-10">There is no document attached to delete.</li>
                                            <a href="#" class="col-2 js-upload-error-delete" id=""><i
                                                        class="material-icons text-danger">clear</i></a>
                                        </ul>

                                    </div>
                                </div>

                            {{--<button type="submit" class="btn btn-sm btn-secondary">Attach File</button>--}}
                            <!-- / Custom File Upload -->
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
