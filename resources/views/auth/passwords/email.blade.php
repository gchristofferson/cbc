@extends('layouts.login')
@section('title')
    <title>Reset Password | Real Estate Connections</title>
@endsection

@section('content')
<div class="container center-vh forgot">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        <img class="auth-form__logo d-table mx-auto mb-3" src="{{ asset('img/real-estate-connections-logo.png') }}" alt="Real Estate Connections Logo">
                        <!--<h1 class="h4 text-center">Real Estate Connections</h1>-->
                        <h2 class="h5 auth-form__title text-center mb-4">Reset Password</h2>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

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

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
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
