@extends('layouts.dashboard')

@section('title', 'Invoice #'.$invoice->InvoiceNum)

@section('content')
    <!-- start main-container -->
    <div class="main-container">
        <!-- start container -->
        <div class="container">
            <!-- start my-profile -->
            <div class="my-profile">
                <div class="col-md-10 offset-md-1">
                    <a href="{{ route('dashboard.invoices.index') }}" class="nav_button">&lt; Back</a>
                    <h2 class="common-heading second">Invoice # {{ $invoice->InvoiceNum }}</h2>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group clearfix">
                                <label class="d-block">Processed</label>
                                <div class="d-block">
                                    <span>{{$invoice->ProcessedMethodType->ProcessedMethodName}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group clearfix">
                                <label class="d-block">Account #</label>
                                <div class="d-block">
                                    <span>{{ $invoice->BTNAccount->AccountNum }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($invoice->ScannedImage && $invoice->ScannedImage->ProcessCode)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group clearfix">
                                <label class="d-block">Process Code</label>
                                <div class="d-block">
                                    <span>{{$invoice->ScannedImage->ProcessCode()->first()->ProcessCodeName}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group clearfix">
                                <label class="d-block">Invoice #</label>
                                <div class="d-block">
                                    <span>@notdefined($invoice->InvoiceNum,$invoice->InvoiceNum)</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group clearfix">
                                <label class="d-block">&nbsp;</label>
                                <div class="d-block">
                                  <span><a target="_blank"
                                           href="{{route('dashboard.invoices.scanned-images', $invoice->ScannedImage->ScannedImageID)}}"
                                           class="grey-color"><i class="fa fa-file-pdf-o"></i> View Image</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group clearfix">
                                <label class="d-block">&nbsp;</label>
                                <div class="d-block">
                                    <span><a data-target="#lastMonthModal" href="#lastMonthModal" data-toggle="modal"
                                             class="grey-color"><i
                                                    class="fa fa-file-pdf-o"></i> View last month</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group clearfix">
                                <label class="d-block">Bill Date</label>
                                <span class="d-block">@notdefined($invoice->BillDate,$invoice->BillDate->format('m/d/Y'))</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group clearfix">
                                <label class="d-block">Due Date</label>
                                <span class="d-block">@notdefined($invoice->DueDate,$invoice->DueDate->format('m/d/Y'))</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group clearfix">
                                <label class="d-block">Service From</label>
                                <span class="d-block">@notdefined($invoice->ServiceFromDate,$invoice->ServiceFromDate->format('m/d/Y'))</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group clearfix">
                                <label class="d-block">Service To</label>
                                <span class="d-block">@notdefined($invoice->ServiceToDate,$invoice->ServiceToDate->format('m/d/Y'))</span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group clearfix">
                                <label class="d-block">Final Bill</label>
                                <span class="d-block">
				        @if($invoice->IsFinalBill == 1) Yes @else No @endif
				</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group clearfix">
                                <label class="d-block">Current Charges</label>
                                <span class="d-block">${{ $invoice->CurrentChargeAmount ?: '0.00' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group clearfix">
                                <label class="d-block">Past Due Amount</label>
                                <span class="d-block">${{ $invoice->PastDueAmount ?: '0.00' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group clearfix">
                                <label class="d-block">Credit</label>
                                <span class="d-block">${{ $invoice->CreditAmount ?: '0.00' }}</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group clearfix">
                                <label class="d-block">Remittance Address</label>
                                <span class="d-block">
                                    @include('partials._address', ['address' => $invoice['RemittanceAddress']])
                                </span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group clearfix">
                                <label class="d-block">Note</label>
                                <span class="d-block break-word">
                                    @notdefined($invoice->Note,$invoice->Note)
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end my-profile -->
        </div>
        <!-- end container -->
        @can('edit')
            <div class="bottom-block">
                <div class="container">
                    <div class="col-md-10 offset-md-1">
                        <div class="row">
                            <div class="col-md-12 col-xs-12 text-center">
                                <a href="{{ route('dashboard.invoices.edit', [$invoice->InvoiceAPID]) }}"
                                   class="btn-primary"><i class="fa fa-pencil"></i> &nbsp;&nbsp;Edit</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        <!-- end container -->
    </div>

    <div class="modal fade last-month" id="lastMonthModal" tabindex="-1" role="dialog"
         aria-labelledby="reportsDownloadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <a href="#" class="close-btn pull-right" data-dismiss="modal" aria-label="Close">&times;</a>
            <div class="clearfix"></div>
            <div class="modal-header text-xs-left">LAST MONTH</div>
            <div class="modal-content">
                <div class="row">
                    <div class="col-md-12">
                        @if ($lastMonthInvoice)
                            <div class="col-md-12" method="get">
                                <div>
                                    <label for="">Invoice #:</label>
                                    <span>{{ $lastMonthInvoice->Invoice }}</span>
                                </div>
                                <div>
                                    <label for="">Bill Date:</label>
                                    <span>{{ $lastMonthInvoice->BillDate ? $lastMonthInvoice->BillDate->format('m/d/Y') : '' }}</span>
                                </div>
                                <div>
                                    <label for="">Due Date:</label>
                                    <span>{{ $lastMonthInvoice->DueDate ? $lastMonthInvoice->DueDate->format('m/d/Y') : '' }}</span>
                                </div>
                                <div>
                                    <label for="">Service From:</label>
                                    <span>{{ $lastMonthInvoice->ServiceFromDate ? $lastMonthInvoice->ServiceFromDate->format('m/d/Y') : '' }}</span>
                                </div>
                                <div>
                                    <label for="">Service To:</label>
                                    <span>{{ $lastMonthInvoice->ServiceToDate ? $lastMonthInvoice->ServiceToDate->format('m/d/Y') : '' }}</span>
                                </div>
                                <div>
                                    <label for="">Current Changes:</label>
                                    <span>${{ $lastMonthInvoice->CurrentChargeAmount }}</span>
                                </div>
                                <div>
                                    <label for="">Past Due Amount:</label>
                                    <span>${{ $lastMonthInvoice->PastDueAmount }}</span>
                                </div>
                                <div>
                                    <label for="">Credit:</label>
                                    <span>${{ $lastMonthInvoice->CreditAmount }}</span>
                                </div>
                                <div>
                                    <label for="">Remittance Address:</label>
                                    <div>@include('partials._address', ['address' => $lastMonthInvoice['RemittanceAddress']])</div>
                                </div>
                            </div>
                            @else
                            <h3 style="font-weight:bold; font-size:1.2em; padding:25px 15px 15px 15px" class="center"> NO DATA FOR LAST MONTH INVOICE.</h3>
                        @endif
                        <div class="form-group">
                            <button type="button" class="btn-primary pull-right" data-dismiss="modal">CLOSE</button>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <!-- end modal-content -->
        </div>
    </div>
    <!-- end main-container -->
@stop
