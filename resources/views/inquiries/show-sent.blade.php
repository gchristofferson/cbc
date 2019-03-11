@extends('layouts.app')
@section('title')
    <title>Received Inquiries | Commercial Broker Connections</title>
@endsection
@section('content')
    <div class="row mt-4">
        <div class="col-lg-12">
            <!-- User Activity -->
            <!-- Received Inquiries -->
            <div class="card card-small user-activity mb-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">Sent Inquires</h6>
                    <div class="block-handle"></div>
                </div>
                <div class="no-footer border-bottom">
                    <div class="dataTables_length" id="DataTables_Table_0_length"><label>Show <select
                                name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select> entries</label></div>
                    <div id="DataTables_Table_0_filter" class="dataTables_filter">
                        <label>Search:<input type="search" class="" placeholder="" aria-controls="DataTables_Table_0"></label>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="card-body p-0">
                        @foreach( $inquiries as $inquiry )
                            @if( $inquiry->type == 'sent')


                                <div class="user-activity__item pr-3 py-3">
                                    <div class="user-activity__item__icon">
                                        <i class="material-icons">send</i>
                                    </div>
                                    <div class="user-activity__item__content">
                                        <span class="text-light">{{ $inquiry->received }}</span>

                                        @if( $inquiry->read === 0)
                                            <p class="font-weight-bold">{{ $inquiry->subject }} | {{ str_limit(strip_tags($inquiry->body), $limit = 30, $end = '...') }}</p>
                                        @else
                                            <p>{{ $inquiry->subject }} | {{ str_limit(strip_tags($inquiry->body), $limit = 30, $end = '...') }}</p>
                                        @endif

                                    </div>
                                    <div class="user-activity__item__action ml-auto">
                                        <button class="ml-auto btn btn-sm btn-white" data-toggle="modal" data-target="#detail-view-{{ $inquiry->inquiry_id }}">View Inquiry</button>
                                        <button type="button" class="btn btn-danger">
                                            <i class="material-icons">&#xE872;</i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endforeach


                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">Showing 1
                        to 9 of 9 entries
                    </div>
                    <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate"><a
                            class="paginate_button previous disabled" aria-controls="DataTables_Table_0" data-dt-idx="0"
                            tabindex="0" id="DataTables_Table_0_previous">Previous</a><span><a
                                class="paginate_button current" aria-controls="DataTables_Table_0" data-dt-idx="1" tabindex="0">1</a></span><a
                            class="paginate_button next disabled" aria-controls="DataTables_Table_0" data-dt-idx="2"
                            tabindex="0" id="DataTables_Table_0_next">Next</a></div>
                </div>
            </div>
            <!-- End Received Inquires -->
            <!-- End User Activity -->
        </div>
    </div>
@endsection
@section('modals')
    @foreach( $inquiries as $inquiry )
        <!-- Modal Detail View -->
        <div class="modal fade" id="detail-view-{{ $inquiry->inquiry_id }}" tabindex="-1" role="dialog" aria-labelledby="make-inquiry" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{ ucfirst("$inquiry->type") }} Inquiry</h5>
                        <button id="reply-close" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h6 class="font-weight-bold">Subject:<span class="font-weight-normal"> {{ $inquiry->subject }}</span></h6>
                        <hr class="my-4">
                        <div class="font-weight-normal">
                            {!! $inquiry->body !!}
                        </div>
                        @if( $inquiry->type == 'received' || $inquiry->type == 'saved')
                            <p>{{ $inquiry->sender_first_name }} {{ $inquiry->sender_last_name }}<br>
                                <a href="{{ $inquiry->sender_company_website }}">{{ $inquiry->sender_company_name }}</a><br>
                                <a href="mailto:{{ $inquiry->sender_email }}">{{ $inquiry->sender_email }}</a><br>
                                {{ $inquiry->sender_phone }}</p>
                        @else
                            <p>{{ $agent->first_name }} {{ $agent->last_name }}<br>
                                <a href="{{ $agent->company_website }}">{{ $agent->company_name }}</a><br>
                                <a href="mailto:{{ $agent->email }}">{{ $agent->email }}</a><br>
                                {{ $agent->phone_number }}</p>
                        @endif
                        @if( $inquiry->documents->document )
                            <span class="file-manager__group-title text-uppercase text-black text-md-left">Documents</span>
                        @endif
                        <div class="row">

                            @foreach( $inquiry->documents->document as $document)
                                <div class="col-12 col-md-6">
                                    <a href="{{ asset("doc/$document") }}" target="_blank">
                                        <h6 class="file-manager__item-title">
                                        <span class="file-manager__item-icon">
                                            <i class="material-icons"></i>
                                        </span> {{ $document }}
                                        </h6>
                                    </a>
                                </div>
                            @endforeach

                            <div class="card-footer border-top w-100">
                                @if( $inquiry->type == 'received' || $inquiry->type == 'sent')
                                    <button id="reply" class="btn btn-accent save">
                                        <i class="material-icons">star</i> Save Inquiry</button>
                                @else
                                    <button type="button" class="btn btn-danger">
                                        <i class="material-icons">&#xE872;</i></button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Detail View -->
    @endforeach
@endsection
