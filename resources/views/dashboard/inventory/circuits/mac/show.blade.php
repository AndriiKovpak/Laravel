@extends('layouts.dashboard')

@section('title', 'MAC Order # '.$CircuitMAC['OrderNum'].' - Circuit # '.$Circuit['CarrierCircuitID'].' - ACCT # '.$BTNAccount->getAttribute('AccountNum'))

@section('content')
    <div class="main-container inventory-circuits inventory-circuits-mac-edit">
        <div class="container">
            <a href="{{ route('dashboard.inventory.circuits.show', [$BTNAccount, $Circuit, 'tab' => 2, 'page' => $page, 'mac-page' => request('mac-page')]) }}" class="nav_button">&lt; Back </a>
            <br/>
            <br/>
            <div class="row">

                {{-- <div class="col-lg-4 mb-5">
                    @include('dashboard.inventory.circuits.partials._table')
                </div> --}}

                <div class="col-lg-12 circuit-container">

                    <div class="row circuit-content">

                        <div class="col-lg-12">
                            <strong>Circuit # {{ $Circuit['CarrierCircuitID'] }}</strong>
                            {{-- <a href="{{ route('dashboard.inventory.circuits.index', [$BTNAccount, $page]) }}" class="pull-right"><i class="fa fa-times"></i></a> --}}
                            <hr/>
                        </div>

                        <div class="col-lg-12">
                            <div>

                                <div class="card2">

                                    <div class="collapse show">
                                        <div class="card-block">

                                            <a href="{{ route('dashboard.inventory.circuits.show', [$BTNAccount, $Circuit, 'tab' => 2, 'page' => $page, 'mac-page' => request('mac-page')]) }}" class="">&lt; Back to MAC List</a>

                                            <h1 class="mt-1">MAC Order # {{ $CircuitMAC['OrderNum'] }}</h1>

                                            <hr/>


                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="d-block">Type</label>
                                                        <div class="d-block">
                                                            <span>@notdefined($CircuitMAC['Type'])</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="d-block">Order #</label>
                                                        <div class="d-block">
                                                            <span>@notdefined($CircuitMAC['OrderNum'])</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr/>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="d-block">Carrier Order</label>
                                                        <div class="d-block">
                                                            <span>@notdefined($CircuitMAC['CarrierOrder'])</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="d-block">Description</label>
                                                        <div class="d-block">
                                                            <span>@notdefined($CircuitMAC['Description'])</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr/>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="d-block">Contact Name</label>
                                                        <div class="d-block">
                                                            <span>@notdefined($CircuitMAC['ContactName'])</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="d-block">Contact Phone</label>
                                                        <div class="d-block">
                                                            <span>@notdefined($CircuitMAC['ContactPhone'])</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="d-block">Ext.</label>
                                                        <div class="d-block">
                                                            <span>@notdefined($CircuitMAC['ContactPhoneExt'])</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr/>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="d-block">Contract Date</label>
                                                        <div class="d-block">@notdefined($CircuitMAC['ContractDate'], $CircuitMAC['ContractDate']->format('m/d/Y'))
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="d-block">Contract Exp Date</label>
                                                        <div class="d-block">@notdefined($CircuitMAC['ContractExpDate'], $CircuitMAC['ContractExpDate']->format('m/d/Y'))
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">

                                                @if($MACType->isContractInfo())
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="d-block">Request Contract Renewal Date</label>
                                                            <div class="d-block">
                                                                @notdefined($CircuitMAC['RequestedContractRenewalDate'], $CircuitMAC['RequestedContractRenewalDate']->format('m/d/Y'))
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="d-block">Disconnect Date</label>
                                                        <div class="d-block">@notdefined($CircuitMAC['DisconnectDate'], $CircuitMAC['DisconnectDate']->format('m/d/Y'))
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if($MACType->isContractInfo())
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="d-block">Contract Term</label>
                                                            <div class="d-block">
                                                                @notdefined($CircuitMAC['ContractTerm'])
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <hr/>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="d-block">Entry Date</label>
                                                        <div class="d-block">@notdefined($CircuitMAC['Created_at'], $CircuitMAC['Created_at']->format('m/d/Y'))
                                                        </div>
                                                    </div>
                                                </div>

                                                {{--<div class="col-md-6">--}}
                                                {{--<div class="form-group">--}}
                                                {{--<label class="d-block">Created by</label>--}}
                                                {{--<div class="d-block">@notdefined($CircuitMAC['CreatedByUser'], $CircuitMAC['CreatedByUser']->getFullName())</div>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="d-block">Edition Date</label>
                                                        <div class="d-block">@notdefined($CircuitMAC['Updated_at'], $CircuitMAC['Updated_at']->format('m/d/Y'))
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="d-block">Edited by</label>
                                                        <div class="d-block">@notdefined($CircuitMAC['UpdatedByUser'], $CircuitMAC['UpdatedByUser']->getFullName())
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr/>

                                            @if($MACType->isGeneral())
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="d-block">Requestor name</label>
                                                            <div class="d-block">
                                                                @notdefined($CircuitMAC['RequestorName'])
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="d-block">Carrier Due Date</label>
                                                            <div class="d-block">
                                                                @notdefined($CircuitMAC['CarrierDueDate'], $CircuitMAC['CarrierDueDate']->format('m/d/Y'))
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr/>
                                            @endif

                                            @if($MACType->isGeneral())
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="d-block">Disconnect Request Date</label>
                                                            <div class="d-block">
                                                                @notdefined($CircuitMAC['DisconnectRequestDate'], $CircuitMAC['DisconnectRequestDate']->format('m/d/Y'))
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label c lass="d-block">Disconnect Date</label>
                                                            <div class="d-block">
                                                                @notdefined($CircuitMAC['DisconnectDate'], $CircuitMAC['DisconnectDate']->format('m/d/Y'))
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr/>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="d-block">Final Credit Amount</label>
                                                            <div class="d-block">
                                                                @if($CircuitMAC['FinalCreditAmount'])
                                                                    ${{ \App\Models\Util::formatCurrency($CircuitMAC['FinalCreditAmount'], true) }}
                                                                @else
                                                                    <i class="text-muted">Not defined</i>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr/>
                                            @endif

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="d-block">Notes</label>
                                                        <div class="d-block">
                                                            @if($CircuitMAC['Notes']->count())
                                                                <ul class="ml-2">
                                                                    @foreach($CircuitMAC['Notes'] as $Note)
                                                                        <li>
                                                                            <small class="text-muted">
                                                                                {{ $Note['Created_at']->format('m/d/Y') }} &bull;
                                                                            </small><div class="break-word"> {{ $Note }}</div></li>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
