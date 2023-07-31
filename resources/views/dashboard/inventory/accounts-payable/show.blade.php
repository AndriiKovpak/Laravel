@extends('layouts.dashboard')

@section('title', 'Invoice # '.$AccountPayable['InvoiceNum'].' - ACCT # '.$BTNAccount->getAttribute('AccountNum'))

@section('content')
    <div class="main-container">
        <!-- start container -->
        <div class="container">
            <!-- start confirmation -->
            <div class="confirmation">

                <p>
                    <a href="{{ route('dashboard.inventory.accounts-payable.index', [$BTNAccount]) }}" class="nav_button">&lt; Back</a>
                </p>

                @if($AccountPayable->getKey() != $LastMonthAccountPayable->getKey())
                    <h3>Invoice # {{ $AccountPayable['InvoiceNum'] }}</h3>
                @else
                    <h3>Last Month Invoice</h3>
                @endif

                <hr />

                <div class="clearfix"></div>

                <div class="tab-content mt-3">
                    <div class="tab-pane active">
                        <form class="clearfix">
                                <div class="row">
                                    <div class="col-md-6 mt-1">
                                        <div class="form-group">
                                            <label class="d-block">Processed</label>
                                            <div class="d-block">
                                                <span>@notdefined($AccountPayable['ProcessedMethodType'])</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="d-block">Account #</label>
                                            <div class="d-block">
                                                <span>{{ $BTNAccount['AccountNum'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="d-block">Invoice #</label>
                                        <div class="d-block">
                                            <span>@notdefined($AccountPayable['InvoiceNum'])</span>

                                                @if($AccountPayable['ScannedImage'])
                                                    <a href="{{ route('dashboard.inventory.accounts-payable.document', [$BTNAccount, $AccountPayable]) }}" target="_blank"><i class="fa fa-file-picture-o"></i> View Image</a>
                                                @endif

                                                <br />
                                            @if($AccountPayable->getKey() != $LastMonthAccountPayable->getKey())
                                                <a href="{{ route('dashboard.inventory.accounts-payable.show', [$BTNAccount, $LastMonthAccountPayable, $AccountPayable->getKey(), $AccountPayable['InvoiceNum']]) }}"><i class="fa fa-file mt-2"></i> View last month</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr />

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="d-block">Bill Date</label>
                                        <div class="d-block">@notdefined($AccountPayable['BillDate'], $AccountPayable['BillDate']->format('m/d/Y'))</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="d-block">Due Date</label>
                                        <div class="d-block">@notdefined($AccountPayable['DueDate'], $AccountPayable['DueDate']->format('m/d/Y'))</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="d-block">Service From</label>
                                        <div class="d-block">@notdefined($AccountPayable['ServiceFromDate'], $AccountPayable['ServiceFromDate']->format('m/d/Y'))</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="d-block">Service To</label>
                                        <div class="d-block">@notdefined($AccountPayable['ServiceToDate'], $AccountPayable['ServiceToDate']->format('m/d/Y'))</div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                @if($AccountPayable->getKey() != $LastMonthAccountPayable->getKey())
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="d-block">Final Bill</label>
                                            <div class="d-block">
                                                <span>{{ $AccountPayable['IsFinalBill'] ? 'Yes' : 'No' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="d-block">Current Charges</label>
                                        <div class="d-block">
                                            <span>${{ \App\Models\Util::formatCurrency($AccountPayable['CurrentChargeAmount'], true) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="d-block">Past Due Amount</label>
                                        <div class="d-block">
                                            <span>${{ \App\Models\Util::formatCurrency($AccountPayable['PastDueAmount'], true) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="d-block">Credit</label>
                                        <div class="d-block">
                                            <span>${{ \App\Models\Util::formatCurrency($AccountPayable['CreditAmount'], true) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="d-block">Remittance Address</label>
                                        <div class="d-block">
                                            @include('partials._address', ['address' => $AccountPayable['RemittanceAddress']])
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="d-block">Note</label>
                                        <div class="d-block break-word">
                                            @notdefined($AccountPayable['Note'])
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <!-- end confirmation -->
        </div>
        <!-- end container -->
    </div>
    <!-- end main-container -->
        <div class="bottom-block">
            <div class="container">
                <div class="col-md-10 offset-md-1">
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-xs-center text-center">
                            <a href="{{ route('dashboard.inventory.accounts-payable.edit', [$BTNAccount, $AccountPayable]) }}" class="btn-primary"><i class="fa fa-pencil"></i> EDIT</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- end main-container -->
@endsection
