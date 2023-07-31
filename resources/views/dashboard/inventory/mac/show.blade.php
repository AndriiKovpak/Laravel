@extends('layouts.dashboard')

@section('title', 'MAC Order # '.$BTNAccountMAC['OrderNum'].' - ACCT # '.$BTNAccount->getAttribute('AccountNum'))

@section('content')
<div class="main-container">
    <!-- start container -->
    <div class="container">
        <!-- start confirmation -->
        <div class="confirmation">

            <p>
                <a href="{{ route('dashboard.inventory.mac.index', [$BTNAccount]) }}" class="nav_button">&lt; Back</a>
            </p>

            <h3>Order # {{ $BTNAccountMAC['OrderNum'] }}</h3>

            <div class="clearfix"></div>

            <div class="tab-content mt-3">
                <div class="tab-pane active">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Type</label>
                                <div class="d-block">
                                    <span>@notdefined($BTNAccountMAC['Type'])</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Order #</label>
                                <div class="d-block">
                                    <span>@notdefined($BTNAccountMAC['OrderNum'])</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr />

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Carrier Order</label>
                                <div class="d-block">
                                    <span>@notdefined($BTNAccountMAC['CarrierOrder'])</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Description</label>
                                <div class="d-block">
                                    <span>@notdefined($BTNAccountMAC['Description'])</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr />

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Contact Name</label>
                                <div class="d-block">
                                    <span>@notdefined($BTNAccountMAC['ContactName'])</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Contact Phone</label>
                                <div class="d-block">
                                    <span>@notdefined($BTNAccountMAC['ContactPhone'])</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Ext.</label>
                                <div class="d-block">
                                    <span>@notdefined($BTNAccountMAC['ContactPhoneExt'])</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr />

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Contract Date</label>
                                <div class="d-block">@notdefined($BTNAccountMAC['ContractDate'], $BTNAccountMAC['ContractDate']->format('m/d/Y'))</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Contract Exp Date</label>
                                <div class="d-block">@notdefined($BTNAccountMAC['ContractExpDate'], $BTNAccountMAC['ContractExpDate']->format('m/d/Y'))</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        @if($MACType->isContractInfo())
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Request Contract Renewal Date</label>
                                <div class="d-block">@notdefined($BTNAccountMAC['RequestedContractRenewalDate'], $BTNAccountMAC['RequestedContractRenewalDate']->format('m/d/Y'))</div>
                            </div>
                        </div>
                        @endif

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Disconnect Date</label>
                                <div class="d-block">@notdefined($BTNAccountMAC['DisconnectDate'], $BTNAccountMAC['DisconnectDate']->format('m/d/Y'))</div>
                            </div>
                        </div>
                    </div>

                    @if($MACType->isContractInfo())
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Contract Term</label>
                                <div class="d-block">@notdefined($BTNAccountMAC['ContractTerm'])</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <hr />

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Entry Date</label>
                                <div class="d-block">@notdefined($BTNAccountMAC['Created_at'], $BTNAccountMAC['Created_at']->format('m/d/Y'))</div>
                            </div>
                        </div>

                        {{--<div class="col-md-6">--}}
                        {{--<div class="form-group">--}}
                        {{--<label class="d-block">Created by</label>--}}
                        {{--<div class="d-block">@notdefined($BTNAccountMAC['CreatedByUser'], $BTNAccountMAC['CreatedByUser']->getFullName())</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Edition Date</label>
                                <div class="d-block">@notdefined($BTNAccountMAC['Updated_at'], $BTNAccountMAC['Updated_at']->format('m/d/Y'))</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Edited by</label>
                                <div class="d-block">@notdefined($BTNAccountMAC['UpdatedByUser'], $BTNAccountMAC['UpdatedByUser']->getFullName())</div>
                            </div>
                        </div>
                    </div>

                    <hr />

                    @if($MACType->isGeneral())
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Requestor name</label>
                                <div class="d-block">@notdefined($BTNAccountMAC['RequestorName'])</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Carrier Due Date</label>
                                <div class="d-block">@notdefined($BTNAccountMAC['CarrierDueDate'], $BTNAccountMAC['CarrierDueDate']->format('m/d/Y'))</div>
                            </div>
                        </div>
                    </div>

                    <hr />
                    @endif

                    @if($MACType->isGeneral())
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Disconnect Request Date</label>
                                <div class="d-block">@notdefined($BTNAccountMAC['DisconnectRequestDate'], $BTNAccountMAC['DisconnectRequestDate']->format('m/d/Y'))</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label c lass="d-block">Disconnect Date</label>
                                <div class="d-block">@notdefined($BTNAccountMAC['DisconnectDate'], $BTNAccountMAC['DisconnectDate']->format('m/d/Y'))</div>
                            </div>
                        </div>
                    </div>

                    <hr />

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Final Credit Amount</label>
                                <div class="d-block">
                                    @if($BTNAccountMAC['FinalCreditAmount'])
                                    ${{ \App\Models\Util::formatCurrency($BTNAccountMAC['FinalCreditAmount'], true) }}
                                    @else
                                    <i class="text-muted">Not defined</i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr />
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="d-block">Notes</label>
                                {{-- {{dd($BTNAccountMAC->Notes);}} --}}
                                <div class="d-block">
                                    @if($BTNAccountMAC['Notes']->count())
                                    <ul class="ml-2">
                                        @foreach($BTNAccountMAC['Notes'] as $Note)
                                            @if($Note['Note'])
                                            <li><small class="text-muted">{{ $Note['Created_at']->format('m/d/Y') }} &bull;</small>
                                                <div class="break-word"> {{ $Note['Note']}} </div>
                                            </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                    @else
                                    <i class="text-muted">Not defined</i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end confirmation -->
    </div>
    <!-- end container -->
</div>
<!-- end main-container -->
@can('edit')
<div class="bottom-block">
    <div class="container">
        <div class="col-md-10 offset-md-1">
            <div class="row">
                <div class="col-md-12 col-xs-12 text-xs-center text-center">
                    <a href="{{ route('dashboard.inventory.mac.edit', [$BTNAccount, $BTNAccountMAC, Arr::get($BTNAccountMAC, 'Type', \App\Models\MACType::GENERAL)]) }}" class="btn-primary"><i class="fa fa-pencil"></i> EDIT</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan
<!-- end main-container -->
@endsection
