@extends ('layouts.admin-crud')

@section('title')
    <title>States | Commercial Broker Connection</title>
@endsection

@section('content')
    @if($back_url = Session::get('prev_url'))
        <a href="{{ $back_url }}" id="save" class="btn btn-secondary btn-sm mt-4 save">
    @else
        <a href="{{ URL::previous() }}" id="save" class="btn btn-secondary btn-sm mt-4 save">
    @endif
        <i class="material-icons">keyboard_backspace</i> Back</a>
    <h2 class="h4 mt-3 ml-1">All States</h2>
    <div class="card-body p-0 pb-3 text-center">

        <table class="table mb-0">
            <thead class="bg-light">
            <tr>
                <th scope="col" class="border-0">#</th>
                <th scope="col" class="border-0">State</th>
                <th scope="col" class="border-0">Delete/Edit</th>
            </tr>
            </thead>
            <tbody>

            @foreach($states as $state)
                <tr>
                    <td>{{ $state->id }}</td>
                    <td><a href="/states/{{ $state->id }}">{{ $state->state }}</a></td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <form method="POST" action="/states/{{ $state->id }}">
                                @method('DELETE')
                                @csrf
                                <input type='hidden' value='' name='delete-btn'>
                                <button type="submit" class="btn btn-white" onclick="return confirm('This may have unintended effects on associated cities & inquiries. Are you sure you want to delete this item?');">
                                    <span class="text-danger">
                                        <i class="material-icons">delete</i>
                                    </span> Delete
                                </button>
                            </form>

                            <button type="button" onclick='window.location.replace("/states/{{ $state->id }}")' class="btn btn-white">
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
                                    <input type="number" class="form-control" id="price" name="price" placeholder="$" required="" value="{{ old('price') }}">
                                </div>
                                <div class="form-group col">
                                    <label for="price">Stripe id</label>
                                    <input type="string" class="form-control" id="stripe" name="stripe_sub_id"
                                          required value="">
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
@section('footer-btn')
    <div class="col text-center view-report">
        <span class="float-right">{{ $states->links() }}</span>
    </div>
@endsection
