@extends('layouts.dashboard')

@section('title', 'Reports')

@section('content')
    <!-- start main-container -->
    <div class="main-container">
        <!-- start container -->
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <!-- start report-section -->
                    <div class="report-section">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="search col-sm-8 col-md-5 col-lg-4 float-xs-right">
                                    <form>
                                        <input name="report_search" type="text" value="" class="form-control" placeholder="Search Report">
                                        <div class="search-btn"><a href="#"><i class="fa fa-search"></i></a></div>
                                    </form>
                                </div>
                                @if($ViewAll)
                                    <div style="margin-top:4px;" class="col-sm-2">
                                        <a href="{{route('dashboard.reports.index')}}">VIEW ALL</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if($reports[\App\Components\Reports\AbstractReport::CATEGORY_GENERAL]['reports'] || $reports[\App\Components\Reports\AbstractReport::CATEGORY_INVENTORY]['reports'])
                            @foreach($reports as $identifier => $category)
                                @if(($identifier == \App\Components\Reports\AbstractReport::CATEGORY_GENERAL || $identifier == \App\Components\Reports\AbstractReport::CATEGORY_INVENTORY)&& $category['reports'])
                                    <div class="col-md-12">
                                        <h2 class="text-uppercase">{{ $category['title'] }}</h2>
                                        <div class="row">
                                            @foreach($category['reports'] as $report)
                                                <div class="col-sm-4 col-md-2 col-6 report-view">
                                                    <i class="fa fa-file-text"></i>
                                                    <span>{{ $report['title'] }}</span>
                                                    <ul class="option">
                                                        @can('edit')
                                                            <li title="Save to Favorites"><a href="{{ route('dashboard.reports.favorite', ['name' => $report['name'], 'reportID' => $report['reportID']]) }}"><img src="{{ asset('/assets/images/bookmark.png') }}" width="18" height="22" alt="bookmark"></a></li>
                                                        @endcan
                                                        @if($report['canEmail'])
                                                            <li title="Send via email"><a data-report-has-date-range="{{ intval($report['has_date_range']) }}" data-report-url="{{ route('dashboard.reports.email', ['name' => $report['name']]) }}" href="#reportsEmailModal" data-toggle="modal"><img src="{{ asset('/assets/images/upload.png') }}" width="20" height="22" alt="upload"></a></li>
                                                        @endif
                                                        @if($report['has_date_range'])
                                                            <li title="Download"><a data-report-url="{{ route('dashboard.reports.download', ['name' => $report['name']]) }}" href="#reportsDownloadModal" data-toggle="modal"><img src="{{ asset('/assets/images/download.png') }}" width="13" height="22" alt="download"></a></li>
                                                        @else
                                                            <li title="Download"><a class="download_report"  href="{{ route('dashboard.reports.download', ['name' => $report['name']]) }}"><img src="{{ asset('/assets/images/download.png') }}" width="13" height="22" alt="download"></a></li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                @endif
                            @endforeach
                        @else
                            <h1 style="padding-left:15px;padding-top:45px;">Sorry, your search did not return any results.</h1>
                        @endif
                    </div>
                    <!-- end report-section -->
                </div>
            </div>
        </div>
        <!-- end container -->
        <!--report modals for email and download dates -->
            @include('dashboard.reports.modals')
        <!--end report modals for email and download dates -->
            </div>
        </div>

    </div>
    <!-- end main-container -->
@endsection