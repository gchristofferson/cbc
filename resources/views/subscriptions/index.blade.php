@extends ('layouts.app')

@section('title')
    <title>Markets | Commercial Broker Connection</title>
@endsection

@section('content')
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
                            <th>Yearly price</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($states as $state)
                            <tr>
                                <td>{{$state->state}}</td>
                                <td>{{$state->price}}</td>
                                <td>
                                    @if(!$user->isSubscribed($state->id))
                                        <span id="add-{{$state->id}}" class="btn btn-dark"
                                              onclick="addState({{$state->id}}, '{{$state->state}}', {{$state->price}})">Add</span>
                                        <span id="remove-{{$state->id}}" class="btn btn-outline-danger hidden"
                                              onclick="removeState({{$state->id}}, {{$state->price}})">Remove</span>

                                    @else
                                        <span id="add-{{$state->id}}" class="btn btn-sm btn-outline-success disabled">Subscribed</span>
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
            <div class="col-md-6 card card-small user-activity mb-4 mt-4">
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
                <div class="card-footer p-2">
                    <form class="form-group" action="/subscriptions" method="post" enctype="multipart/form-data">
                        @csrf
                        <input id="stateIds" name="stateIds" type="hidden" value="[]">
                        <div class="input-group mb-3 hidden" id="promoCode">
                            <input id="coupon" type="text" name="coupon" class="form-control"
                                   placeholder="Enter a coupon"
                                   aria-label="Enter a coupon" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button onclick="disablePromoCode()" class="btn btn-outline-danger" type="button">
                                    Remove
                                </button>
                            </div>
                        </div>
                        <span id="add-coupon" onclick="enablePromoCode()" class="btn  btn-outline-secondary">
                            Add promo code
                        </span>
                        <button type="submit" id="subscribe" disabled class="btn btn-success disabled">Subscribe
                        </button>
                    </form>
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
        var isPromoCodeEnabled = false;
        var subtotal = 0;
        var selectedMarkets = [];

        function getStateId(id) {
            return 'state-' + id;
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
            subTotalEl.innerHTML = subtotal.toPrecision(4);
            subscribeButton.classList.remove("disabled");
            subscribeButton.removeAttribute("disabled");
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
            }
            document.getElementById(getStateId(id)).remove();
            toggleAddRemoveForStateList(id);
            removeStateIdFromForm(id);
            selectedMarkets.splice(marketIndex, 1);
        }

        function enablePromoCode() {
            if (isPromoCodeEnabled) return;
            promoCodeContainer.classList.remove("hidden");
            addPromoCodeButton.classList.add("hidden");
            promoCodeInputField.setAttribute("value", "");
            isPromoCodeEnabled = true;
        }

        function disablePromoCode() {
            if (!isPromoCodeEnabled) return;
            promoCodeContainer.classList.add("hidden");
            addPromoCodeButton.classList.remove("hidden");
            promoCodeInputField.setAttribute("value", "");
            isPromoCodeEnabled = false;
        }
    </script>
@endsection
@section('footer-btn')

@endsection
