<!doctype html>
<html class="no-js h-100" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    @yield('title')
    <meta name="description" content="A platform for connecting commercial real estate agents together for sharing connections and off market deals in your area.">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" id="main-stylesheet" data-version="1.2.0" href="{{ asset('css/shards-dashboards.1.2.0.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/extras.1.2.0.min.css') }}">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
</head>
<body class="h-100">
<div class="container-fluid">
    <div class="row">
        <!-- Main Sidebar -->
        <aside class="main-sidebar col-12 col-md-3 col-lg-2 px-0">
            <div class="main-navbar">
                <nav class="navbar align-items-stretch navbar-light bg-white flex-md-nowrap border-bottom p-0">
                    <a class="navbar-brand w-100 mr-0" href="/">
                        <div class="d-table m-auto">
                            <img id="main-logo" class="d-inline-block align-top mr-1" src="{{ asset('img/real-estate-connections-logo.png') }}" alt="Commercial Broker Connection Logo">
                            {{--<span class="logo-text d-none d-md-inline ml-1">Commercial Broker Connections</span>--}}
                        </div>
                    </a>
                    <a class="toggle-sidebar d-sm-inline d-md-none d-lg-none">
                        <i class="material-icons">&#xE5C4;</i>
                    </a>
                </nav>
            </div>
            <form action="#" class="main-sidebar__search w-100 border-right d-sm-flex d-md-none d-lg-none">
                <div class="input-group input-group-seamless ml-3">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                    <input class="navbar-search form-control" type="text" placeholder="Search for something..." aria-label="Search"> </div>
            </form>
            <div id="navigation" class="nav-wrapper">
                <ul class="nav nav--no-borders flex-column">
                    <li class="nav-item">
                        <a class="nav-link " href="/dashboard">
                            <i class="material-icons">person</i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="/inquiries/create">
                            <i class="material-icons">add_circle</i>
                            <span>Make Inquiry</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="/received">
                            <i class="material-icons">mail</i>
                            {{--<span class="badge badge-pill badge-danger">2</span>--}}
                            <span>Received Inquires</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="/sent">
                            <i class="material-icons">send</i>
                            <span>Sent Inquiries</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="/saved">
                            <i class="material-icons">star</i>
                            <span>Saved Inquiries</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle " data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
                            <i class="material-icons">settings</i>
                            <span>Admin</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-small">
                            <a class="dropdown-item " href="/users">Users</a>
                            <a class="dropdown-item " href="/states">Markets</a>
                            <a class="dropdown-item " href="/inquiries">Inquiries</a>
                        </div>
                    </li>
                </ul>
            </div>
        </aside>
        <!-- End Main Sidebar -->
        <main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
            <div class="main-navbar sticky-top bg-white">
                <!-- Main Navbar -->
                <nav class="navbar align-items-stretch navbar-light flex-md-nowrap p-0">
                    <form id="js-search-form" class="main-navbar__search w-100 d-none d-md-flex d-lg-flex">
                        <div class="input-group input-group-seamless ml-3">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </div>
                            </div>
                            <input id="js-search-input" class="navbar-search form-control" type="text" placeholder="Search Inquiries..." aria-label="Search"> </div>
                    </form>
                    <ul class="navbar-nav border-left flex-row ">
                        <li class="nav-item border-right dropdown notifications">
                            <a class="nav-link nav-link-icon text-center vertical-center" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="nav-link-icon__wrapper m-auto">
                                    <i class="material-icons">mail</i>
                                    @php($unread_count = 0)
                                    @foreach($received_inquiries as $received_inquiry)
                                        @if($received_inquiry['read'] == false)
                                            @php($unread_count++)
                                        @endif
                                    @endforeach
                                    <span class="badge badge-pill badge-danger">{{ ($unread_count == 0 ? '' : $unread_count)}}</span>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-small" aria-labelledby="dropdownMenuLink">
                                @foreach($received_inquiries as $received_inquiry)
                                    @if($received_inquiry['read']=== 0)
                                        <a class="dropdown-item" href="/inquiries/{{ $received_inquiry[0]['id'] }}">
                                            <div class="notification__icon-wrapper">
                                                <div class="notification__icon">
                                                    <i class="material-icons">mail</i>
                                                </div>
                                            </div>
                                            <div class="notification__content">
                                                <span class="notification__category">{{ $received_inquiry[0]['created_at'] }}</span>
                                                <p>{{ $received_inquiry[0]['subject']}} | {{ str_limit(strip_tags($received_inquiry[0]['body']), $limit = 30, $end = '...') }}</p>
                                            </div>
                                        </a>
                                    @endif
                                @endforeach
                                <a class="dropdown-item notification__all text-center" href="/received"> View all Received Inquiries </a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-nowrap px-3 vertical-center" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                @if( $user->avatar == 'placeholder.gif')
                                    <img class="user-avatar rounded-circle mr-2" src='{{ asset("img/avatars/$user->avatar") }}' alt="User Avatar">
                                @else
                                    {{--replace with unique path to avatar--}}
                                    <img class="user-avatar rounded-circle mr-2" src='{{ $user->avatar }}' alt="User Avatar">
                                @endif

                                <span class="d-none d-md-inline-block">{{ $user->first_name }} {{ $user->last_name }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-small">
                                <a class="dropdown-item" href="/dashboard">
                                    <i class="material-icons">&#xE7FD;</i> Profile</a>
                                <a class="dropdown-item" href="/update-profile">
                                    <i class="material-icons">&#xE8B8;</i> Edit Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#">
                                    <i class="material-icons text-danger">&#xE879;</i> Logout </a>
                            </div>
                        </li>
                    </ul>
                    <nav class="nav">
                        <a href="#" class="nav-link nav-link-icon toggle-sidebar d-md-inline d-lg-none text-center border-left vertical-center" data-toggle="collapse" data-target=".header-navbar" aria-expanded="false" aria-controls="header-navbar">
                            <i class="material-icons m-auto">&#xE5D2;</i>
                        </a>
                    </nav>
                </nav>
            </div>
            <!-- / .main-navbar -->
            <div class="main-content-container container-fluid px-4">
                <!-- Content -->
                @if($message = Session::get('success'))
                    <div class="container-fluid px-0">
                        <div class="alert alert-success alert-dismissible fade show m-0" role="alert">
                            {!! $message !!}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                    @php(Session::forget('success'))
                @endif
                @if($message = Session::get('error'))
                    <div class="container-fluid px-0">
                        <div class="alert alert-danger alert-dismissible fade show m-0" role="alert">
                            {!! $message !!}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                    @php(Session::forget('error'))
                @endif
                @if($message = Session::get('message'))
                    <div class="container-fluid px-0">
                        <div class="alert alert-success alert-dismissible fade show m-0" role="alert">
                            {!! $message !!}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                    @php(Session::forget('message'))
                @endif
                @if( isset($msg) && $msg != '')
                    <div class="container-fluid px-0">
                        <div class="alert alert-danger alert-dismissible fade show m-0" role="alert">
                            {{ $msg }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                    @php( $msg = '')
                @endif
                @if( isset($success_msg) && $success_msg != '')
                    <div class="container-fluid px-0">
                        <div class="alert alert-success alert-dismissible fade show m-0" role="alert">
                            {{ $success_msg }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                    @php( $success_msg = '')
                @endif
                @if( isset($msg) && $msg != '')
                    <div class="container-fluid px-0">
                        <div class="alert alert-danger alert-dismissible fade show m-0" role="alert">
                            {{ $msg }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                    @php( $msg = '')
                @endif
                @if( isset($success_msg) && $success_msg != '')
                    <div class="container-fluid px-0">
                        <div class="alert alert-success alert-dismissible fade show m-0" role="alert">
                            {{ $success_msg }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                    @php( $success_msg = '')
                @endif
                @if($message = Session::get('promo_msg'))
                    @php($price = Session::get('price'))
                    <div class="container-fluid px-0">
                        <div class="alert alert-dismissible fade show m-0" role="alert">
                            <div class="mx-4 site-subscription">
                                <div class="col mb-3">
                                    <h6 class="form-text m-0">Site Subscription</h6>
                                    <p class="form-text text-muted m-0">{{ $message }}</p>
                                    <p class="form-text text-muted font-weight-bold m-0">Don't lose access to your connections and inquires!</p>
                                    <p class="h4 text-monospace">Upgrade your annual membership to this State for only ${{ $price }}</p>
                                </div>
                            </div>
                            <div class="mx-4">
                                <div class="col d-flex">
                                    <a href="#subscribe"><button type="button" class="mb-2 btn btn-md btn-secondary mr-1 upgrade-btn">Upgrade Now</button></a>
                                </div>
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                    @php(Session::forget('promo_msg'))
                    @php(Session::forget('price'))
                @endif
            <div class="card card-small user-activity mb-4 mt-4">
            <div class="card card-small edit-user-details mb-4">
                <!--<div class="card-header p-0">-->
                <!--</div>-->
                <div class="card-header card-body p-0">
                    <div class="border-bottom clearfix d-flex">
                        <ul class="nav nav-tabs border-0 mt-auto mx-4 pt-2">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle " href="/users" data-toggle="dropdown">Users</a>
                                    <div class="dropdown-menu dropdown-menu-small">
                                        <a href="/users" class="dropdown-item ">All Users</a>
                                        <a href="/new/users" class="dropdown-item ">New Users</a>
                                        <a href="/rejected/users" class="dropdown-item ">Rejected Users</a>
                                    </div>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link " href="/states">Markets</a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link " href="/inquiries">Inquiries</a>
                            </li>
                        </ul>
                    </div>
                    <div class="form-row mx-4">
                        <!-- Default Light Table -->
                        <div class="row table-responsive">
                            <div class="col">
                                <div class="mb-4">
                                    @if($message = Session::get('success'))
                                        <div class="container-fluid px-0">
                                            <div class="alert alert-success alert-dismissible fade show m-0" role="alert">
                                                {!! $message !!}
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                        </div>
                                        @php(Session::forget('success'))
                                    @endif
                                    @if($message = Session::get('error'))
                                        <div class="container-fluid px-0">
                                            <div class="alert alert-danger alert-dismissible fade show m-0" role="alert">
                                                {!! $message !!}
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                        </div>
                                        @php(Session::forget('error'))
                                    @endif
                                    @if( isset($msg) && $msg != '')
                                        <div class="container-fluid px-0">
                                            <div class="alert alert-danger alert-dismissible fade show m-0" role="alert">
                                                {{ $msg }}
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                        </div>
                                        @php( $msg = '')
                                    @endif
                                    @if( isset($success_msg) && $success_msg != '')
                                        <div class="container-fluid px-0">
                                            <div class="alert alert-success alert-dismissible fade show m-0" role="alert">
                                                {{ $success_msg }}
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                        </div>
                                        @php( $success_msg = '')
                                    @endif
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                        <!-- End Default Light Table -->
                    </div>
                </div>
                <div class="card-footer border-top">

                    {{--@yield('footer-btn')--}}
                </div>
            </div>
            </div>
                <!-- End Content -->
            </div>
        </main>
        <footer class="main-footer d-flex p-2 px-3 bg-white border-top w-100 offset-lg-2 offset-md-3">
            <span class="copyright ml-auto my-auto mr-2">Copyright © 2019 Commercial Broker Connections</span>
        </footer>
    </div>
</div>

<!-- Vendors -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<!-- Dashboard JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
<script src="https://unpkg.com/shards-ui@latest/dist/js/shards.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sharrre/2.0.1/jquery.sharrre.min.js"></script>
<script src="{{ asset('js/extras.1.2.0.min.js') }}"></script>
<script src="{{ asset('js/shards-dashboards.1.2.0.min.js') }}"></script>
<script src="{{ asset('js/app/app-user-profile.1.2.0.js') }}"></script>

<!-- Custom JS -->
<script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
