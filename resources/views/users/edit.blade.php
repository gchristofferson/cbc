@extends('layouts.admin-crud')

@section('title')
    <title>Edit User | Commercial Broker Connections</title>
@endsection

@section('content')
    @if($back_url = Session::get('prev_url'))
        <a href="{{ $back_url }}" id="save" class="btn btn-secondary btn-sm mt-4 save">
    @else
        <a href="{{ URL::previous() }}" id="save" class="btn btn-secondary btn-sm mt-4 save">
    @endif
        <i class="material-icons">keyboard_backspace</i> Back</a>
    {{--{{ $user->first_name }}--}}
    <div class="card card-small mt-4">
        <div class="card-header border-bottom">
            <h6 class="m-0">Edit User</h6>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item p-3">
                <div class="row">
                    <div class="col">
                        <form method="POST" action="/users/{{ $update->id }}">
                            @method('PATCH')
                            @csrf

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" value="{{ $update->first_name }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" value="{{ $update->last_name }}" required> </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ $update->email }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="license">Real Estate License</label>
                                <input type="text" class="form-control" id="license" name="license" placeholder="License #" value="{{ $update->license }}" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="company_name">Company Name</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Name" value="{{ $update->company_name }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="company_website">Company Website</label>
                                    <input type="url" class="form-control" id="company_website" name="company_website" placeholder="example.com" value="{{ $update->company_website }}"> </div>
                            </div>

                            <div class="form-group">
                                <label for="main_market">Main Market</label>
                                <input type="text" class="form-control" id="main_market" name="main_market" placeholder="Los Angeles, California" value="{{ $update->main_market }}">
                            </div>

                            <div class="form-group">
                                <label for="phone_number">Phone Number</label>
                                <input type="tel" class="form-control" id="phone_number" name="phone_number" placeholder="Phone Number" value="{{ $update->phone_number }}">
                            </div>

                            <!-- Custom File Upload -->
                            <label for="customFile2">Upload Avatar Image</label>
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="customFile2" value="{{ $update->avatar }}">
                                    <label class="custom-file-label" for="customFile2">Choose file...</label>
                                </div>
                            </div>
                            <!-- / Custom File Upload -->
                            <!-- / Admin Area -->
                            @if($update->admin == 'on')
                                <?php $admin = "checked"; ?>
                            @else
                                <?php $admin = ""; ?>
                            @endif

                            <div class="form-row mx-4 form-group">
                                <label for="adminToggle" class="col col-form-label"> Admin
                                    <small class="form-text text-muted">Turn admin role on/off for this user </small>
                                </label>
                                <div class="col d-flex">
                                    <div class="custom-control custom-toggle ml-auto my-auto">
                                        <input id='adminToggleHidden' type='hidden' value='off' name='admin'>
                                        <input type="checkbox" id="adminToggle" value='on' name="admin" class="custom-control-input" <?=$admin;?>>
                                        <label class="custom-control-label" id="adminToggleLabel" for="adminToggle"></label>
                                    </div>
                                </div>
                            </div>

                            @if($update->approved == 'on')
                                <?php $approved = "checked"; ?>
                            @else
                                <?php $approved = ""; ?>
                            @endif

                            <div class="form-row mx-4 form-group">
                                <label for="approvedToggle" class="col col-form-label"> Approved
                                    <small class="form-text text-muted">Turn approved status on/off for this user </small>
                                </label>
                                <div class="col d-flex">
                                    <div class="custom-control custom-toggle ml-auto my-auto">
                                        <input id='approvedToggleHidden' type='hidden' value='off' name='approved'>
                                        <input type="checkbox" id="approvedToggle" name="approved" class="custom-control-input" <?=$approved;?>>
                                        <label class="custom-control-label" for="approvedToggle"></label>
                                    </div>
                                </div>
                            </div>
                            <!-- End Admin Area -->

                            <button type="submit" class="btn btn-primary" style="margin-bottom: 1em;">Update User</button>
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
                        <form method="POST" action="/users/{{ $update->id }}">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete User</button>
                        </form>
                    </div>
                </div>
            </li>
        </ul>
    </div>
@endsection
