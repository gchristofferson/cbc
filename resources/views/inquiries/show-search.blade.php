@extends('layouts.app')
@section('title')
    <title>Search Results | Commercial Broker Connections</title>
@endsection
@section('content')
    <div class="row mt-4">
        <div class="col-lg-12">
            <!-- User Activity -->
            <!-- Received Inquiries -->
            <div class="card card-small user-activity mb-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">Search Results for: "search term"</h6>
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
                    <div class="user-activity__item pr-3 py-3">
                        <div class="user-activity__item__icon">
                            <i class="material-icons">email</i>
                        </div>
                        <div class="user-activity__item__content">
                            <span class="text-light">23 Minutes ago</span>
                            <p class="font-weight-bold">This is an inquiry subject | This is the message truncated ...</p>
                        </div>
                        <div class="user-activity__item__action ml-auto">
                            <button class="ml-auto btn btn-sm btn-white" data-toggle="modal" data-target="#detail-view">View Inquiry</button>
                            <button type="button" class="btn btn-danger">
                                <i class="material-icons">&#xE872;</i>
                            </button>
                        </div>
                    </div>
                    <div class="user-activity__item pr-3 py-3">
                        <div class="user-activity__item__icon">
                            <i class="material-icons">send</i>
                        </div>
                        <div class="user-activity__item__content">
                            <span class="text-light">2 Hours ago</span>
                            <p class="font-weight-bold">This is an inquiry subject | This is the message truncated ...</p>
                        </div>
                        <div class="user-activity__item__action ml-auto">
                            <button class="ml-auto btn btn-sm btn-white">View Inquiry</button>
                            <button type="button" class="btn btn-danger">
                                <i class="material-icons">&#xE872;</i>
                            </button>
                        </div>
                    </div>
                    <div class="user-activity__item pr-3 py-3">
                        <div class="user-activity__item__icon">
                            <i class="material-icons">star</i>
                        </div>
                        <div class="user-activity__item__content">
                            <span class="text-light">3 Hours 10 Minutes ago</span>
                            <p>This is an inquiry subject | This is the message truncated ...</p>
                        </div>
                        <div class="user-activity__item__action ml-auto">
                            <button class="ml-auto btn btn-sm btn-white">View Inquiry</button>
                            <button type="button" class="btn btn-danger">
                                <i class="material-icons">&#xE872;</i>
                            </button>
                        </div>
                    </div>
                    <div class="user-activity__item pr-3 py-3">
                        <div class="user-activity__item__icon">
                            <i class="material-icons">email</i>
                        </div>
                        <div class="user-activity__item__content">
                            <span class="text-light">2 Days ago</span>
                            <p>This is an inquiry subject | This is the message truncated ...</p>
                        </div>
                        <div class="user-activity__item__action ml-auto">
                            <button class="ml-auto btn btn-sm btn-white">View Inquiry</button>
                            <button type="button" class="btn btn-danger">
                                <i class="material-icons">&#xE872;</i>
                            </button>
                        </div>
                    </div>
                    <div class="user-activity__item pr-3 py-3">
                        <div class="user-activity__item__icon">
                            <i class="material-icons">star</i>
                        </div>
                        <div class="user-activity__item__content">
                            <span class="text-light">2 Days ago</span>
                            <p>This is an inquiry subject | This is the message truncated ...</p>
                        </div>
                        <div class="user-activity__item__action ml-auto">
                            <button class="ml-auto btn btn-sm btn-white">View Inquiry</button>
                            <button type="button" class="btn btn-danger">
                                <i class="material-icons">&#xE872;</i>
                            </button>
                        </div>
                    </div>
                    <div class="user-activity__item pr-3 py-3">
                        <div class="user-activity__item__icon">
                            <i class="material-icons">send</i>
                        </div>
                        <div class="user-activity__item__content">
                            <span class="text-light">2 Days ago</span>
                            <p>This is an inquiry subject | This is the message truncated ...</p>
                        </div>
                        <div class="user-activity__item__action ml-auto">
                            <button class="ml-auto btn btn-sm btn-white">View Inquiry</button>
                            <button type="button" class="btn btn-danger">
                                <i class="material-icons">&#xE872;</i>
                            </button>
                        </div>
                    </div>
                    <div class="user-activity__item pr-3 py-3">
                        <div class="user-activity__item__icon">
                            <i class="material-icons">send</i>
                        </div>
                        <div class="user-activity__item__content">
                            <span class="text-light">2 Days ago</span>
                            <p>This is an inquiry subject | This is the message truncated ...</p>
                        </div>
                        <div class="user-activity__item__action ml-auto">
                            <button class="ml-auto btn btn-sm btn-white">View Inquiry</button>
                            <button type="button" class="btn btn-danger">
                                <i class="material-icons">&#xE872;</i>
                            </button>
                        </div>
                    </div>
                    <div class="user-activity__item pr-3 py-3">
                        <div class="user-activity__item__icon">
                            <i class="material-icons">star</i>
                        </div>
                        <div class="user-activity__item__content">
                            <span class="text-light">2 Days ago</span>
                            <p>This is an inquiry subject | This is the message truncated ...</p>
                        </div>
                        <div class="user-activity__item__action ml-auto">
                            <button class="ml-auto btn btn-sm btn-white">View Inquiry</button>
                            <button type="button" class="btn btn-danger">
                                <i class="material-icons">&#xE872;</i>
                            </button>
                        </div>
                    </div>
                    <div class="user-activity__item pr-3 py-3">
                        <div class="user-activity__item__icon">
                            <i class="material-icons">search</i>
                        </div>
                        <div class="user-activity__item__content">
                            <span class="text-light">2 Days ago</span>
                            <p>This is an inquiry subject | This is the message truncated ...</p>
                        </div>
                        <div class="user-activity__item__action ml-auto">
                            <button class="ml-auto btn btn-sm btn-white">View Inquiry</button>
                            <button type="button" class="btn btn-danger">
                                <i class="material-icons">&#xE872;</i>
                            </button>
                        </div>
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
@section('button')
    <button id="reply" class="btn btn-accent save">
        <i class="material-icons">star</i> Save Inquiry</button>
@endsection

