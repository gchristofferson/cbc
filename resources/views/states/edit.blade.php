@extends('layouts.crud')

@section('title')
    <title>Edit State | Commercial Broker Connections</title>
@endsection

@section('content')
    <div class="card card-small">
        <div class="card-header border-bottom">
            <h6 class="m-0">Edit State</h6>
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
                                    <label for="price">Price Per Year</label>
                                    <input type="number" class="form-control" id="price" name="price" placeholder="$" required="" value="{{ $state->price }}">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Update State</button>

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
                        <form class="mt-3" method="POST" action="/states/{{ $state->id }}">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger" onclick="return confirm('This may have unintended effects on associated cities & inquiries. Are you sure you want to delete this item?');">Delete State</button>
                        </form>
                    </div>
                </div>
            </li>
        </ul>
    </div>
@endsection
