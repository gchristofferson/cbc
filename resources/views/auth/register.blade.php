@extends('layouts.login')

@section('content')
    <div class="container center-vh register">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">

                    <div class="card-body">
                        <img class="auth-form__logo d-table mx-auto mt-0 mb-3"
                             src="{{ asset('img/real-estate-connections-logo.png') }}"
                             alt="Real Estate Connections Logo">
                        <p class="text-justify font-weight-normal ml-lg-5 mr-lg-5">A simple email chain to connect local
                            or statewide commercial
                            real estate investment brokers and agents together. Send and receive email blasts
                            to let other brokers know property availability or your current property needs. This
                            is a 24/7 marketing program and the easiest, yet most influential tool for commercial
                            brokers.
                            All email inquiries are saved so you can search through thousands of inquiries from other
                            local brokers.
                        </p>
                        <h2 class="h5 auth-form__title text-center mb-4">Register</h2>
                        <form method="POST" action="/create-user" id="payment-form">
                            @csrf
                            <div class="step" id="first-step">

                                <div class="form-group row">
                                    <label for="first_name"
                                           class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>

                                    <div class="col-md-6">
                                        <input id="first_name" type="text"
                                               class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}"
                                               name="first_name" value="{{ old('first_name') }}" required autofocus>

                                        @if ($errors->has('first_name'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="last_name"
                                           class="col-md-4 col-form-label text-md-right">{{ __('Last Name') }}</label>

                                    <div class="col-md-6">
                                        <input id="last_name" type="text"
                                               class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}"
                                               name="last_name" value="{{ old('last_name') }}" required autofocus>

                                        @if ($errors->has('last_name'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="license"
                                           class="col-md-4 col-form-label text-md-right">{{ __('Real Estate License #') }}</label>

                                    <div class="col-md-6">
                                        <input id="license" type="text"
                                               class="form-control{{ $errors->has('license') ? ' is-invalid' : '' }}"
                                               name="license" value="{{ old('license') }}" required autofocus>

                                        @if ($errors->has('license'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('license') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email"
                                           class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email"
                                               class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                               name="email" value="{{ old('email') }}" required>

                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password"
                                           class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password"
                                               class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                               name="password" required>

                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password-confirm"
                                           class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control"
                                               name="password_confirmation" required>
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <span onclick="goto('second')" id="next" class="btn btn-primary">
                                            Next
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="step hidden" id="second-step">
                                Enter your card details to continue.
                                <hr>
                                <div id="card-element">
                                    <!-- A Stripe Element will be inserted here. -->
                                </div>
                                <div class="form-group row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3 d-table">
                                            <div class="custom-control custom-checkbox mb-1">
                                                <input type="checkbox" class="custom-control-input" id="agree"
                                                       name="agree" required>
                                                <label id="agree" class="custom-control-label" for="agree">I agree with
                                                    the
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

                                <div class="mt-3">
                                    <span onclick="goto('first')" id="back" disabled class="btn btn-primary">
                                        Back
                                    </span>
                                    <button type="submit" id="subscribe" class="btn btn-success">
                                        Subscribe
                                    </button>
                                    <!-- Used to display form errors. -->
                                    <div id="card-errors" role="alert"></div>
                                    <div id="messages" role="alert"></div>
                                </div>
                                <script src="https://js.stripe.com/v3/"></script>
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
    <script>
        function goto(step) {
            [].forEach.call(document.getElementsByClassName("step"), function (el) {
                if (!el.classList.contains('hidden')) {
                    el.classList.add('hidden');
                }
            });
            document.getElementById(step + "-step").classList.remove("hidden");
        }

        // Create a Stripe client.
        var stripe = Stripe('pk_test_b8F0FiRDBw3plFvkCIh5XiXf005q74yZNT');

        // Create an instance of Elements.
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        // (Note that this demo uses a wider set of styles than the guide below.)
        var style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {style: style});

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        // Handle real-time validation errors from the card Element.
        card.addEventListener('change', function (event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission.
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            stripe.createToken(card).then(function (result) {
                if (result.error) {
                    // Inform the user if there was an error.
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    // Send the token to your server.
                    stripeTokenHandler(result.token);
                }
            });
        });

        // Submit the form with the token ID.
        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        }


    </script>
@endsection
