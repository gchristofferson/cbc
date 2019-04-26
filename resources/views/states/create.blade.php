@extends('layouts.crud')

@section('title')
    <title>Create State | Commercial Broker Connections</title>
@endsection

@section('content')
    <div class="card card-small">
        <div class="card-header border-bottom">
            <h6 class="m-0">Create State</h6>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item p-3">
                <div class="row">
                    <div class="col">
                        <form method="POST" action="/states">
                            @csrf

                            <div class="form-row">
                                <div class="form-group col">
                                    <label for="state">State</label>
                                    <input type="text" class="form-control" id="state" name="state" placeholder="State" required="" value="{{ old('state') }}">
                                </div>
                                <div class="form-group col">
                                    <label for="price">Price Per Year</label>
                                    <input type="number" class="form-control" step="0.01" id="price" name="price" placeholder="$" required="" value="{{ old('price') }}">
                                </div>
                                <div class="form-group col">
                                    <label for="price">Stripe id</label>
                                    <input type="string" class="form-control" id="stripe" name="stripe_plan_id"
                                           placeholder="$" required="" value="">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Create New State</button>

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
