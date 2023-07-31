@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
    <!-- start search-section -->
    <div class="search-section">
        <!-- start container -->
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <form method="GET" action="{{ route('dashboard.inventory.index') }}" accept-charset="UTF-8">

                        <div class="form-group">
                            <label for="accountSearch" class="sr-only">Account Number/BTN</label>
                            <input id="accountSearch" name="accountSearch" type="text" value="{{ request('accountSearch') }}" class="form-control" placeholder="Account Number/BTN">
                        </div>

                        <div class="form-group">
                            <label for="circuitSearch" class="sr-only">Circuit ID/Telephone #</label>
                            <input id="circuitSearch" name="circuitSearch" type="text" value="{{ request('circuitSearch') }}" class="form-control" placeholder="Circuit ID/Telephone #">
                        </div>

                        <div class="form-group">
                            <label for="CarrierID" class="sr-only">Carrier</label>
                            <select id="CarrierID" name="CarrierID" class="form-control">
                                <option selected value="">All Carriers</option>
                                @foreach($_options['CarrierID'] as $value => $title)
                                    <option value="{{ $value }}" {{ request('CarrierID') === $value ? 'selected' : '' }}>{{ $title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="circuitInventorySearch" class="sr-only">Circuit ID/Telephone # Inventory</label>
                            <input id="circuitInventorySearch" name="circuitInventorySearch" type="text" value="{{ request('circuitInventorySearch') }}" class="form-control" placeholder="Circuit ID/Telephone # Inventory">
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-4 col-md-3">
                                <button class="btn-primary btn-block border-white" style="width:100%" type="submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                @can('edit')
                    <div class="col-md-3 offset-md-1">
                        <div class="dropdown">
                            <a class="btn-primary dropdown-toggle" href="#" id="createNewMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"  style="color: white; text-decoration: none;">
                                CREATE
                            </a>

                            <div class="dropdown-menu" aria-labelledby="createNewMenuLink">
                                <a style="text-decoration: none;" class="dropdown-item" href="{{ route('dashboard.inventory.create') }}">New Account#/BTN</a>
                                <a style="text-decoration: none;" class="dropdown-item" href="{{ route('dashboard.orders.create',['from' => 'order', 'category' => 1]) }}">New Order</a>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
        <!-- end container -->
    </div>
    <!-- end search-section -->
    <!-- start main-container -->
    @can('edit')
        <div class="main-container">
            <!-- start container -->
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-lg-3">
                        <!-- start statistics -->
                        <div class="statistics">
                            <h2 class="common-heading">STATISTICS</h2>
                            <ul>
                                <li>
                                    <span class="heading">{{ $TotalDisconnect }}</span>
                                    <span>Disconnects this month</span>
                                    @if($TotalDisconnect)
                                        <a href="{{ route('dashboard.inventory.index', ['disconnect']) }}">See Details</a>
                                    @endif
                                </li>
                                <li>
                                    <span class="heading">{{ $TotalExpire }}</span>
                                    <span>Contract expirations this month</span>
                                    @if($TotalExpire)
                                        <a href="{{ route('dashboard.inventory.index', ['expiration']) }}">See Details</a>
                                    @endif
                                </li>
                            </ul>
                        </div>
                        <!-- end statistics -->
                    </div>
                    <div class="col-md-8 col-lg-9">
                        <!-- start statistics -->
                        <div class="report">
                            <h2 class="common-heading">REPORTS</h2>
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active btn-group-vertical" data-toggle="tab" href="#home" role="tab"><span>Accounts<br>Payable</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#profile" role="tab"><span>Credits</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#messages" role="tab"><span>Invoice by <span>Account#</span></span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#settings" role="tab"><span>New Accounts</span></a>
                                </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active" id="home" role="tabpanel">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Account #</th>
                                            <th>Bill Date</th>
                                            <th class="text-xs-right">Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($Reports['AccountsPayable'] as $report)
                                            <tr>
                                                <td>{{$report['AccountNumber']}}</td>
                                                <td>{{DateTime::createFromFormat('Y-m-d', $report['BillDate'])->format('m/d/Y')}}</td>
                                                <td class="text-xs-right">${{$report['CurrentCharges']}}</td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                    <a href="{{ route('dashboard.reports.index') }}?report_search=52" class="see-more">See Report</a>
                                </div>
                                <div class="tab-pane" id="profile" role="tabpanel">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Account #</th>
                                            <th>Bill Date</th>
                                            <th class="text-xs-right">Credit Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($Reports['Credits'] as $report)
                                            <tr>
                                                <td>{{$report['AccountNumber']}}</td>
                                                <td>{{DateTime::createFromFormat('Y-m-d', $report['BillDT'])->format('m/d/Y')}}</td>
                                                <td class="text-xs-right">${{$report['CreditAmt']}}</td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                    <a href="{{ route('dashboard.reports.index') }}?report_search=26" class="see-more">See Report</a>
                                </div>
                                <div class="tab-pane" id="messages" role="tabpanel">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Account #</th>
                                            <th>Invoice #</th>
                                            <th class="text-xs-right">Due Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($Reports['Invoice'] as $report)
                                            <tr>
                                                <td>{{$report['AccountNumber']}}</td>
                                                <td>{{$report['InvoiceNum']}}</td>
                                                <td class="text-xs-right">{{$report['DueDT']}}</td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                    <a href="{{ route('dashboard.reports.index') }}?report_search=26" class="see-more">See Report</a>
                                </div>
                                <div class="tab-pane" id="settings" role="tabpanel">         <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Account #</th>
                                            <th>Carrier</th>
                                            <th class="text-xs-right">Date Entered</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($Reports['NewAccounts'] as $report)
                                            <tr>
                                                <td>{{$report['AccountNumber']}}</td>
                                                <td>{{$report['Carrier']}}</td>
                                                <td class="text-xs-right">{{DateTime::createFromFormat('Y-m-d H:i:s.u', $report['DateEntered'])->format('m/d/Y')}}</td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                    <a href="{{ route('dashboard.reports.index') }}?report_search=70" class="see-more">See Report</a></div>
                            </div>
                        </div>
                        <!-- end statistics -->
                    </div>
                </div>
                <!-- start gallery slider -->
                <div class="favorite-report" style="margin-bottom:40px;">
                    <h2 class="common-heading">FAVORITE REPORTS ({{ count($favoriteReports) }})</h2>
                    <div class="row">
                        <div class="col-md-10 col-12 offset-md-1 report-section">
                            @if(count($favoriteReports))
                                <div class="carousel slide" id="myCarousel" data-ride="carousel">
                                    <div class="carousel-inner">
                                        @foreach($favoriteReports->chunk(5) as $reports)
                                            <div class="carousel-item @if($loop->first) active @endif">
                                                @foreach($reports as $report)
                                                    <div class=" report-view">
                                                        <i class="fa fa-file-text"></i>
                                                        <span>{{ $report['title'] }}</span>
                                                        <ul class="option">
                                                            @if($report['canEmail'])
                                                                <li title="Send via email"><a data-report-has-date-range="{{ intval($report['has_date_range']) }}" data-report-url="{{ route('dashboard.reports.email', [$report['name']]) }}" href="#reportsEmailModal" data-toggle="modal"><img src="{{ asset('/assets/images/upload.png') }}" width="20" height="22" alt="upload"></a></li>
                                                            @endif
                                                            @if($report['has_date_range'])
                                                                <li title="Download"><a data-report-url="{{ route('dashboard.reports.download', [$report['name']]) }}" href="#reportsDownloadModal" data-toggle="modal"><img src="{{ asset('/assets/images/download.png') }}" width="13" height="22" alt="download"></a></li>
                                                            @else
                                                                <li title="Download"><a class="download_report"  href="{{ route('dashboard.reports.download', [$report['name']]) }}"><img src="{{ asset('/assets/images/download.png') }}" width="13" height="22" alt="download"></a></li>
                                                            @endif
                                                        </ul>
                                                    </div>

                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                    @if(count($favoriteReports) > 5)
                                        <a class="left carousel-control text-xs-left" href="#myCarousel" data-slide="prev"><i class="fa fa-chevron-left"></i></a>
                                        <a class="right carousel-control text-xs-right" href="#myCarousel" data-slide="next"><i class="fa fa-chevron-right"></i></a>
                                    @endif
                                </div>
                            @else
                                <a style="font-size: 1.2em;" href="{{ route('dashboard.reports.index') }}"> No favorite reports have been added. To add a report to favorites, click HERE.</a>
                            @endif
                        </div>
                        @if(count($favoriteReports))
                            <div class="col-10">
                                <a href="{{ route('dashboard.settings.favorite-reports.index') }}">Edit Favorite Reports</a>
                            </div>
                        @endif
                    </div>

                </div>
                <!-- end gallery slider -->

                <!--report modals for email and download dates -->
                @include('dashboard.reports.modals')
                <!--end report modals for email and download dates -->

            </div>
            <!-- end container -->
        </div>
    @endcan
    <!-- end main-container -->
@endsection
