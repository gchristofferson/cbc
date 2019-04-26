@extends('layouts.app')
@section('title')
    <title>Update Profile - Subscription | Commercial Broker Connections</title>
@endsection
@php(Session::get('data'))
@if($user->approved != 'off')
@section('content')
    <!--- /Errors --->
    @if($errors->any())
        <div class="container-fluid px-0">
            <div class="alert alert-danger alert-dismissible fade show m-0" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
    @endif
    <!--- End Errors --->

    {{--Update User--}}
    <div class="row">
        <div class="col-lg-12 mx-auto mt-4">
            <!-- Edit User Details Card -->
            <div class="card card-small edit-user-details mb-4">
                <div class="card-header p-0">
                    <div class="edit-user-details__bg">
                        <img src='{{ asset("img/user-profile/$user->background_img") }}' alt="User Details Background Image">
                    </div>
                </div>
                <div class="card-body p-0">
                    <form method="POST" action="/users/{{ $user->id }}" class="py-4" enctype="multipart/form-data" id="user_update">
                        @method('PATCH')
                        @csrf
                        <div class="form-row mx-4">
                            <div class="col-lg-8">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="first_name">First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $user->first_name }}"> </div>
                                    <div class="form-group col-md-6">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $user->last_name }}"> </div>
                                    <div class="form-group col-md-6">
                                        <label for="main_market">Main Market</label>
                                        <input type="text" class="form-control" id="main_market" name="main_market" value="{{ $user->main_market }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="phone_number">Phone Number</label>
                                        <div class="input-group input-group-seamless">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="material-icons">&#xE0CD;</i>
                                                </div>
                                            </div>
                                            <input type="tel" class="form-control" id="phone_number" name="phone_number" value="{{ $user->phone_number }}"> </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">Email</label>
                                        <div class="input-group input-group-seamless">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="material-icons">&#xE0BE;</i>
                                                </div>
                                            </div>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}"> </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="license">License</label>
                                        <input type="text" class="form-control" id="main_market" name="license" value="{{ $user->license }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="company_name">Comapany Name</label>
                                        <input type="text" class="form-control" id="main_market" name="company_name" value="{{ $user->company_name }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="company_website">Company Website</label>
                                        <input type="url" class="form-control" id="main_market" name="company_website" value="{{ $user->company_website }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label for="userProfilePicture" class="text-center w-100 mb-4">Profile Picture</label>
                                <div class="edit-user-details__avatar m-auto" data-toggle="tooltip" data-placement="top" title="Upload Square Image">
                                    @if( $user->avatar == 'placeholder.gif')
                                        <img src='{{ asset("img/avatars/$user->avatar") }}' alt="User Avatar">
                                    @else
                                        <img src='{{ $user->avatar }}' alt="User Avatar">
                                    @endif
                                    <label class="edit-user-details__avatar__change">
                                        <i class="material-icons mr-1">&#xE439;</i>
                                        <input type="file" id="userProfilePicture" class="d-none" name="avatar"> </label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer border-top">
                            <button type="submit" class="btn btn-primary">Update User</button>
                        </div>
                    </form>
                    <form class="mx-3 mb-4" method="POST" action="/users/{{ $user->id }}">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-danger">Delete User</button>
                    </form>
                </div>
            </div>
            <!-- End Edit User Details Card -->
        </div>
    </div>

    @if(isset($city->id))
    {{--Notifications--}}
    <div class="card card-small mb-4">
        <div class="card-header border-bottom">
            <h6 class="m-0">City Notifications</h6>
        </div>
        <ul class="list-group ">
            <li class="list-group-item p-3">
                <div class="row">
                    <div class="col form-row">
                        <div class="form-row mx-4">
                            <div class="form-group col-md-4">
                                <label for="userBio">Add Notification Subscription</label>
                                <!-- Default Light Table -->
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <div class="border-bottom"></div>
                                            <form method="POST" action="/notifications/create">
                                                @csrf
                                                <div class="form-group mb-0 row container">
                                                    <div class="form-group mb-3 col-12">
                                                        <label for="feInputCity">City</label>
                                                        <select data-city="js-input-city" id="" class="form-control col-10 city" name="city" required>
                                                            <option value="" selected>Choose City...</option>
                                                            @foreach($cities as $city)
                                                                    <option value="{{ $city->id }}">{{ $city->city }}, {{ $city->state->state }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <!-- / Select -->
                                                    <button type="submit" class="btn btn-accent add-market-btn">Add Notification</button>
                                                </div>
                                            </form>


                                        </div>
                                    </div>
                                </div>
                                <!-- End Default Light Table -->
                            </div>
                            <div class="form-group col-md-8">
                                <label for="userBio">Notification Subscriptions</label>
                                <!-- Default Light Table -->
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <div class="border-bottom">
                                            </div>
                                            <div class="card-body p-0 pb-3 text-center">
                                                <table class="table mb-0 notification-subscriptions">
                                                    <thead class="bg-light">
                                                    <tr>
                                                        <th scope="col" class="border-0">#</th>
                                                        <th scope="col" class="border-0">City</th>
                                                        <th scope="col" class="border-0">State</th>
                                                        <th scope="col" class="border-0">Delete</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>


                                                    @foreach($notifications as $notification)
                                                        <tr>
                                                            <td>{{ $notification->id }}</td>
                                                            <td>{{ $notification->city->city }}</td>
                                                            <td>{{ $notification->city->state->state }}</td>
                                                            <td>
                                                                <form method="POST" action="/notifications/{{ $notification->id }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">
                                                                        <i class="material-icons"></i>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach


                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Default Light Table -->
                            </div>

                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    {{--End Notifications--}}
    @endif

    <!-- Edit User Details Card -->
    <div class="card card-small  mb-4">
        <div class="card-header">
            <h6 class="m-0">Email Notifications</h6>
        </div>
        <div class="card-body p-0">
                <hr>
                <div class="form-row mx-4">
                    <div class="col mb-3">
                        <h6 class="form-text m-0">Email Notifications</h6>
                        <p class="form-text text-muted m-0">Select if you would you like to receive email notifications.</p>
                    </div>
                </div>
                <div class="form-row mx-4">
                    <label for="conversationsEmailsToggle" class="col col-form-label"> New Inquiries
                        <small class="form-text text-muted"> Sends notification emails when someone sends an inquiry to a City you are subscribed to. </small>
                    </label>
                    @if($user->notifications == 'on')
                        <?php $notifications = "checked"; ?>
                    @else
                        <?php $notifications = ""; ?>
                    @endif
                    <form id="notifications" method="POST" action="/users/{{ auth()->id() }}">
                        @csrf
                        @method('PATCH')
                        <div class="col d-flex">
                            <div class="col d-flex">
                                <div class="custom-control custom-toggle ml-auto my-auto">
                                    <input id='adminToggleHidden' type='hidden' value='off' name='notifications'>
                                    <input type="checkbox" id="adminToggle" value='on' name="notifications" class="custom-control-input" <?=$notifications;?>>
                                    <label class="custom-control-label" id="adminToggleLabel" for="adminToggle"></label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                {{--<hr>--}}

                {{--<div class="form-row mx-4">--}}
                    {{--<div class="col mb-3">--}}
                        {{--<h6 class="form-text m-0">Change Password</h6>--}}
                        {{--<p class="form-text text-muted m-0">Change your current password.</p>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--<form>--}}
                {{--<div class="form-row mx-4">--}}
                    {{--<div class="form-group col-md-4">--}}
                        {{--<label for="firstName">Old Password</label>--}}
                        {{--<input type="text" class="form-control" id="firstName" placeholder="Old Password"> </div>--}}
                    {{--<div class="form-group col-md-4">--}}
                        {{--<label for="lastName">New Password</label>--}}
                        {{--<input type="text" class="form-control" id="lastName" placeholder="New Password"> </div>--}}
                    {{--<div class="form-group col-md-4">--}}
                        {{--<label for="emailAddress">Repeat New Password</label>--}}
                        {{--<input type="email" class="form-control" id="emailAddress" placeholder="Repeat New Password"> </div>--}}
                {{--</div>--}}
            {{--</form>--}}
        </div>
        {{--<div class="card-footer border-top">--}}
            {{--<a href="#" class="btn btn-sm btn-accent ml-auto d-table mr-3">Save Changes</a>--}}
        {{--</div>--}}
    </div>
    <!-- End Edit User Details Card -->
@endsection
@endif
