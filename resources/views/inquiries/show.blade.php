@extends('layouts.app')
@section('title')
    <title>View Inquiry | Commercial Broker Connections</title>
@endsection
@section('content')
    @if($back_url = Session::get('prev_url'))
        <a href="{{ $back_url }}" id="save" class="btn btn-secondary btn-sm mt-4 save">
    @else
        <a href="{{ URL::previous() }}" id="save" class="btn btn-secondary btn-sm mt-4 save">
    @endif
        <i class="material-icons">keyboard_backspace</i> Back</a>

        <!-- Modal Detail View -->
        <div class="card card-small user-activity mb-4 mt-4">
            <div class="">
                <div>
                    <div class="card-header border-bottom">
                        <h5>Inquiry</h5>

                    </div>
                    <div class="modal-body">
                        <h6 class="font-weight-bold">Subject:<span class="font-weight-normal"> {{ $inquiry->subject }}</span></h6>
                        <hr class="my-4">
                        <div class="font-weight-normal mb-3">
                            {!! $inquiry->body !!}
                        </div>

                            <p>{{ $inquiry->user->first_name }} {{ $inquiry->user->last_name }}<br>
                                <a href="{{ $inquiry->user->company_website }}">{{ $inquiry->user->company_name }}</a><br>
                                <a href="mailto:{{ $inquiry->user->email }}">{{ $inquiry->user->email }}</a><br>
                                {{ $inquiry->user->phone }}</p>

                        <label>Sent To: </label>
                        <div class="form-group mb-1 row  mx-auto add-select">
                            <ul>
                                @foreach($cities as $city)
                                    <li>{{ $city->city }}, {{ $city->state->state }}</li>
                                @endforeach
                            </ul>
                        </div>


                        @if( isset($inquiry->documents[0]['id']) )
                            <span class="file-manager__group-title text-uppercase text-md-left">Documents</span>
                        @endif
                        <div class="row">

                            @foreach( $inquiry->documents as $document)
                                <div class="col-12 col-md-6">
                                    <a href="{{ $document->document_link }}" target="_blank">
                                        <h6 class="file-manager__item-title">
                                        <span class="file-manager__item-icon">
                                            <i class="material-icons"></i>
                                        </span> Attachment {{ $loop->index + 1 }}
                                        </h6>
                                    </a>
                                </div>
                            @endforeach

                            <div class="card-footer border-top w-100 row">
                                @if($saved !== true)
                                    <form method="POST" action="/saved">
                                        @csrf
                                        <input type="hidden" name="save" value="{{ $inquiry->id }}">
                                        <input type="hidden" name="back_url" value="{{ URL::previous() }}">

                                        <button id="save" type="submit" class="btn btn-accent mr-2">
                                            <i class="material-icons">star</i> Save Inquiry
                                        </button>
                                    </form>
                                @endif
                                @if($inquiry->user_id == auth()->id() || $user->admin == 'on' || $user->super_admin == 'on')
                                    <a href="/inquiries/{{ $inquiry->id }}/edit" id="edit" class="btn btn-secondary save mr-2">
                                        <i class="material-icons">edit</i> Edit Inquiry</a>
                                @endif
                                {{--TODO: This button will change depending on if person is an admin or not--}}
                                @if($user->admin == 'on' || $user->admin == 'on' || $user->super_admin == 'on')
                                    <form method="POST" action="/inquiries/{{ $inquiry->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button id="reply" class="btn btn-danger save mr-2" onclick="return confirm('You will delete this inquiry from all users who have received or saved it as well as any attached documents. Are you sure you want to delete this inquiry?');">
                                            <i class="material-icons">delete</i> Delete Inquiry
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Detail View -->
@endsection

