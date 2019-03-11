@extends ('layouts.app')

@section('title')
    <title>Sent Inquiries | Commercial Broker Connection</title>
@endsection

@section('content')
    <!-- Saved Inquiries -->
    <div class="card card-small user-activity mb-4  mt-4">
        <div class="card-header border-bottom">
            <h6 class="m-0">Sent Inquires</h6>
            <div class="block-handle"></div>
        </div>
        {{--<div class="no-footer border-bottom">--}}
        {{--<div class="dataTables_length" id="DataTables_Table_0_length">--}}
        {{--<label>Show --}}
        {{--<select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="">--}}
        {{--<option value="10">10</option>--}}
        {{--<option value="25">25</option>--}}
        {{--<option value="50">50</option>--}}
        {{--<option value="100">100</option>--}}
        {{--</select> entries--}}
        {{--</label>--}}
        {{--</div>--}}
        {{--<div id="DataTables_Table_0_filter" class="dataTables_filter">--}}
        {{--<label>Search:<input type="search" class="" placeholder="" aria-controls="DataTables_Table_0"></label>--}}
        {{--</div>--}}
        {{--</div>--}}
        <div class="card-body p-0">

            @foreach($sent_inquiries as $sent_inquiry)
                <div class="user-activity__item pr-3 py-3" data-type="sent">
                    <div class="user-activity__item__icon">
                        <i class="material-icons">send</i>
                    </div>
                    <div class="user-activity__item__content">
                        <span class="text-light">{{ $sent_inquiry[0]['created_at'] }}</span>
                        <p>{{ $sent_inquiry[0]['subject'] }} | {{ str_limit(strip_tags($sent_inquiry[0]['body']), $limit = 30, $end = '...') }}</p>
                    </div>
                    <div class="row ml-auto">
                        <div class="user-activity__item__action ml-auto mr-1">
                            <a href="/inquiries/{{ $sent_inquiry[0]['id'] }}" class="ml-auto btn btn-sm btn-white">View Inquiry</a>
                        </div>
                        <div class="user-activity__item__action ml-auto mr-3">
                            <form method="POST" action="/sent/{{ $sent_inquiry['sent_id'] }}">
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
                <span class="float-right">{{ $sent_inquiry_rows->links() }}</span>
            </div>
        </div>
    </div>
    <!-- End Saved Inquires -->
@endsection
@section('footer-btn')

@endsection
