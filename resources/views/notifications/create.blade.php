@extends('layouts.crud')

@section('title')
    <title>Notifications | Commercial Broker Connections</title>
@endsection

@section('content')
    <div class="card card-small mb-3">
        <div class="card-header border-bottom">
            <h6 class="m-0">Notifications</h6>
        </div>
        <ul class="list-group ">
            <li class="list-group-item p-3">
                <div class="row">
                    <div class="col form-row">
                        <div class="form-row mx-4">
                            <div class="form-group col-md-4">
                                <label for="userBio">Add Notification Subscription</label>
                                <!-- Default Light Table -->
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <div class="border-bottom"></div>
                                            <form method="POST">
                                                @csrf
                                                <div class="form-group mb-0 row container">
                                                    <div class="form-group mb-3 col-12">
                                                        <label for="feInputCity">City</label>
                                                        <select data-city="js-input-city" id="" class="form-control col-10 city" name="city" required>
                                                            <option value="" selected>Choose City...</option>
                                                            @foreach($cities as $city)
                                                                <option value="{{ $city->id }}">{{ $city->city }}, {{ $city->state->state }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <!-- / Select -->
                                                    <button type="submit" class="btn btn-accent add-market-btn">Add Notification</button>
                                                </div>
                                            </form>


                                        </div>
                                    </div>
                                </div>
                                <!-- End Default Light Table -->
                            </div>
                            <div class="form-group col-md-8">
                                <label for="userBio">Notification Subscriptions</label>
                                <!-- Default Light Table -->
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <div class="border-bottom">
                                            </div>
                                            <div class="card-body p-0 pb-3 text-center">
                                                <table class="table mb-0 notification-subscriptions">
                                                    <thead class="bg-light">
                                                    <tr>
                                                        <th scope="col" class="border-0">#</th>
                                                        <th scope="col" class="border-0">City</th>
                                                        <th scope="col" class="border-0">State</th>
                                                        <th scope="col" class="border-0">Delete</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>


                                                    @foreach($notifications as $notification)
                                                        <tr>
                                                            <td>{{ $notification->id }}</td>
                                                            <td>{{ $notification->city->city }}</td>
                                                            <td>{{ $notification->city->state->state }}</td>
                                                            <td>
                                                                <form method="POST" action="/notifications/{{ $notification->id }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger">
                                                                            <i class="material-icons"></i>
                                                                        </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach


                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Default Light Table -->
                            </div>

                        </div>
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

                    </div>
                </div>
            </li>
        </ul>
    </div>

@endsection
