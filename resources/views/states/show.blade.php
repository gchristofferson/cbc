@extends('layouts.admin-crud')

@section('title')
    <title>State Details | Commercial Broker Connections</title>
@endsection

@section('content')
    @if($back_url = Session::get('prev_url'))
        <a href="{{ $back_url }}" id="save" class="btn btn-secondary btn-sm mt-4 save">
    @else
        <a href="{{ URL::previous() }}" id="save" class="btn btn-secondary btn-sm mt-4 save">
    @endif
        <i class="material-icons">keyboard_backspace</i> Back</a>
    <div class="card card-small mb-4 mt-4">
        <div class="card-header border-bottom">
            <h6 class="m-0">State Details</h6>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item p-3">
                <div class="row">
                    <div class="col">
                        <form method="POST" action="/states/{{ $state->id }}">
                            @csrf
                            @method('PATCH')
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="state">State</label>
                                    <input type="text" class="form-control" id="state" name="state" placeholder="State" required="" value="{{ $state->state }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="first_name">Price Per Year</label>
                                    <input type="number" class="form-control" id="price" name="price" placeholder="$" required="" value="{{ $state->price }}">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                Update State
                            </button>
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
                        <form class="mt-3 mb-4" method="POST" action="/states/{{ $state->id }}">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger" onclick="return confirm('This may have unintended effects on associated cities & inquiries. Are you sure you want to delete this State?');">Delete State</button>
                        </form>


                        @if($state->cities->count())
                            <div class="col-md-12 mb-3">
                                <strong class="text-muted d-block mb-2">Cities</strong>
                                <table class="table mb-0">
                                    <thead class="bg-light">
                                    <tr>
                                        <th scope="col" class="border-0">#</th>
                                        <th scope="col" class="border-0">City</th>
                                        <th scope="col" class="border-0">Delete/Edit</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($state->cities as $city)
                                            <tr>
                                                <td>{{ $city->id }}</td>
                                                <td>{{ $city->city }}</td>
                                                <td>
                                                    <div class="btn-group btn-group-md">
                                                        <form method="POST" action="/cities/{{ $city->id }}">
                                                            @method('DELETE')
                                                            @csrf
                                                            <input type='hidden' value='' name='delete-btn'>
                                                            <button type="submit" class="btn btn-white" onclick="return confirm('This may have unintended effects on associated inquiries. Are you sure you want to delete this city?');">
                                                                <span class="text-danger">
                                                                    <i class="material-icons">delete</i>
                                                                </span> Delete
                                                            </button>
                                                        </form>

                                                        <button type="button" onclick='window.location.replace("/cities/{{ $city->id }}/edit")' class="btn btn-white">
                                                            <span class="text-light">
                                                                <i class="material-icons">more_vert</i>
                                                            </span> Edit
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        {{--end Cities--}}
                        {{--Discounts--}}
                        @if($state->discounts->count())
                            <div class="col-md-12 mb-3">
                                <strong class="text-muted d-block mb-2">Discounts</strong>
                                <table class="table mb-0 table-responsive">
                                    <thead class="bg-light">
                                    <tr>
                                        <th scope="col" class="border-0">#</th>
                                        <th scope="col" class="border-0">Promo Code</th>
                                        <th scope="col" class="border-0">Discount Amount</th>
                                        <th scope="col" class="border-0">Discount Text</th>
                                        <th scope="col" class="border-0">Days to Expire Discount</th>
                                        <th scope="col" class="border-0">User Discount Limit</th>
                                        <th scope="col" class="border-0">Override Subscription Expire</th>
                                        <th scope="col" class="border-0">Delete/Edit</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($state->discounts as $discount)
                                        <tr>
                                            <td>{{ $discount->id }}</td>
                                            <td>{{ $discount->promo_code }}</td>
                                            <td>${{ $discount->discount }}</td>
                                            <td>{{ $discount->discount_desc }}</td>
                                            <td>{{ $discount->days_to_expire_discount }}</td>
                                            <td>{{ $discount->discount_limit }}</td>
                                            <td>{{ $discount->override_subscription_expire }}</td>
                                            <td>
                                                <div class="btn-group btn-group-md">
                                                    <form method="POST" action="/discounts/{{ $discount->id }}">
                                                        @method('DELETE')
                                                        @csrf
                                                        <input type='hidden' value='' name='delete-btn'>
                                                        <button type="submit" class="btn btn-white" onclick="return confirm('This will delete associated subscriptions. Are you sure you want to delete this discount?');">
                                                                <span class="text-danger">
                                                                    <i class="material-icons">delete</i>
                                                                </span> Delete
                                                        </button>
                                                    </form>

                                                    <button type="button" onclick='window.location.replace("/discounts/{{ $discount->id }}/edit")' class="btn btn-white">
                                                            <span class="text-light">
                                                                <i class="material-icons">more_vert</i>
                                                            </span> Edit
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </li>
        </ul>
    </div>

    {{-- create new city --}}

    <div class="card card-small mb-3">
        <div class="card-header border-bottom">
            <h6 class="m-0">Add City To This State</h6>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item p-3">
                <div class="row">
                    <div class="col">
                        <form method="POST" action="/cities">
                            @csrf

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="first_name">City</label>
                                    <input type="hidden" id="cityHidden" name="state_id"  value="{{ $state->id }}">
                                    <input type="text" class="form-control" id="city" name="city" placeholder="City" value="{{ old('city') }}">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Add City</button>

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

    {{--Create new discount--}}

    <div class="card card-small">
        <div class="card-header border-bottom">
            <h6 class="m-0">Create Discounts for State Subscription</h6>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item p-3">
                <div class="row">
                    <div class="col">
                        <form method="POST" action="/discounts">
                            @csrf

                            <input id="discount_state_id" type="hidden" value="{{ $state->id }}" name="discount_state_id">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="discount">Discount</label>
                                    <input type="number" class="form-control" id="discount" name="discount" placeholder="$" value="{{ old('discount') }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="discount_desc">Discount Text</label>
                                    <input type="text" class="form-control" id="discount_desc" name="discount_desc" placeholder="i.e. 30 Days Free!" value="{{ old('discount_desc') }}">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="days_to_expire_discount">Days to Expire Discount</label>
                                    <input type="number" class="form-control" id="days_to_expire_discount" name="days_to_expire_discount" placeholder="Enter number of days until discount expires" value="{{ old('days_to_expire_discount') }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="discount_limit">User Discount Limit</label>
                                    <input type="number" class="form-control" id="discount_limit" name="discount_limit" placeholder="Enter limit per user" value="{{ old('discount_limit') }}" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="promo_code">Promo Code</label>
                                    <input type="text" class="form-control" id="promo_code" name="promo_code" placeholder="Promo Code" value="{{ old('promo_code') }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="adminToggle" class="col col-form-label"> Expire Subscription When Discount Expires?
                                        <small class="form-text text-muted">Turn expire date override on/off for this State subscription </small>
                                    </label>
                                    <div class="col d-flex">
                                        <div class="custom-control custom-toggle ml-auto my-auto">
                                            <input id="adminToggleHidden" type="hidden" value="off" name="override_subscription_expire">
                                            <input type="checkbox" id="adminToggle" value="on" name="override_subscription_expire" class="custom-control-input">
                                            <label class="custom-control-label" id="adminToggleLabel" for="adminToggle"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Create New Discount</button>

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
