@extends('layouts.app')
@section('title')
    <title>Update Profile - Subscription | Commercial Broker Connections</title>
@endsection
@php(Session::get('data'))
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
                        <img src='{{ asset("img/user-profile/$user->background_img") }}'
                             alt="User Details Background Image">
                    </div>
                </div>
                <div class="card-body p-0">
                    <form method="POST" id="updatedata" action="/users/{{ $user->id }}" class="py-4"
                          enctype="multipart/form-data" id="user_update">
                        @method('PATCH')
                        @csrf
                        <div class="form-row mx-4">
                            <div class="col-lg-8">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="first_name">First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name"
                                               value="{{ $user->first_name }}"></div>
                                    <div class="form-group col-md-6">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name"
                                               value="{{ $user->last_name }}"></div>
                                    {{--<div class="form-group col-md-6">--}}
                                    {{--<label for="main_market">Main Market</label>--}}
                                    {{--<input type="text" class="form-control" id="main_market" name="main_market" value="{{ $user->main_market }}">--}}
                                    {{--</div>--}}
                                    <div class="form-group col-md-6">
                                        <label for="phone_number">Phone Number</label>
                                        <div class="input-group input-group-seamless">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="material-icons">&#xE0CD;</i>
                                                </div>
                                            </div>
                                            <input type="tel" class="form-control" id="phone_number" name="phone_number"
                                                   value="{{ $user->phone_number }}"></div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">Email</label>
                                        <div class="input-group input-group-seamless">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="material-icons">&#xE0BE;</i>
                                                </div>
                                            </div>
                                            <input type="email" class="form-control" id="email" disabled name="email"
                                                   value="{{ $user->email }}"></div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="license">License</label>
                                        <input type="text" class="form-control" disabled id="main_market" name="license"
                                               value="{{ $user->license }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="company_name">Comapany Name</label>
                                        <input type="text" class="form-control" id="main_market" name="company_name"
                                               value="{{ $user->company_name }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="company_website">Company Website</label>
                                        <input type="url" class="form-control" id="main_market" name="company_website"
                                               value="{{ $user->company_website }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label for="userProfilePicture" class="text-center w-100 mb-4">Profile Picture</label>
                                <div class="edit-user-details__avatar m-auto" data-toggle="tooltip" data-placement="top"
                                     title="Upload Square Image">
                                    @if( $user->avatar == 'placeholder.gif')
                                        <img src='{{ asset("img/avatars/$user->avatar") }}' alt="User Avatar">
                                    @else
                                        <img src='{{ $user->avatar }}' alt="User Avatar">
                                    @endif
                                    <label class="edit-user-details__avatar__change">
                                        <i class="material-icons mr-1">&#xE439;</i>
                                        <input type="file" id="userProfilePicture" class="d-none" name="avatar">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer border-top">
                        </div>
                    </form>
                    <form class="mx-3 mb-4" id='deleteuser' method="POST" action="/users/{{ $user->id }}">
                        @method('DELETE')
                        @csrf
                    </form>
                </div>
            </div>
            <!-- End Edit User Details Card -->
        </div>
    </div>


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
                    <small class="form-text text-muted"> Sends notification emails when someone sends an inquiry to a
                        City you are subscribed to.
                    </small>
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
                                <input type="checkbox" id="adminToggle" value='on' name="notifications"
                                       class="custom-control-input" <?=$notifications;?>>
                                <label class="custom-control-label" id="adminToggleLabel" for="adminToggle"></label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <div class="mb-4">
            <span onclick='document.getElementById("updatedata").submit();' type="submit"
                  class="btn btn-sm btn-primary">Update User</span>
        <span onclick='if(confirm("Are you sure you want to delete your account? This cannot be undone.")){ document.getElementById("deleteuser").submit(); }' type="submit"
              class="btn btn-sm btn-danger">Delete User</span>
    </div>
    <script>

    </script>
    <!-- End Edit User Details Card -->
@endsection
