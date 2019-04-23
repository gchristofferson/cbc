@extends ('layouts.app')

@section('title')
    <title>Saved Inquiries | Commercial Broker Connection</title>
@endsection

@section('content')
    <!-- Saved Inquiries -->
    <div class="card card-small user-activity mb-4  mt-4">
        <div class="card-header border-bottom">
            <h6 class="m-0">Saved Inquires</h6>
            <div class="block-handle"></div>
        </div>
        <div class="card-body p-0">

            @foreach($saved_inquiries as $saved_inquiry)
                <div class="user-activity__item pr-3 py-3" data-type="saved">
                    <div class="user-activity__item__icon">
                        <i class="material-icons star">star_rate</i>
                    </div>
                    <div class="user-activity__item__content">
                        <span class="text-light">{{ $saved_inquiry[0]['created_at'] }}</span>
                        <p>{{ $saved_inquiry[0]['subject'] }} | {{ str_limit(strip_tags($saved_inquiry[0]['body']), $limit = 30, $end = '...') }}</p>
                    </div>
                    <div class="row ml-auto">
                        <div class="user-activity__item__action ml-auto mr-1">
                            <a href="/inquiries/{{ $saved_inquiry[0]['id'] }}" class="ml-auto btn btn-sm btn-white">View Inquiry</a>
                        </div>
                        <div class="user-activity__item__action ml-auto mr-3">
                            <form method="POST" action="/saved/{{ $saved_inquiry['saved_id'] }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="material-icons">&#xE872;</i>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
        <div class="card-footer border-top">
            <div class="col text-center view-report">
                <span class="float-right">{{ $saved_inquiry_rows->links() }}</span>
            </div>
        </div>
    </div>
    <!-- End Saved Inquires -->
@endsection
@section('footer-btn')

@endsection
