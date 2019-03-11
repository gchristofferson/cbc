@extends ('layouts.admin-crud')

@section('title')
    <title>Users - All | Commercial Broker Connection</title>
@endsection

@section('content')
    @if($back_url = Session::get('prev_url'))
        <a href="{{ $back_url }}" id="save" class="btn btn-secondary btn-sm mt-4 save">
    @else
        <a href="{{ URL::previous() }}" id="save" class="btn btn-secondary btn-sm mt-4 save">
    @endif
        <i class="material-icons">keyboard_backspace</i> Back</a>
    <h2 class="h4 mt-3 ml-1">All Users</h2>
    <div class="card-body p-0 pb-3 text-center">

        <table class="table mb-0">
            <thead class="bg-light">
            <tr>
                <th scope="col" class="border-0">#</th>
                <th scope="col" class="border-0">First Name</th>
                <th scope="col" class="border-0">Last Name</th>
                <th scope="col" class="border-0">Email</th>
                <th scope="col" class="border-0">Real Estate License</th>
                <th scope="col" class="border-0">Approved</th>
                <th scope="col" class="border-0">Edit</th>
            </tr>
            </thead>
            <tbody>

            @foreach($users as $all)
                @if($all->rejected != 'on')
                    <tr>
                        <td>{{ $all->id }}</td>
                        <td>{{ $all->first_name }}</td>
                        <td>{{ $all->last_name }}</td>
                        <td><a href="mailto:{{ $all->email }}">{{ $all->email }}</a></td>
                        <td>{{ $all->license }}</td>

                        @if($all->approved == 'on')
                            <td><i class="material-icons">verified_user</i></td>
                        @else
                            <td>Not Approved</td>
                        @endif

                        <td>
                            <div class="btn-group btn-group-sm">
                                <button type="button" onclick='window.location.replace("/users/{{ $all->id }}/edit")' class="btn btn-white">
                                    <span class="text-light">
                                        <i class="material-icons">more_vert</i>
                                    </span> Edit
                                </button>
                            </div>
                        </td>
                    </tr>
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

