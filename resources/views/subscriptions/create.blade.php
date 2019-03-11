@extends('layouts.crud')

@section('title')
    <title>Subscribe | Commercial Broker Connections</title>
@endsection

@section('content')
    <div class="card card-small mb-3">
        <div class="card-header border-bottom">
            <h6 class="m-0">Subscribe</h6>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item p-3">
                <div class="row">
                    <div class="col form-row">
                        <form method="POST" class="col-md-2">
                            @csrf


                                {{--<div class="form-group col-md-6">--}}
                                    {{--<label for="state">State</label>--}}
                                    {{--<input type="text" class="form-control" id="state" name="state" placeholder="State" required="" value="{{ old('state') }}">--}}
                                {{--</div>--}}

                                <div class="form-group add-select">
                                    <label for="state">State</label>
                                    <select data-city="js-input-city" id="state" name="state" class="form-control col-10 city">
                                        <option selected>Choose State...</option>
                                        @if(! session('cart_states'))
                                            @php($cart_states = [])
                                        @else
                                            @php($cart_states = session('cart_states'))
                                        @endif

                                        {{--@if(isset($cart_state_ids))--}}
                                            {{--@foreach($states as $state)--}}
                                                {{--@if(!in_array($state->id, $subscription_state_ids) && !in_array($state->id, $cart_state_ids))--}}
                                                    {{--<option value="{{ $state->id }}">{{ $state->state }}</option>--}}
                                                {{--@endif--}}
                                            {{--@endforeach--}}
                                        {{--@else--}}
                                            @foreach($states as $state)
                                                {{--@if(!in_array($state->id, $subscription_state_ids))--}}
                                                    <option value="{{ $state->id }}">{{ $state->state }}</option>
                                                {{--@endif--}}
                                            @endforeach
                                        {{--@endif--}}

                                    </select>


                                </div>
                            <div class="form-group">
                                <label for="promo_code">Promo Code</label>
                                <input type="text" class="form-control" id="promo_code" name="promo_code" placeholder="Enter Code" value="">
                            </div>




                            <button type="submit" class="btn btn-primary">Add to Cart</button>

                        </form>
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




                        <div class="form-group col-md-10">
                            <label for="price">Your Cart</label>
                            @if (session('cart_datas') != [])

                                @php($cart_total = 0)
                                <table class="table mb-2 table-responsive-md">
                                    <thead class="bg-light">
                                    <tr>
                                        <th scope="col" class="border-0">State</th>
                                        <th scope="col" class="border-0">Discount</th>
                                        <th scope="col" class="border-0">Subscription Starts</th>
                                        <th scope="col" class="border-0">Subscription Expires</th>
                                        <th scope="col" class="border-0">Amount</th>
                                        <th scope="col" class="border-0">Remove from Cart</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach(session('cart_datas') as $cart_data)
                                        @php($cart_total += $cart_data['this_price'])

                                        <tr>
                                            <td>
                                                {{ $cart_data['cart_state']->state }}
                                            </td>
                                            <td>
                                                <span class="text-muted">- ${{ $cart_data['this_discount_amount'] }}
                                                    @if($cart_data['this_discount_desc'] != '')
                                                        <small>( {{ $cart_data['this_discount_desc']}} )</small>
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                {{ $cart_data['this_start_date'] }}
                                            </td>
                                            <td>
                                                {{ $cart_data['this_expire_date'] }}
                                            </td>
                                            <td>
                                                <span class="text-muted">${{ $cart_data['this_price'] }}</span>
                                            </td>
                                            <td class="mx-auto">
                                                <div>
                                                    <form method="POST">
                                                        @csrf
                                                        <input type='hidden' value='{{ $loop->index }}' name='delete_btn'>
                                                        <button type="submit" class="btn btn-white">
                                                                <span class="text-danger">
                                                                    <i class="material-icons">delete</i>
                                                                </span> Remove
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @php($cart_tax = $cart_total * 0.085)
                                    @php($cart_total += $cart_tax)

                                    <tr>
                                        <td>
                                            <span class="text-muted"></span>
                                        </td>
                                        <td>
                                            <span class="text-muted"></span>
                                        </td>
                                        <td>
                                            <span class="text-muted"></span>
                                        </td>
                                        <td class="pl-5">
                                            Tax
                                        </td>
                                        <td>
                                            <span class="text-muted">${{ $cart_tax }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted"></span>
                                        </td>
                                    </tr>
                                    <tr class="no-border">
                                        <td>
                                            <span class="text-muted"></span>
                                        </td>
                                        <td>
                                            <span class="text-muted"></span>
                                        </td>
                                        <td>
                                            <span class="text-muted"></span>
                                        </td>
                                        <td class="pl-5">
                                            Total (USD) / Year
                                        </td>
                                        <td>
                                            <strong>${{ $cart_total }}</strong>
                                        </td>
                                        <td>
                                            <span class="text-muted"></span>
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                            <form action="{!! URL::to('paypal') !!}" method="POST">
                                @csrf
                                <input type="hidden" id="amount" name="amount" value="{{ $cart_total }}">
                                <button type="submit" class="btn btn-secondary float-right">Checkout</button>
                            </form>


                            @else
                                <table class="table mb-2 table-responsive-md">
                                    <thead class="bg-light">
                                    <tr>
                                        <th scope="col" class="border-0">State</th>
                                        <th scope="col" class="border-0">Discount</th>
                                        <th scope="col" class="border-0">Subscription Starts</th>
                                        <th scope="col" class="border-0">Subscription Expires</th>
                                        <th scope="col" class="border-0">Amount</th>
                                        <th scope="col" class="border-0">Remove from Cart</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        is Empty.
                                    </tbody>
                                </table>
                            @endif


                        </div>



                    </div>
                </div>
            </li>
        </ul>
    </div>

    <div class="card card-small">
        <div class="card-header border-bottom">
            <h6 class="m-0">Your Current Subscriptions</h6>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item p-3">
                <div class="row">
                    <div class="col">

                        <div class="form-row">

                            {{--<div class="form-group col-md-6">--}}
                            {{--<label for="state">State</label>--}}
                            {{--<input type="text" class="form-control" id="state" name="state" placeholder="State" required="" value="{{ old('state') }}">--}}
                            {{--</div>--}}

                            <div class="form-group col">

                                    @php($total = 0)
                                    <table class="table mb-2 table-responsive-md">
                                        <thead class="bg-light">
                                        <tr>
                                            <th scope="col" class="border-0">State</th>
                                            <th scope="col" class="border-0">Discount</th>
                                            <th scope="col" class="border-0">Subscription Started</th>
                                            <th scope="col" class="border-0">Subscription Expires</th>
                                            <th scope="col" class="border-0">Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($subscriptions as $subscription)
                                            @php($total += $subscription->state->price)
                                            @if($subscription->has_discount == true && $subscription->used == true)
                                                @php($total = $total - $subscription->discount->discount)
                                                @php($price = $subscription->state->price - $subscription->discount->discount)
                                            @else
                                                @php($price = $subscription->state->price)
                                            @endif


                                            <tr>
                                                <td>
                                                    {{ $subscription->state->state }}
                                                </td>
                                                <td>
                                                    @if($subscription->has_discount = true)
                                                        <span class="text-muted">- ${{ number_format($subscription->discount['discount'], 2) }}
                                                            @if($subscription->discount['discount_desc'])
                                                                <small data-toggle="tooltip" data-placement="top" title="{{ $subscription->discount['discount_desc'] }}" style="cursor: default;">
                                                                    ( {{ str_limit($subscription->discount['discount_desc'], $limit = 30, $end = '...') }} )
                                                                </small>
                                                            @endif
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $subscription->subscription_start_date->format('m-d-Y') }}
                                                </td>
                                                <td>
                                                    {{ $subscription->subscription_expire_date->format('m-d-Y') }}
                                                </td>
                                                <td>
                                                    <span class="text-muted">${{ number_format($price, 2) }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @php($tax = $total * 0.085)
                                        @php($total += $tax)

                                            <tr>
                                                <td>
                                                    <span class="text-muted"></span>
                                                </td>
                                                <td>
                                                    <span class="text-muted"></span>
                                                </td>
                                                <td>
                                                    <span class="text-muted"></span>
                                                </td>
                                                <td class="pl-5">
                                                    Tax
                                                </td>
                                                <td>
                                                    <span class="text-muted">${{ number_format($tax, 2) }}</span>
                                                </td>
                                            </tr>
                                            <tr class="no-border">
                                                <td>
                                                    <span class="text-muted"></span>
                                                </td>
                                                <td>
                                                    <span class="text-muted"></span>
                                                </td>
                                                <td>
                                                    <span class="text-muted"></span>
                                                </td>
                                                <td class="pl-5">
                                                    Total (USD) / Year
                                                </td>
                                                <td>
                                                    <strong>${{ number_format($total,2) }}</strong>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>


                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
@endsection
