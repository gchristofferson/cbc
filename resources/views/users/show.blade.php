@extends('layouts.crud')

@section('title')
    <title>Show User | Commercial Broker Connection</title>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 mx-auto mt-4">
            <!-- Edit User Details Card -->
            <div class="card card-small edit-user-details mb-4">
                <div class="card-header p-0">
                    <div class="edit-user-details__bg">
                        <img src='{{ asset("img/user-profile/$user->background_img") }}' alt="User Details Background Image">
                        {{--<label class="edit-user-details__change-background">--}}
                            {{--<i class="material-icons mr-1">&#xE439;</i> Change Background Photo--}}
                            {{--<input class="d-none" type="file" /> </label>--}}
                    </div>
                </div>
                <!-- Site Subscription -->
                {{--<div class="mx-4 site-subscription">--}}
                    {{--<div class="col mb-3">--}}
                        {{--<h6 class="form-text m-0">Site Subscription</h6>--}}
                        {{--<p class="form-text text-muted m-0">You are currently subscribed to a free trial for 9 more days!</p>--}}
                        {{--<p class="form-text text-muted font-weight-bold m-0">Don't lose access to your connections and inquires!</p>--}}
                        {{--<p class="h4 text-monospace">Upgrade your annual membership for only $99!</p>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="mx-4">--}}
                    {{--<div class="col d-flex">--}}
                        {{--<button id="upgrade-btn" type="button" class="mb-2 btn btn-md btn-secondary mr-1 upgrade-btn">Upgrade Now</button>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<hr>--}}
                <!-- End Site Subscription -->
                <div class="card-body p-0">
                    <form method="POST" action="/users/{{ $user->id }}" class="py-4" enctype="multipart/form-data" id="user_update">
                        @method('PATCH')
                        @csrf
                        {{--<div class="form-row mx-4">--}}
                            {{--<div class="col mb-3">--}}
                                {{--<h6 class="form-text m-0">User Details</h6>--}}
                                {{--<p class="form-text text-muted m-0">Setup your general profile details.</p>--}}
                            {{--</div>--}}
                        {{--</div>--}}
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
                                        {{--<div class="input-group input-group-seamless">--}}
                                            {{--<select id="userLocation" class="form-control">--}}
                                                {{--<option selected>Choose City...</option>--}}
                                                {{--<option value="1">One</option>--}}
                                                {{--<option value="2">Two</option>--}}
                                                {{--<option value="3">Three</option>--}}
                                            {{--</select>--}}
                                        {{--</div>--}}
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
                                        {{--<label for="displayEmail">Display Email Publicly</label>--}}
                                        {{--<select class="custom-select">--}}
                                            {{--<option value="1" selected>Yes, display my email</option>--}}
                                            {{--<option value="2">No, do not display my email.</option>--}}
                                        {{--</select>--}}
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
                                        {{--replace with unique path to avatar--}}
                                        <img src='{{ $user->avatar }}' alt="User Avatar">
                                    @endif
                                    <label class="edit-user-details__avatar__change">
                                        <i class="material-icons mr-1">&#xE439;</i>
                                        <input type="file" id="userProfilePicture" class="d-none" name="avatar"> </label>
                                </div>
                                {{--<div class="custom-file d-table mx-auto mt-4 btn btn-sm">--}}
                                    {{--<input type="file" class="custom-file-input col-10 mb-1" data-doc="js-doc" id="" name="avatar[]">--}}
                                    {{--<label class="custom-file-label" for="avatar" id=""><i class="material-icons">&#xE2C3;</i> Upload Image</label>--}}
                                {{--</div>--}}
                                {{--<button class="btn btn-sm btn-white d-table mx-auto mt-4">--}}
                                    {{--<i class="material-icons">&#xE2C3;</i> Upload Image--}}
                                {{--</button>--}}
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
                        <!--- End Errors --->
                        </div>
                        {{--<div class="form-row mx-4">--}}
                            {{--<div class="form-group col-md-8">--}}
                                {{--<label for="userBio">Notification Subscriptions</label>--}}
                                {{--<!-- Default Light Table -->--}}
                                {{--<div class="row">--}}
                                    {{--<div class="col">--}}
                                        {{--<div class="mb-4">--}}
                                            {{--<div class="border-bottom">--}}
                                            {{--</div>--}}
                                            {{--<div class="card-body p-0 pb-3 text-center">--}}
                                                {{--<table class="table mb-0 notification-subscriptions">--}}
                                                    {{--<thead class="bg-light">--}}
                                                    {{--<tr>--}}
                                                        {{--<th scope="col" class="border-0">#</th>--}}
                                                        {{--<th scope="col" class="border-0">City</th>--}}
                                                        {{--<th scope="col" class="border-0">State</th>--}}
                                                        {{--<th scope="col" class="border-0">Delete</th>--}}
                                                    {{--</tr>--}}
                                                    {{--</thead>--}}
                                                    {{--<tbody>--}}
                                                    {{--<tr>--}}
                                                        {{--<td>1</td>--}}
                                                        {{--<td>Sacramento</td>--}}
                                                        {{--<td>California</td>--}}
                                                        {{--<td>--}}
                                                            {{--<button type="button" class="btn btn-danger">--}}
                                                                {{--<i class="material-icons">&#xE872;</i>--}}
                                                            {{--</button>--}}
                                                        {{--</td>--}}
                                                    {{--</tr>--}}
                                                    {{--<tr>--}}
                                                        {{--<td>2</td>--}}
                                                        {{--<td>San Diego</td>--}}
                                                        {{--<td>California</td>--}}
                                                        {{--<td>--}}
                                                            {{--<button type="button" class="btn btn-danger">--}}
                                                                {{--<i class="material-icons">&#xE872;</i>--}}
                                                            {{--</button>--}}
                                                        {{--</td>--}}
                                                    {{--</tr>--}}
                                                    {{--<tr>--}}
                                                        {{--<td>3</td>--}}
                                                        {{--<td>San Francisco</td>--}}
                                                        {{--<td>California</td>--}}
                                                        {{--<td>--}}
                                                            {{--<button type="button" class="btn btn-danger">--}}
                                                                {{--<i class="material-icons">&#xE872;</i>--}}
                                                            {{--</button>--}}
                                                        {{--</td>--}}
                                                    {{--</tr>--}}
                                                    {{--<tr>--}}
                                                        {{--<td>4</td>--}}
                                                        {{--<td>Los Angeles</td>--}}
                                                        {{--<td>California</td>--}}
                                                        {{--<td>--}}
                                                            {{--<button type="button" class="btn btn-danger">--}}
                                                                {{--<i class="material-icons">&#xE872;</i>--}}
                                                            {{--</button>--}}
                                                        {{--</td>--}}
                                                    {{--</tr>--}}
                                                    {{--</tbody>--}}
                                                {{--</table>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<!-- End Default Light Table -->--}}
                            {{--</div>--}}
                            {{--<div class="form-group col-md-4">--}}
                                {{--<label for="userBio">Add Notification Subscription</label>--}}
                                {{--<!-- Default Light Table -->--}}
                                {{--<div class="row">--}}
                                    {{--<div class="col">--}}
                                        {{--<div class="mb-4">--}}
                                            {{--<div class="border-bottom"></div>--}}
                                            {{--<form class="quick-post-form">--}}
                                                {{--<div class="form-group mb-0 row container">--}}
                                                    {{--Select--}}
                                                    {{--<div class="form-group mb-3 col-12">--}}
                                                        {{--<label for="feInputState">State</label>--}}
                                                        {{--<select id="feInputState" class="form-control">--}}
                                                            {{--<option selected>Choose State...</option>--}}
                                                            {{--<option value="1">One</option>--}}
                                                            {{--<option value="2">Two</option>--}}
                                                            {{--<option value="3">Three</option>--}}
                                                        {{--</select>--}}
                                                    {{--</div>--}}
                                                    {{--<div class="form-group mb-3 col-12">--}}
                                                        {{--<label for="feInputCity">City</label>--}}
                                                        {{--<select id="feInputCity" class="form-control">--}}
                                                            {{--<option selected>Choose City...</option>--}}
                                                            {{--<option value="1">One</option>--}}
                                                            {{--<option value="2">Two</option>--}}
                                                            {{--<option value="3">Three</option>--}}
                                                        {{--</select>--}}
                                                    {{--</div>--}}
                                                    {{--<!-- / Select -->--}}
                                                    {{--<button type="submit" class="btn btn-accent add-market-btn">Add Notification</button>--}}
                                                {{--</div>--}}
                                            {{--</form>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<!-- End Default Light Table -->--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<hr>--}}
                        {{--<div class="form-row mx-4">--}}
                            {{--<div class="col mb-3">--}}
                                {{--<h6 class="form-text m-0">Email Notifications</h6>--}}
                                {{--<p class="form-text text-muted m-0">Select if you would you like to receive email notifications.</p>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="form-row mx-4">--}}
                            {{--<label for="conversationsEmailsToggle" class="col col-form-label"> New Inquiries--}}
                                {{--<small class="form-text text-muted"> Sends notification emails when someone sends an inquiry to a City you are subscribed to. </small>--}}
                            {{--</label>--}}
                            {{--<div class="col d-flex">--}}
                                {{--<div class="custom-control custom-toggle ml-auto my-auto">--}}
                                    {{--<input type="checkbox" id="conversationsEmailsToggle" class="custom-control-input" checked>--}}
                                    {{--<label class="custom-control-label" for="conversationsEmailsToggle"></label>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<hr>--}}

                        {{--<div class="form-row mx-4">--}}
                            {{--<div class="col mb-3">--}}
                                {{--<h6 class="form-text m-0">Change Password</h6>--}}
                                {{--<p class="form-text text-muted m-0">Change your current password.</p>--}}
                            {{--</div>--}}
                        {{--</div>--}}
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
                {{--<div class="card-footer border-top">--}}
                    {{--<a href="#" class="btn btn-sm btn-accent ml-auto d-table mr-3">Save Changes</a>--}}
                {{--</div>--}}
            </div>
            <!-- End Edit User Details Card -->
        </div>
    </div>
@endsection
