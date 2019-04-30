@extends ('layouts.app')

@section('title')
    <title>Markets | Commercial Broker Connection</title>
@endsection

@section('content')
    @php($disabled = $user->approved != 'on' ? 'disabled' : '')
    @php($disabledClass = $user->approved != 'on' ? 'disabled' : '')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5 card card-small user-activity mb-4  mt-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">Markets</h6>
                    <div class="block-handle"></div>
                </div>
                <div class="card-body p-0">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>State</th>
                            <th>Price</th>
                            <th></th>
                            <th>End date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($states as $state)
                            <tr>
                                <td>{{$state->state}}</td>
                                <td>{{number_format($state->price, 2, '.', '')}} / yr</td>
                                <td>
                                    @php ($sub = $user->isSubscribed($state->id))
                                    @if(!$sub)
                                        <span id="add-{{$state->id}}" class="btn btn-dark"
                                              onclick="addState({{$state->id}}, '{{$state->state}}', {{$state->price}})">Add</span>
                                        <span id="remove-{{$state->id}}" class="btn btn-outline-danger hidden"
                                              onclick="removeState({{$state->id}}, {{$state->price}})">Remove</span>

                                    @else
                                        @if($sub->renew)
                                            <form method="post"
                                                  action="/subscriptions/cancel"
                                                  onsubmit="return confirm('Are you sure you want to cancel your ' +
                                                          'subscription to {{$state->state}}?');">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$state->id}}"/>
                                                <button class="btn btn-sm btn-outline-warning disabled">Pause
                                                    subscription
                                                </button>
                                            </form>
                                        @else
                                            <form method="post"
                                                  action="/subscriptions/restart">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$state->id}}"/>
                                                <button class="btn btn-sm btn-outline-success disabled">Restart
                                                    subscription
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($sub)
                                        @php($date = new DateTime($sub->subscription_expire_date))
                                        {{$date->format('m/d/Y')}}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer border-top">
                    <div class="col text-center view-report">
                        {{--<span class="float-right">{{ $saved_inquiry_rows->links() }}</span>--}}
                    </div>
                </div>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-6 card mb-4 mt-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">Cart</h6>
                    <div class="block-handle"></div>
                </div>
                <div class="card-body p-0">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>State</th>
                            <th>Yearly price</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody id="cart-body">

                        </tbody>
                    </table>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Subtotal</th>
                            <th>$ <span id="subtotal">0.00</span> USD</th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="card-footer p-0">

                    <form class="form-group" id="payment-form" action="/subscriptions" method="post"
                          enctype="multipart/form-data">
                        @csrf
                        <fieldset {{$disabled}}>

                            <input id="stateIds" name="stateIds" type="hidden" value="[]">
                            <button onclick="showstripe(this)"
                                    class="btn btn-sm btn-secondary   {{!$user->is_costumer_source_valid ? 'hidden' : ''}}">
                                Update payment method
                            </button>
                            <div class="{{$user->is_costumer_source_valid ? 'hidden' : ''}}" id="card-element">
                                <!-- A Stripe Element will be inserted here. -->
                            </div>

                            <div class="mt-3">
                                <div class="input-group mb-3 hidden" id="promoCode">
                                    <div class="input-group-prepend">
                                        <button onclick="checkCoupon()" class="btn btn-outline-success" type="button">
                                            Add coupon
                                        </button>
                                    </div>

                                    <input id="coupon" type="text" name="coupon" class="form-control"
                                           placeholder="Enter a coupon"
                                           aria-label="Enter a coupon" aria-describedby="basic-addon2">

                                    <div class="input-group-append">
                                        <button onclick="disablePromoCode()" class="btn btn-outline-danger"
                                                type="button">
                                            Remove
                                        </button>
                                    </div>
                                    <button {{}} type="submit" id="subscribe-1" disabled
                                            class="btn btn-success disabled">
                                        Subscribe
                                    </button>
                                </div>
                                <span id="add-coupon" onclick="enablePromoCode()" class="btn  btn-outline-secondary">
                            Add promo code
                        </span>
                                <button type="submit" id="subscribe" disabled class="btn btn-success disabled">Subscribe
                                </button>
                                <!-- Used to display form errors. -->
                                <div id="card-errors" role="alert"></div>
                                <div id="messages" role="alert"></div>
                            </div>
                        </fieldset>
                    </form>
                    <script src="https://js.stripe.com/v3/"></script>
                </div>
            </div>
        </div>
    </div>
    <script>

        var cart = document.getElementById("cart-body");
        var stateIdEl = document.getElementById("stateIds");
        var subTotalEl = document.getElementById("subtotal");
        var promoCodeContainer = document.getElementById("promoCode");
        var promoCodeInputField = document.getElementById("coupon");
        var addPromoCodeButton = document.getElementById("add-coupon");
        var subscribeButton = document.getElementById("subscribe");
        var subscribeButton1 = document.getElementById("subscribe-1");
        var messages = document.getElementById("messages");
        var isPromoCodeEnabled = false;
        var subtotal = 0;
        var promoCode = 1;
        var selectedMarkets = [];

        function getStateId(id) {
            return 'state-' + id;
        }

        function showstripe(el) {
            document.getElementById("card-element").classList.remove("hidden");
            el.classList.add("hidden");
        }

        function checkCoupon() {
            let coupon = promoCodeInputField.value;
            if (coupon.length === 0) {
                resetPromoCode();
            } else {
                fetch('/subscriptions/checkcoupon/' + coupon)
                    .then(function (response) {
                        if (response.status === 200) {
                            response.json().then(function (response) {
                                promoCodeInputField.classList.remove("is-invalid");
                                promoCodeInputField.classList.add("is-valid");
                                messages.innerHTML = response + "% OFF Coupon Added!";
                                promoCode = (100 - response) * .01;
                                updateSubTotal(subtotal);
                                // setTimeout(function () {
                                //     messages.innerHTML = "";
                                // }, 3000)
                            });
                        } else {
                            promoCode = 1;
                            updateSubTotal(subtotal);
                            promoCodeInputField.value = "";
                            promoCodeInputField.classList.remove("is-valid");
                            promoCodeInputField.classList.add("is-invalid");
                            messages.innerHTML = "Coupon " + coupon + " is not valid";
                            setTimeout(function () {
                                messages.innerHTML = "";
                            }, 3000)
                        }
                    })
            }
        }

        function toggleAddRemoveForStateList(id) {
            let removeId = 'remove-' + id;
            let addId = 'add-' + id;
            let removeIdEl = document.getElementById(removeId);
            let addEl = document.getElementById(addId);
            removeIdEl.classList.toggle("hidden");
            addEl.classList.toggle("hidden");
        }

        function addStateIdToForm(id) {
            let jsonArray = stateIdEl.getAttribute("value");
            let parsedArray = JSON.parse(jsonArray);
            if (parsedArray.indexOf(id) >= 0) return;
            parsedArray.push(id);
            jsonArray = JSON.stringify(parsedArray);
            stateIdEl.setAttribute("value", jsonArray);
        }

        function removeStateIdFromForm(id) {
            let jsonArray = stateIdEl.getAttribute("value");
            let parsedArray = JSON.parse(jsonArray);
            if (parsedArray.indexOf(id) === -1) return;
            parsedArray.splice(id, 1);
            jsonArray = JSON.stringify(parsedArray);
            stateIdEl.setAttribute("value", jsonArray);
        }

        function updateSubTotal(newVale) {
            subTotalEl.innerHTML = (newVale * promoCode).toPrecision(4);
        }

        function addState(id, state, price) {
            if (selectedMarkets.indexOf(id) >= 0) return;
            let row = document.createElement("tr");
            let rowData = [
                document.createElement('td'),
                document.createElement('td'),
                document.createElement('td')
            ];
            row.setAttribute('id', getStateId(id));
            let attributes = id + ',' + price;
            rowData[0].innerText = state;
            rowData[1].innerText = price;
            rowData[2].innerHTML = '<span class="btn btn-outline-danger" onclick="removeState(' + attributes + ')">Remove</span>';
            row.appendChild(rowData[0]);
            row.appendChild(rowData[1]);
            row.appendChild(rowData[2]);
            cart.appendChild(row);
            subtotal += price;
            updateSubTotal(subtotal);
            subscribeButton.classList.remove("disabled");
            subscribeButton.removeAttribute("disabled");
            subscribeButton1.classList.remove("disabled");
            subscribeButton1.removeAttribute("disabled");
            toggleAddRemoveForStateList(id);
            addStateIdToForm(id);
            selectedMarkets.push(id);
        }

        function removeState(id, price) {
            let marketIndex = selectedMarkets.indexOf(id);
            if (marketIndex === -1) return;
            subtotal -= price;
            if (subtotal > 0) {
                subTotalEl.innerHTML = subtotal.toPrecision(4);
            } else {
                subTotalEl.innerHTML = "0.00";
                subscribeButton.classList.add("disabled");
                subscribeButton.setAttribute("disabled", true);
                subscribeButton1.classList.add("disabled");
                subscribeButton1.setAttribute("disabled", true);
            }
            document.getElementById(getStateId(id)).remove();
            toggleAddRemoveForStateList(id);
            removeStateIdFromForm(id);
            selectedMarkets.splice(marketIndex, 1);
        }

        function resetPromoCode() {
            promoCodeInputField.classList.remove("is-invalid");
            promoCodeInputField.classList.remove("is-valid");
            promoCodeInputField.value = "";
            messages.innerHTML = "";
            promoCode = 1;
            updateSubTotal(subtotal);
        }

        function enablePromoCode() {
            if (isPromoCodeEnabled) return;
            promoCodeContainer.classList.remove("hidden");
            addPromoCodeButton.classList.add("hidden");
            subscribeButton.classList.add("hidden");
            resetPromoCode();
            isPromoCodeEnabled = true;
        }

        function disablePromoCode() {
            promoCodeInputField.classList.remove("is-invalid");
            promoCodeInputField.classList.remove("is-valid");
            if (!isPromoCodeEnabled) return;
            promoCodeContainer.classList.add("hidden");
            addPromoCodeButton.classList.remove("hidden");
            subscribeButton.classList.remove("hidden");
            resetPromoCode();
            isPromoCodeEnabled = false;
        }


        /*STRIPE*/

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
@section('footer-btn')

@endsection
