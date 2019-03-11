@extends('layouts.app')
@section('title')
    <title>Dashboard | Commercial Broker Connections</title>
@endsection
@section('content')
    <div class="row mt-4">
        <div class="col-sm-12 col-lg-4">
            <!-- User Details Card -->
            <div class="card card-small user-details mb-4">
                <div class="card-header p-0">
                    <div class="user-details__bg">
                        <img src="{{ asset("img/user-profile/$user->background_img") }}" alt="User Details Background Image"> </div>
                </div>
                <div class="card-body p-0">
                    <div class="user-details__avatar mx-auto">
                        @if( $user->avatar == 'placeholder.gif')
                            <img src='{{ asset("img/avatars/$user->avatar") }}' alt="User Avatar"> </div>
                        @else
                            {{--replace with unique path to avatar--}}
                            <img src='{{ $user->avatar }}' alt="User Avatar"> </div>
                        @endif

                    <h4 class="text-center m-0 mt-2">{{ $user->first_name }} {{ $user->last_name }}</h4>
                    <p class="text-light text-center m-0 mb-2">
                        <a href="https://www.matexas.com">{{ $user->company_name }}</a>
                    </p>
                    <div class="user-details__user-data border-top border-bottom p-4">
                        <div class="row mb-3">
                            <div class="col w-50">
                                <span>Email</span>
                                <span>{{ $user->email }}</span>
                            </div>
                            <div class="col w-50">
                                <span>Markets</span>
                                <span>{{ $user->main_market }}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col w-50">
                                <span>Phone</span>
                                <span>{{ $user->phone_number }}</span>
                            </div>
                            <div class="col w-50">
                                <span>REC License</span>
                                <span>{{ $user->license }}</span>
                            </div>
                        </div>

                        <div class="card-footer border-top mt-4">
                            <a href="/inquiries/create" class="btn mb-2 btn btn-primary mr-2 d-table mx-auto">Make Inquiry</a>
                        </div>

                    </div>
                </div>
            </div>
            <!-- End User Details Card -->
        </div>
        <div class="col-lg-8">
            <!-- User Activity -->
            <!-- Received Inquiries -->
            <div class="card card-small user-activity mb-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">Received Inquires</h6>
                    <div class="block-handle"></div>
                </div>
                <div class="card-body p-0">

                    @foreach( array_slice($received_inquiries, 0, 4) as $received_inquiry )
                            <div class="user-activity__item pr-3 py-3" data-type="received">
                                <div class="user-activity__item__icon">
                                    <i class="material-icons">mail</i>
                                </div>
                                <div class="user-activity__item__content">
                                    <span class="text-light">{{ $received_inquiry[0]['created_at'] }}</span>

                                    @if( $received_inquiry['read']=== 0)
                                        <p><span class="font-weight-bold">{{ $received_inquiry[0]['subject'] }}</span> | {{ str_limit(strip_tags($received_inquiry[0]['body']), $limit = 30, $end = '...') }}</p>
                                    @else
                                        <p>{{ $received_inquiry[0]['subject'] }} | {{ str_limit(strip_tags($received_inquiry[0]['body']), $limit = 30, $end = '...') }}</p>
                                    @endif

                                </div>
                                <div class="row ml-auto">
                                    <div class="user-activity__item__action ml-auto mr-1">
                                        <a href="/inquiries/{{ $received_inquiry[0]['id'] }}" class="ml-auto btn btn-sm btn-white">View Inquiry</a>
                                    </div>
                                    <div class="user-activity__item__action ml-auto mr-3">
                                        <form method="POST" action="/received/{{ $received_inquiry['received_id'] }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="material-icons">&#xE872;</i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                    @endforeach

                </div>
                <div class="card-footer border-top">
                    <a href="/received" class="btn btn-sm btn-white d-table mx-auto">View All</a>
                </div>
            </div>
            <!-- End Received Inquires -->
            <!-- Sent Inquiries -->
            <div class="card card-small user-activity mb-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">Sent Inquires</h6>
                    <div class="block-handle"></div>
                </div>
                <div class="card-body p-0">

                    @foreach( array_slice($sent_inquiries, 0, 4) as $sent_inquiry )
                            <div class="user-activity__item pr-3 py-3" data-type="sent">
                                <div class="user-activity__item__icon">
                                    <i class="material-icons">send</i>
                                </div>
                                <div class="user-activity__item__content">
                                    <span class="text-light">{{ $sent_inquiry[0]['created_at'] }}</span>
                                    <p>{{ $sent_inquiry[0]['subject'] }} | {{ str_limit(strip_tags($sent_inquiry[0]['body']), $limit = 30, $end = '...') }}</p>
                                </div>
                                <div class="row ml-auto">
                                    <div class="user-activity__item__action ml-auto mr-1">
                                        <a href="/inquiries/{{ $sent_inquiry[0]['id'] }}" class="ml-auto btn btn-sm btn-white">View Inquiry</a>
                                    </div>
                                    <div class="user-activity__item__action ml-auto mr-3">
                                        <form method="POST" action="/sent/{{ $sent_inquiry['sent_id'] }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="material-icons">&#xE872;</i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                    @endforeach

                </div>
                <div class="card-footer border-top">
                    <a href="/sent" class="btn btn-sm btn-white d-table mx-auto">View All</a>
                </div>
            </div>
            <!-- End Sent Inquiries -->
            <!-- Saved Inquiries -->
            <div class="card card-small user-activity mb-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">Saved Inquires</h6>
                    <div class="block-handle"></div>
                </div>
                <div class="card-body p-0">

                    @foreach( $saved_inquiries as $saved_inquiry)
                            <div class="user-activity__item pr-3 py-3" data-type="saved">
                                <div class="user-activity__item__icon">
                                    <i class="material-icons star">star_rate</i>
                                </div>
                                <div class="user-activity__item__content">
                                    <span class="text-light">{{ $saved_inquiry[0]['created_at'] }}</span>
                                    <p>{{ $saved_inquiry[0]['subject'] }} | {{ str_limit(strip_tags($saved_inquiry[0]['body']), $limit = 30, $end = '...') }}</p>
                                </div>
                                <div class="row ml-auto">
                                    <div class="user-activity__item__action ml-auto mr-1">
                                        <a href="/inquiries/{{ $saved_inquiry[0]['id'] }}" class="ml-auto btn btn-sm btn-white">View Inquiry</a>
                                    </div>
                                    <div class="user-activity__item__action ml-auto mr-3">
                                        <form method="POST" action="/saved/{{ $saved_inquiry['saved_id'] }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="material-icons">&#xE872;</i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                    @endforeach


                </div>
                <div class="card-footer border-top">
                    <a href="/saved" class="btn btn-sm btn-white d-table mx-auto">View All</a>
                </div>
            </div>
            <!-- End Saved Inquires -->
            <!-- End User Activity -->
        </div>
    </div>
@endsection
