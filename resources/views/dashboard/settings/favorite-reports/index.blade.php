@extends('layouts.dashboard')

@section('title', 'Favorite Reports - Settings')
@section('content')

    <!-- start main-container -->
    <div class="main-container settings favorite-reports">
        <!-- start container -->
        <div class="container">
            <p>
                <a href="{{ route('dashboard.settings.index') }}">&lt; Back</a>
            </p>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <h1>Favorite Reports</h1>
                </div>
                <div class="col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm text-xs-center">
                            <thead>
                            <tr>
                                <th>Order</th>
                                <th>Report Name</th>
                                <th class="text-right">Delete</th>
                                <th class="text-right">Order</th>
                            </tr>
                            </thead>
                            <tbody data-order-url="{{ route('dashboard.settings.favorite-reports.order') }}">
                            @if(count($favoriteReports))
                                @foreach($favoriteReports as $favoriteReport)
                                    <tr>
                                        <td data-id="{{ $favoriteReport->ReportID }}"></td>
                                        <td>{{ $favoriteReport->ReportName }}</td>
                                        <td class="text-right"><a href="" data-confirmation-btn="true"><img src="{{ asset('/assets/images/delete-icon.png') }}" alt=""></a></td>
                                        <td class="text-right"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></td>
                                        <td data-confirmation-body colspan="4" class="text-left">

                                            <form style="display: none;" method="POST" action="{{ route('dashboard.settings.favorite-reports.destroy', [$favoriteReport->ReportID]) }}">
                                                {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                                <input type="hidden" name="page" value="{{ request('page') }}" />
                                            </form>

                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            Are you sure you want to remove this report from your favorites?

                                            <a href="" data-delete-form>Yes</a>
                                            <a href="#" data-confirmation-btn="false">No</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8" class="no-highlight text-center"><h3 class="not-found">Sorry, No Favorite Reports Found</h3></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- end container -->

        </div>
        <!-- end container -->
    </div>

    <!-- end main-container -->
@endsection
