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
                                <div class="form-group col">
                                    <label for="price">Stripe id</label>
                                    <input type="string" class="form-control" id="stripe" name="stripe_sub_id"
                                           required value="{{ $state->stripe_sub_id }}">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                Update State
                            </button>
                        </form>
                        <form class="mt-3 mb-4" method="POST" action="/states/{{ $state->id }}">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger" onclick="return confirm('This may have unintended effects on associated cities & inquiries. Are you sure you want to delete this State?');">Delete State</button>
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
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        {{--end Cities--}}
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

@endsection
