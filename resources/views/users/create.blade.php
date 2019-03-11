@extends('layouts.crud')

@section('title')
    <title>Create User | Commercial Broker Connections</title>
@endsection

@section('content')
    <div class="card card-small">
    <div class="card-header border-bottom">
        <h6 class="m-0">Create User Account</h6>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item p-3">
            <div class="row">
                <div class="col">
                    <form method="POST" action="/users">
                        {{ csrf_field() }}

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" required="" value="{{ old('first_name') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" required="" value="{{ old('last_name') }}"> </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required="" value="{{ old('email') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="license">Real Estate License</label>
                            <input type="text" class="form-control" id="license" name="license" placeholder="License #" required="" value="{{ old('license') }}">
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="company_name">Company Name</label>
                                <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Name" value="{{ old('company_name') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="company_website">Company Website</label>
                                <input type="url" class="form-control" id="company_website" name="company_website" placeholder="example.com" value="{{ old('company_website') }}"> </div>
                        </div>

                        <div class="form-group">
                            <label for="main_market">Main Market</label>
                            <input type="text" class="form-control" id="main_market" name="main_market" placeholder="Los Angeles, California" value="{{ old('main_market') }}">
                        </div>

                        <div class="form-group">
                            <label for="phone_number">Phone Number</label>
                            <input type="tel" class="form-control" id="phone_number" name="phone_number" placeholder="Phone Number" value="{{ old('phone_number') }}">
                        </div>

                        <!-- Custom File Upload -->
                        <label for="customFile2">Upload Avatar Image</label>
                        <div class="input-group mb-3">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile2" name="avatar" value="{{ old('avatar') }}">
                                <label class="custom-file-label" for="customFile2">Choose file...</label>
                            </div>
                        </div>
                        <!-- / Custom File Upload -->

                        <div class="form-row">
                            {{--<div class="form-group col-md-6">--}}
                                {{--<label for="feInputCity">City</label>--}}
                                {{--<input type="text" class="form-control" id="feInputCity">--}}
                            {{--</div>--}}
                            {{--<div class="form-group col-md-4">--}}
                                {{--<label for="feInputState">State</label>--}}
                                {{--<select id="feInputState" class="form-control">--}}
                                    {{--<option selected="">Choose...</option>--}}
                                    {{--<option>...</option>--}}
                                {{--</select>--}}
                            {{--</div>--}}

                            <div class="form-group col-md-12">
                                <div class="custom-control custom-checkbox mb-1">
                                    <input type="checkbox" class="custom-control-input" name="agreed" id="formsAgreeField" required="" value="on">
                                    <label class="custom-control-label" for="formsAgreeField">I agree with your
                                        <a href="#">User Terms & Privacy Policy</a>.</label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Create New User</button>

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
