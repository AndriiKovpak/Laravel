@extends('layouts.dashboard')

@section('title', 'Scanned Invoices')

@section('content')
<!-- start main-container -->

<div class="main-container invoices">
    <!-- start container -->

    <div class="container">
        <form class="controls">
            <div class="row">
                <div class="col-md-4">
                    @can('edit')
                    <select class="form-control invoice-type" data-url-scanned="{{ route('dashboard.invoices.index') }}" data-url-pending="{{ route('dashboard.invoices.pending') }}">
                        <option value="scanned" selected="selected">Scanned Invoices</option>
                        <option value="pending">Pending Invoices</option>
                    </select>
                    @endcan
                </div>
                <div class="col-md-8 float-xs-right">
                    <div class="option-ico second clearfix">
                        <ul>
                            <li><a href="" class="toggle-filter"><i class="fa fa-gear"></i></a></li>
                            <li class="col-md-8 pr-0" style="padding:0">
                                <div class="search col-md-12 pr-0 col-12 float-xs-right" style="padding:0; min-height:30px">
                                    <div>
                                        <input type="hidden" name="search" value="{{ $params['search'] ?? 'hide' }}">
                                        <input name="searchText" type="text" value="{{ $params['searchText'] ?? '' }}" class="form-control hide-block search-input" placeholder="Searching BTN, Account #, Carrier or District">
                                        <div class="search-btn toggle-search"><a href=""><i class="fa fa-search"></i></a></div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- start list-invoice -->
            <div class="row">
                <div class="col-sm-12">

                    <div id="list-invoice" class="list-invoice">
                        <h2><a class="hide-filter toggle-filter" href="">&times;</a> LIST INVOICES BY</h2>
                        <input type="hidden" name="filter" value="{{ $params['filter'] ?? 'show' }}">

                        <div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="ProcessedMethod">Processed</label>
                                        <select class="form-control" id="ProcessedMethod" name="ProcessedMethod">
                                            <option value="">All</option>
                                            @foreach($processedMethodTypes as $type)
                                            <option value="{{ $type->ProcessedMethod }}" @if(isset($params['ProcessedMethod']) && $params['ProcessedMethod']==$type->ProcessedMethod) selected="selected" @endif
                                                >{{ $type->ProcessedMethodName }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="ProcessCode">Process Code</label>
                                        <select class="form-control" id="ProcessCode" name="ProcessCode">
                                            <option value="">All</option>
                                            @foreach($processCodes as $code)
                                            <option value="{{ $code->ProcessCode }}" @if(isset($params['ProcessCode']) && $params['ProcessCode']==$code->ProcessCode) selected="selected" @endif
                                                >{{ $code->ProcessCodeName }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="CarrierID">Carrier</label>
                                        <select class="form-control" id="CarrierID" name="CarrierID">
                                            <option value="">All Carriers</option>
                                            @foreach($carriers as $CarrierID => $CarrierName)
                                            <option value="{{ $CarrierID }}" @if(isset($params['CarrierID']) && $params['CarrierID']==$CarrierID) selected="selected" @endif>{{ $CarrierName}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="sortColumn">Sort Column</label>
                                        <select class="form-control" id="sortColumn" name="sortColumn">
                                            @foreach($sortFields as $key => $field)
                                            <option value="{{ $key }}" {{ Arr::get($params, 'sortColumn', 'BillDate') == $key ? ' selected' : '' }}>{{ $field }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="sortDirection">Sort Direction</label>
                                        <select class="form-control" id="sortDirection" name="sortDirection">
                                            <option value="asc" {{ Arr::get($params, 'sortDirection', 'desc') == 'asc' ? ' selected' : '' }}>Ascending</option>
                                            <option value="desc" {{ Arr::get($params, 'sortDirection', 'desc') == 'desc' ? ' selected' : '' }}>Descending</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!-- start form-group -->
                                    <div class="form-group">
                                        <label for="FiscalYearID">Fiscal Year</label>
                                        <select class="form-control" id="FiscalYearID" name="FiscalYearID">
                                            <option value="">All Fiscal Years</option>
                                            @foreach($fiscalYears as $fiscalYear)
                                            <option value="{{ $fiscalYear->FiscalYearID }}" @if(isset($params['FiscalYearID']) && $params['FiscalYearID']==$fiscalYear->FiscalYearID) selected="selected" @endif
                                                >{{ $fiscalYear->FiscalYearName }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input class="custom-check toggle-date-range" name="datecheck" id="date-range" @if(!empty($params['datecheck']) && $params['datecheck']=='1' ) checked="checked" @endif type="checkbox" value="1">
                                                <label for="date-range">View All Date Ranges</label>
                                            </div>
                                            <div id="date_range" class="mb-1 clearfix col-md-12" @if(!empty($params['datecheck']) && $params['datecheck']=='1' ) style="display: none;" @endif>
                                                <div class="form-group">
                                                    <label for="date_from">FROM</label>
                                                    <input class="form-control" id="date_from" name="from_date" value="{{ isset($params['from_date']) ? $params['from_date'] : '' }}" type="date">
                                                </div>
                                                <div class="form-group">
                                                    <label for="date_to">TO</label>
                                                    <input class="form-control" id="date_to" name="to_date" value="{{ isset($params['to_date']) ? $params['to_date'] : '' }}" type="date">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <input class="custom-check toggle-batch-date" name="batchcheck" id="batch-date" @if(!empty($params['batchcheck']) && $params['batchcheck']=='1' ) checked="checked" @endif type="checkbox" value="1">
                                                <label for="batch-date">View All Batch Dates</label>
                                            </div>
                                            <div id="batch_date" @if(!empty($params['batchcheck']) && $params['batchcheck']=='1' ) style="display: none;" @endif>
                                                <div class="col-md-12">
                                                    <input class="form-control" id="batch_filter" name='batch_date' value="{{ isset($params['batch_date']) ? $params['batch_date'] : '' }}" type="date">
                                                </div>
                                            </div>
                                            <div class="col-md-12" id="batch_date"></div>
                                        </div>
                                    </div>
                                    <!-- end form-group -->
                                </div>
                                <div class="col-md-4 offset-md-4">
                                    <input type="submit" id="searchInvoiceList" value="UPDATE LIST" class="btn-primary btn-block border-white refresh">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- end list-invoice -->
        <div class="col-md-12" id="invoiceList">
            <div class="row" id="displayInvoiceListing">
                <!-- start invoice-info -->
                <div class="invoice-info">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm text-xs-center">
                            <thead>
                                <tr>
                                    <th>Carrier</th>
                                    <th>BTN</th>
                                    <th>Account #</th>
                                    <th>Batch Date</th>
                                    <th>Invoice Date</th>
                                    <th>Process Code</th>
                                    <th>Processed</th>
                                    <th>Note</th>
                                    @if(isset($params['ProcessedMethod']) && $params['ProcessedMethod'] === '4')
                                    <th>New Invoice</th>
                                    @else
                                    <th>Details</th>
                                    @endif
                                    @can('edit')
                                    <th>Delete</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($invoices))
                                @foreach($invoices as $invoice)
                                <tr @if($invoice->Status != 1 && $invoice->ProcessedMethod === "4")class="error"@endif>
                                    <td>{{ $invoice->CarrierName }}</td>
                                    <td>{{ $invoice->BTN }}</td>
                                    <td>{{ $invoice->AccountNum }}</td>
                                    <td>{{ $invoice->BatchDate ? Carbon\Carbon::parse($invoice->BatchDate)->format('m/d/Y') : '' }}</td>
                                    <td>{{ $invoice->BillDate ? Carbon\Carbon::parse($invoice->BillDate)->format('m/d/Y') : '' }}</td>
                                    <td>{{ $invoice->ProcessCodeName }}</td>
                                    <td>{{ $invoice->ProcessedMethodName }}</td>
                                    <td>{{ $invoice->Note }}</td>
                                    @if($invoice->ProcessedMethod === "4")
                                    <td><a href="{{ route('dashboard.invoices.edit', [$invoice->InvoiceAPID, true]) }}"><img src="{{ asset('/assets/images/add-invoice.png') }}" /></a></td>
                                    @else
                                    <td><a href="{{ route('dashboard.invoices.show', $invoice->InvoiceAPID) }}"><i class="fa fa-eye"></i></a></td>
                                    @endif
                                    @can('edit')
                                    <td>
                                        <a href="#" data-confirmation-btn="true"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                    </td>

                                    <td data-confirmation-body colspan="10" class="text-left">

                                        <form style="display: none;" method="POST" action="{{ route('dashboard.invoices.destroy', $invoice->InvoiceAPID) }}">
                                            {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                            <input type="hidden" name="p" value="{{ session()->get('invoicesIndexRequest.page') }}" />
                                        </form>

                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                        Are you sure you want to delete it?

                                        <a href="" data-delete-form>Yes</a>
                                        <a href="#" data-confirmation-btn="false">No</a>
                                    </td>
                                    @endcan
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="10" class="no-highlight text-center">
                                        <h3 class="confirmation not-found">Sorry, no invoice found.</h3>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- end invoice-info -->
                {{ $invoices->appends(session()->get('invoicesIndexRequest'))->links() }}
            </div>
        </div>
    </div>
    <!-- end container -->
</div>
<!-- end main-container -->
@stop
