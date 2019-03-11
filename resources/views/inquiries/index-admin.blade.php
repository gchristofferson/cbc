@extends ('layouts.admin-crud')

@section('title')
    <title>Inquiries | Commercial Broker Connection</title>
@endsection

@section('content')
    @if($back_url = Session::get('prev_url'))
        <a href="{{ $back_url }}" id="save" class="btn btn-secondary btn-sm mt-4 save">
    @else
        <a href="{{ URL::previous() }}" id="save" class="btn btn-secondary btn-sm mt-4 save">
    @endif
        <i class="material-icons">keyboard_backspace</i> Back</a>
    <!-- Received Inquiries -->
    <div class="card card-small user-activity mb-4 mt-4">
        <div class="card-header border-bottom">
            <h6 class="m-0">All Inquires</h6>
            <div class="block-handle"></div>
        </div>
        <div class="card-body p-0">

            @foreach($inquiries as $inquiry)
                <div class="user-activity__item pr-3 py-3">
                    <div class="user-activity__item__icon">
                        <i class="material-icons">mail</i>
                    </div>
                    <div class="user-activity__item__content">
                        <span class="text-light">{{ $inquiry->created_at }}</span>

                        @if($inquiry->read == 'off')
                            <p class="font-weight-bold">{{ str_limit($inquiry->subject, $limit = 30, $end = '...') }} | {{ str_limit(strip_tags($inquiry->body), $limit = 30, $end = '...') }}</p>
                        @else
                            <p>{{ $inquiry->subject }} | {{ str_limit(strip_tags($inquiry->body), $limit = 30, $end = '...') }}</p>
                        @endif

                    </div>
                    <div class="user-activity__item__action ml-auto">
                        <a href="/inquiries/{{ $inquiry->id }}" class="ml-auto btn btn-sm btn-white view-inquiry">View Inquiry</a>
                        <button type="button" class="btn btn-danger">
                            <i class="material-icons">&#xE872;</i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="card-footer border-top">
            <div class="col text-center view-report">
                <span class="float-right">{{ $inquiries->links() }}</span>
            </div>
        </div>
    </div>
    <!-- End Received Inquires -->
@endsection
@section('footer-btn')

@endsection
