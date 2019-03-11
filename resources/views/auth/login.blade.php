@extends('layouts.login')
@section('title')
    <title>Login | Real Estate Connections</title>
@endsection
@section('content')
    <div class="container center-vh loginForm">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">

                    <div class="card-body">
                        <img class="auth-form__logo d-table mx-auto mb-3" src="{{ asset('img/real-estate-connections-logo.png') }}" alt="Real Estate Connections Logo">
                        <p class="text-justify font-weight-normal ml-lg-5 mr-lg-5">A simple email chain to connect local or statewide commercial
                            real estate investment brokers and agents together. Send and receive email blasts
                            to let other brokers know property availability or your current property needs. This
                            is a 24/7 marketing program and the easiest, yet most influential tool for commercial brokers.
                            All email inquiries are saved so you can search through thousands of inquiries from other local brokers.
                        </p>
                        <!--<h1 class="h4 text-center">Real Estate Connections</h1>-->
                        <h2 class="h5 auth-form__title text-center mb-4">Access Your Account</h2>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

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
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="auth-form__meta d-flex mt-4 col-md-8">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                @endif
                <a class="ml-auto" href="/register">Create new account?</a>
            </div>
        </div>
    </div>



    {{--<div class="row no-gutters h-100">--}}
        {{--<!--<div class="col-lg-3 col-md-5 auth-form mx-auto my-auto">-->--}}
        {{--<!---->--}}
        {{--<!--</div>-->--}}
        {{--<div class="col-lg-3 col-md-5 auth-form mx-auto my-auto">--}}
            {{--<div class="card">--}}
                {{--<div class="card-body">--}}
                    {{--<img class="auth-form__logo d-table mx-auto mb-3" src="{{ asset('img/real-estate-connections-logo.png') }}" alt="Real Estate Connections Logo">--}}
                    {{--<p class="text-justify font-weight-normal">A simple email chain to connect local or statewide commercial--}}
                        {{--real estate investment brokers and agents together. Send and receive email blasts--}}
                        {{--to let other brokers know property availability or your current property needs. This--}}
                        {{--is a 24/7 marketing program and the easiest, yet most influential tool for commercial brokers.--}}
                        {{--All email inquiries are saved so you can search through thousands of inquiries from other local brokers.--}}
                    {{--</p>--}}
                    {{--<!--<h1 class="h4 text-center">Real Estate Connections</h1>-->--}}
                    {{--<h2 class="h5 auth-form__title text-center mb-4">Access Your Account</h2>--}}
                    {{--<form method="POST" action="{{ route('login') }}">--}}
                        {{--@csrf--}}
                        {{--<div class="form-group">--}}
                            {{--<label for="email">{{ __('E-Mail Address') }}</label>--}}
                            {{--<input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Enter email" name="email" value="{{ old('email') }}" required autofocus>--}}
                            {{--@if ($errors->has('email'))--}}
                                {{--<span class="invalid-feedback" role="alert">--}}
                                        {{--<strong>{{ $errors->first('email') }}</strong>--}}
                                    {{--</span>--}}
                            {{--@endif--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<label for="password">{{ __('Password') }}</label>--}}
                            {{--<input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Password" name="password" required>--}}

                            {{--@if ($errors->has('password'))--}}
                                {{--<span class="invalid-feedback" role="alert">--}}
                                        {{--<strong>{{ $errors->first('password') }}</strong>--}}
                                    {{--</span>--}}
                            {{--@endif--}}
                        {{--</div>--}}
                        {{--<div class="form-group mb-3 d-table mx-auto">--}}
                            {{--<div class="custom-control custom-checkbox mb-1">--}}
                                {{--<input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>--}}
                                {{--<label class="custom-control-label" for="remember">{{ __('Remember Me') }}</label>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<button type="submit" class="btn btn-pill btn-accent d-table mx-auto">Access Account</button>--}}
                    {{--</form>--}}



                    {{--<form method="POST" action="{{ route('login') }}">--}}
                        {{--@csrf--}}

                        {{--<div class="form-group row">--}}
                            {{--<label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>--}}

                            {{--<div class="col-md-6">--}}
                                {{--<input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>--}}

                                {{--@if ($errors->has('email'))--}}
                                    {{--<span class="invalid-feedback" role="alert">--}}
                                        {{--<strong>{{ $errors->first('email') }}</strong>--}}
                                    {{--</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="form-group row">--}}
                            {{--<label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>--}}

                            {{--<div class="col-md-6">--}}
                                {{--<input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>--}}

                                {{--@if ($errors->has('password'))--}}
                                    {{--<span class="invalid-feedback" role="alert">--}}
                                        {{--<strong>{{ $errors->first('password') }}</strong>--}}
                                    {{--</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="form-group row">--}}
                            {{--<div class="col-md-6 offset-md-4">--}}
                                {{--<div class="form-check">--}}
                                    {{--<input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>--}}

                                    {{--<label class="form-check-label" for="remember">--}}
                                        {{--{{ __('Remember Me') }}--}}
                                    {{--</label>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="form-group row mb-0">--}}
                            {{--<div class="col-md-8 offset-md-4">--}}
                                {{--<button type="submit" class="btn btn-primary">--}}
                                    {{--{{ __('Login') }}--}}
                                {{--</button>--}}

                                {{--@if (Route::has('password.request'))--}}
                                    {{--<a class="btn btn-link" href="{{ route('password.request') }}">--}}
                                        {{--{{ __('Forgot Your Password?') }}--}}
                                    {{--</a>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</form>--}}
                {{--</div>--}}
                {{--<div class="card-footer border-top">--}}
                    {{--<ul class="auth-form__social-icons d-table mx-auto">--}}
                        {{--<li>--}}
                            {{--<a href="#">--}}
                                {{--<i class="fab fa-facebook-f"></i>--}}
                            {{--</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="#">--}}
                                {{--<i class="fab fa-twitter"></i>--}}
                            {{--</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="#">--}}
                                {{--<i class="fab fa-instagram"></i>--}}
                            {{--</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="#">--}}
                                {{--<i class="fab fa-google-plus-g"></i>--}}
                            {{--</a>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="auth-form__meta d-flex mt-4">--}}
                {{--@if (Route::has('password.request'))--}}
                    {{--<a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>--}}
                {{--@endif--}}
                {{--<a class="ml-auto" href="/register">Create new account?</a>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
@endsection
