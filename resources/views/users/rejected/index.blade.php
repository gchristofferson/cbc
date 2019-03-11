@extends ('layouts.admin-crud')

@section('title')
    <title>Users - Rejected | Commercial Broker Connection</title>
@endsection

@section('content')
    @if($back_url = Session::get('prev_url'))
        <a href="{{ $back_url }}" id="save" class="btn btn-secondary btn-sm mt-4 save">
            <i class="material-icons">keyboard_backspace</i> Back</a>
    @else
        <a href="{{ URL::previous() }}" id="save" class="btn btn-secondary btn-sm mt-4 save">
            <i class="material-icons">keyboard_backspace</i> Back</a>
    @endif
    <h2 class="h4 mt-3 ml-1">Rejected Users</h2>
    <div class="card-body p-0 pb-3 text-center">
        <table class="table mb-0">
            <thead class="bg-light">
            <tr>
                <th scope="col" class="border-0">#</th>
                <th scope="col" class="border-0">First Name</th>
                <th scope="col" class="border-0">Last Name</th>
                <th scope="col" class="border-0">Email</th>
                <th scope="col" class="border-0">Real Estate License</th>
                <th scope="col" class="border-0">Approve/Delete</th>

            </tr>
            </thead>
            <tbody>

            @foreach($users as $rejected)
                @if($rejected->rejected == 'on')
                    <tr>
                        <td>{{ $rejected->id }}</td>
                        <td>{{ $rejected->first_name }}</td>
                        <td>{{ $rejected->last_name }}</td>
                        <td><a href="mailto:{{ $rejected->email }}">{{ $rejected->email }}</a></td>
                        <td>{{ $rejected->license }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <form method="POST" action="/users/{{ $rejected->id }}">
                                    @method('PATCH')
                                    @csrf
                                    <input type='hidden' value='' name='approved-btn'>
                                    <input type='hidden' value='on' name='approved'>
                                    <button type="submit" class="btn btn-white">
                                        <span class="text-success">
                                            <i class="material-icons">check</i>
                                        </span> Approve
                                    </button>
                                </form>
                                <form method="POST" action="/users/{{ $rejected->id }}">
                                    @method('DELETE')
                                    @csrf
                                    <input type='hidden' value='' name='delete-btn'>
                                    <button type="submit" class="btn btn-white" onclick="return confirm('Are you sure you want to delete this user?');">
                                        <span class="text-danger">
                                            <i class="material-icons">delete</i>
                                        </span> Delete
                                    </button>
                                </form>
                                <button type="button" onclick='window.location.replace("/users/{{ $rejected->id }}/edit")' class="btn btn-white">
                                    <span class="text-light">
                                        <i class="material-icons">more_vert</i>
                                    </span> Edit
                                </button>
                            </div>
                        </td>
                    @endif
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="col text-center view-report">
        {{--<button type="submit" class="btn btn-white">Export All Users</button>--}}
        <span class="float-right">{{ $users->links() }}</span>
    </div>
@endsection

