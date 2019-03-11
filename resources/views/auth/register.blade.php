@extends('layouts.login')

@section('content')
<div class="container center-vh register">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
                    <img class="auth-form__logo d-table mx-auto mt-0 mb-3" src="{{ asset('img/real-estate-connections-logo.png') }}" alt="Real Estate Connections Logo">
                    <p class="text-justify font-weight-normal ml-lg-5 mr-lg-5">A simple email chain to connect local or statewide commercial
                        real estate investment brokers and agents together. Send and receive email blasts
                        to let other brokers know property availability or your current property needs. This
                        is a 24/7 marketing program and the easiest, yet most influential tool for commercial brokers.
                        All email inquiries are saved so you can search through thousands of inquiries from other local brokers.
                    </p>
                    <h2 class="h5 auth-form__title text-center mb-4">Register</h2>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="first_name" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ old('first_name') }}" required autofocus>

                                @if ($errors->has('first_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="last_name" class="col-md-4 col-form-label text-md-right">{{ __('Last Name') }}</label>

                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name') }}" required autofocus>

                                @if ($errors->has('last_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="license" class="col-md-4 col-form-label text-md-right">{{ __('Real Estate License #') }}</label>

                            <div class="col-md-6">
                                <input id="license" type="text" class="form-control{{ $errors->has('license') ? ' is-invalid' : '' }}" name="license" value="{{ old('license') }}" required autofocus>

                                @if ($errors->has('license'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('license') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-group mb-3 d-table">
                                    <div class="custom-control custom-checkbox mb-1">
                                        <input type="checkbox" class="custom-control-input" id="agree" name="agree" required>
                                        <label id="agree" class="custom-control-label" for="agree">I agree with the
                                            <a href="#">Terms & Conditions</a>.</label>
                                    </div>
                                    @if ($errors->has('agree'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('agree') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="auth-form__meta d-flex mt-4 col-md-8">
            <a href="{{ route('login') }}">{{ __('Return to Login') }}</a>
            <a class="ml-auto" href="/register">Create new account?</a>
        </div>
    </div>
</div>
@endsection
