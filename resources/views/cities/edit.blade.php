@extends('layouts.crud')

@section('title')
    <title>Edit City | Commercial Broker Connections</title>
@endsection

@section('content')
    <div class="card card-small">
        <div class="card-header border-bottom">
            <h6 class="m-0">Edit City</h6>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item p-3">
                <div class="row">
                    <div class="col">
                        <form method="POST" action="/cities/{{ $city->id }}">
                            @csrf
                            @method('PATCH')

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="city">City</label>
                                    <input type="text" class="form-control" id="city" name="city" placeholder="City" required="" value="{{ $city->city }}">
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="feInputState">State</label>
                                <select id="feInputState" class="form-control" name="state_id" required>
                                    @foreach($states as $state)
                                        @if($state->state == $city_state->state)
                                            <option selected="" value="{{ $state->id }}">{{ $state->state }}</option>
                                        @else
                                            <option value="{{ $state->id }}">{{ $state->state }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Update City</button>

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
                        <form class="mt-3" method="POST" action="/cities/{{ $city->id }}">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger" onclick="return confirm('This may have unintended effects on associated inquiries. Are you sure you want to delete this city?');">Delete City</button>
                        </form>
                    </div>
                </div>
            </li>
        </ul>
    </div>
@endsection
