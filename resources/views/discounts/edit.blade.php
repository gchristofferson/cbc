@extends('layouts.admin-crud')

@section('title')
    <title>Edit City | Commercial Broker Connections</title>
@endsection

@section('content')
    @if($back_url = Session::get('prev_url'))
        <a href="{{ $back_url }}" id="save" class="btn btn-secondary btn-sm mt-4 save">
    @else
        <a href="{{ URL::previous() }}" id="save" class="btn btn-secondary btn-sm mt-4 save">
    @endif
        <i class="material-icons">keyboard_backspace</i> Back</a>
    <div class="card card-small mt-4 mb-4">
        <div class="card-header border-bottom">
            <h6 class="m-0">Edit Discount</h6>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item p-3">
                <div class="row">
                    <div class="col">
                        <form method="POST" action="/discounts/{{$discount->id}}">
                            @csrf
                            @method("PATCH")

                            <input id="discount_state_id" type="hidden" value="{{ $discount_state->id }}" name="discount_state_id">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="discount">Discount</label>
                                    <input type="number" class="form-control" id="discount" name="discount" placeholder="$" value="{{ $discount->discount }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="discount_desc">Discount Text</label>
                                    <input type="text" class="form-control" id="discount_desc" name="discount_desc" placeholder="i.e. 30 Days Free!" value="{{ $discount->discount_desc }}">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="days_to_expire_discount">Days to Expire Discount</label>
                                    <input type="number" class="form-control" id="days_to_expire_discount" name="days_to_expire_discount" placeholder="Enter number of days until discount expires" value="{{ $discount->days_to_expire_discount }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="discount_limit">User Discount Limit</label>
                                    <input type="number" class="form-control" id="discount_limit" name="discount_limit" placeholder="Enter limit per user" value="{{ $discount->discount_limit }}" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="promo_code">Promo Code</label>
                                    <input type="text" class="form-control" id="promo_code" name="promo_code" placeholder="Promo Code" value="{{ $discount->promo_code }}" required>
                                </div>
                                @if($discount->override_subscription_expire == 'on')
                                    <?php $override = "checked"; ?>
                                @else
                                    <?php $override = ""; ?>
                                @endif
                                <div class="form-group col-md-6">
                                    <label for="adminToggle" class="col col-form-label"> Expire Subscription When Discount Expires?
                                        <small class="form-text text-muted">Turn expire date override on/off for this State subscription </small>
                                    </label>
                                    <div class="col d-flex">
                                        <div class="custom-control custom-toggle ml-auto my-auto">
                                            <input id="adminToggleHidden" type="hidden" value="off" name="override_subscription_expire">
                                            <input type="checkbox" id="adminToggle" value="on" name="override_subscription_expire" class="custom-control-input" <?=$override;?>>
                                            <label class="custom-control-label" id="adminToggleLabel" for="adminToggle"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Discount</button>

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
                        <form class="mt-3" method="POST" action="/discounts/{{ $discount->id }}">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger" onclick="return confirm('This will delete associated user subscriptions. Are you sure you want to delete this discount?');">Delete Discount</button>
                        </form>
                    </div>
                </div>
            </li>
        </ul>
    </div>
@endsection
