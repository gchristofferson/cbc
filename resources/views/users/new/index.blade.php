@extends ('layouts.admin-crud')

@section('title')
    <title>Users - New | Commercial Broker Connection</title>
@endsection

@section('content')
    @if($back_url = Session::get('prev_url'))
        <a href="{{ $back_url }}" id="save" class="btn btn-secondary btn-sm mt-4 save">
            <i class="material-icons">keyboard_backspace</i> Back</a>
    @else
        <a href="{{ URL::previous() }}" id="save" class="btn btn-secondary btn-sm mt-4 save">
            <i class="material-icons">keyboard_backspace</i> Back</a>
    @endif
    <h2 class="h4 mt-3 ml-1">New Users</h2>
    <div class="card-body p-0 pb-3 text-center">
        <table class="table mb-0">
            <thead class="bg-light">
            <tr>
                <th scope="col" class="border-0">#</th>
                <th scope="col" class="border-0">First Name</th>
                <th scope="col" class="border-0">Last Name</th>
                <th scope="col" class="border-0">Email</th>
                <th scope="col" class="border-0">Real Estate License</th>
                <th scope="col" class="border-0">Approve/Reject/Edit</th>
            </tr>
            </thead>
            <tbody>

            @foreach($users as $new)
                @if($new->approved == 'off' && $new->admin != 'on' && $new->rejected != 'on')
                    <tr>
                        <td>{{ $new->id }}</td>
                        <td>{{ $new->first_name }}</td>
                        <td>{{ $new->last_name }}</td>
                        <td><a href="mailto:{{ $new->email }}">{{ $new->email }}</a></td>
                        <td>{{ $new->license }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <form method="POST" action="/users/{{ $new->id }}">
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

                                <form method="POST" action="/users/{{ $new->id }}">
                                    @method('PATCH')
                                    @csrf
                                    <input type='hidden' value='' name='rejected-btn'>
                                    <input type='hidden' value='on' name='rejected'>
                                    <button type="submit" class="btn btn-white">
                                    <span class="text-danger">
                                        <i class="material-icons">clear</i>
                                    </span> Reject
                                    </button>
                                </form>

                                <button type="button" onclick='window.location.replace("/users/{{ $new->id }}/edit")' class="btn btn-white">
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

