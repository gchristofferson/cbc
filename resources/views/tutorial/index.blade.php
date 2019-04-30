@extends ('layouts.app')

@section('title')
    <title>Tutorial | Commercial Broker Connection</title>
@endsection

@section('content')
    <div class="container-fluid pt-4">
        @if($first)
            <div class="row ">
                <div class="col-md-12 card card-small user-activity mb-1">
                    Welcome this is the first time you visit this page
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12 card card-small user-activity mb-4">
                <pre>
                    First do something

                    Then do something else

                    blah blah
                </pre>
            </div>
        </div>
    </div>

@endsection
@section('footer-btn')

@endsection
