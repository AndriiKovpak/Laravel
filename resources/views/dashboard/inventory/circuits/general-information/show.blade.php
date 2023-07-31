@extends('layouts.dashboard')

@section('title', 'General Information - Circuit # '.$Circuit['CarrierCircuitID'].' - ACCT # '.$BTNAccount->getAttribute('AccountNum'))

@section('content')
    <div class="main-container inventory-circuits">
        <div class="container">
            <a href="{{ route('dashboard.inventory.circuits.index', [$BTNAccount, 'page' => $page, 'search' => $search]) }}" class="nav_button">&lt; Back </a>
            <br/>
            <br/>
            <div class="row circuit-container">
                <div class="col-lg-4 mb-5">
                    @include('dashboard.inventory.circuits.partials._table')
                </div>

                <div class="col-lg-8 ">
                    <div class="row circuit-content">
                    <div class="row circuit-content">
                        <div class="col-lg-12 circuit-header-wrapper">
                            <strong class="title">Circuit # {{ $Circuit['CarrierCircuitID'] }}</strong>
                            <?php
                                $activeTab = request('tab') ?: '0';
                                $didPages = request('did-page') ?: '0';
                                $DIDQuery = request('DID') ?: null;
                                $macPages = request('mac-page') ?: '0';
                                $macPages = request('mac-page') ?: '0';
                                if(intval($didPages) > 0 || $DIDQuery) {
                                    $activeTab = '1'; // did page
                                }
                                if(intval($macPages) > 0) {
                                    $activeTab = '2';
                                }
                            ?>
                            <ul class="nav nav-tabs navbar-wrapper" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link btn-group-vertical @if($activeTab=='0') active @endif" data-toggle="tab" href="#general" role="tab"><span>Circuit Detail</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link  @if($activeTab=='1') active @endif" data-toggle="tab" href="#dids" role="tab"><span>DiDs</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link  @if($activeTab=='2') active @endif" data-toggle="tab" href="#mac-notes" role="tab"><span>MAC Notes</span></a>
                                </li>
                            </ul>
                            <hr />
                        </div>

                        <div class="col-lg-12">
                            <div class="tab-content circuit-tab-content">
                                <div class="tab-pane  @if($activeTab=='0') active @endif" id="general" role="tabpanel">

                                    <div class="collapse show" >
                                        <div class="card-block">

                                            <form>
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="d-block">Updated</label>
                                                            <div class="d-block">
                                                                @notdefined($Circuit['Updated_at'], $Circuit['Updated_at']->format('m/d/Y h:i A'))
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="d-block">Category</label>
                                                            <div class="d-block">
                                                                @notdefined($Circuit['Category'])
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="d-block">Status</label>
                                                            <div class="d-block">
                                                                @notdefined($Circuit['StatusType'])
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-6 col-md-3">
                                                        <div class="form-group">
                                                            <label class="d-block">Bill under BTN</label>
                                                            <div class="d-block">
                                                                @notdefined($Circuit['BillUnderBTN'])
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if(! $Circuit['Category']->isSatellite())
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="d-block">Disconnect Date</label>
                                                            <div class="d-block">
                                                                @notdefined($Circuit['DisconnectDate'], $Circuit['DisconnectDate']->format('m/d/Y'))
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="d-block">Start Date</label>
                                                            <div class="d-block">
                                                                @notdefined($Circuit['BillingStartDate'], $Circuit['BillingStartDate']->format('m/d/Y'))
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="d-block">Installation Date</label>
                                                            <div class="d-block">
                                                                @notdefined($Circuit['InstallationDT'], $Circuit['InstallationDT']->format('m/d/Y'))
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr />

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="d-block">Service Type</label>
                                                            <div class="d-block">
                                                                @notdefined($Circuit['Service'])
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @if(! $Circuit['Category']->isSatellite())
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="d-block">Bandwidth</label>
                                                                <div class="d-block">
                                                                    @notdefined($Circuit['CategoryData']['Description'])
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="d-block">Description</label>
                                                                <div class="d-block">
                                                                    @notdefined($Circuit['Description2'])
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>

                                                @if($Circuit['Category']->isVoice())
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="d-block">Circuit ID Phone</label>
                                                                <div class="d-block">
                                                                    @notdefined($Circuit['CarrierCircuitID'])
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="d-block">SPID/phone#1</label>
                                                                <div class="d-block">
                                                                    @notdefined($Circuit['CategoryData']['SPID_Phone1'])
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="d-block">SPID/phone#2</label>
                                                                <div class="d-block">
                                                                    @notdefined($Circuit['CategoryData']['SPID_Phone2'])
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <!-- <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="d-block">Email</label>
                                                                <div class="d-block">
                                                                    @notdefined($Circuit['CategoryData']['Email'])
                                                                </div>
                                                            </div>
                                                        </div> -->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="d-block">LD PIC</label>
                                                                <div class="d-block">
                                                                    @notdefined($Circuit['CategoryData']['LD_PIC'])
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="d-block">Telco</label>
                                                                <div class="d-block">
                                                                    @notdefined($Circuit['TelcoNum'])
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="d-block">SNOW Ticket</label>
                                                                <div class="d-block">
                                                                    @notdefined($Circuit['SNOWTicketNum'])
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <hr />

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="d-block">Point to no.</label>
                                                                <div class="d-block">
                                                                    @notdefined($Circuit['CategoryData']['PointToNumber'])
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                @elseif($Circuit['Category']->isData())

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="d-block">Circuit ID</label>
                                                                <div class="d-block">
                                                                    @notdefined($Circuit['CarrierCircuitID'])
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="d-block">Telco</label>
                                                                <div class="d-block">
                                                                    @notdefined($Circuit['TelcoNum'])
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="d-block">SNOW Ticket</label>
                                                                <div class="d-block">
                                                                    @notdefined($Circuit['SNOWTicketNum'])
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="d-block">Email</label>
                                                                <div class="d-block">
                                                                    @notdefined($Circuit['CategoryData']['Email'])
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> -->

                                                @elseif($Circuit['Category']->isSatellite())

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="d-block">Phone #</label>
                                                                <div class="d-block">
                                                                    @notdefined($Circuit['CarrierCircuitID'])
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="d-block">Name</label>
                                                                <div class="d-block">
                                                                    @notdefined($Circuit['CategoryData']['AssignedToName'])
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                @endif

                                                @if(! $Circuit['Category']->isSatellite())

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="d-block">ILEC ID 1</label>
                                                                <div class="d-block">
                                                                    @notdefined($Circuit['CategoryData']['ILEC_ID1'])
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="d-block">ILEC ID 2</label>
                                                                <div class="d-block">
                                                                    @notdefined($Circuit['CategoryData']['ILEC_ID2'])
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                @endif

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="d-block">Cost</label>
                                                            <div class="d-block">
                                                                @if($Circuit['Cost'])
                                                                    <span>${{ \App\Models\Util::formatCurrency($Circuit['Cost'], true) }}</span>
                                                                @else
                                                                    <i class="text-muted">Not defined</i>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="d-block">Notes</label>
                                                            <div class="d-block">
                                                                @if($Circuit['Notes']->count())
                                                                    <ul class="ml-2">
                                                                        @php($Note = $Circuit->Notes()->latest()->first())
                                                                        <li><small class="text-muted">{{ $Note['Created_at']->format('m/d/Y') }} &bull;</small> <div class="break-word">{{ $Note }}</div></li>
                                                                    </ul>
                                                                @else
                                                                    <i class="text-muted">Not defined</i>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if($Circuit['Category']->isData())
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="d-block">Handoff</label>
                                                            <div class="d-block">
                                                                @notdefined($Circuit['CategoryData']['Handoff'])
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="d-block">Dmarc</label>
                                                            <div class="d-block">
                                                                @notdefined($Circuit['CategoryData']['Dmarc'])
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                <hr />

                                                @if(! $Circuit['Category']->isSatellite())
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="d-block">Service Location</label>
                                                                <div class="d-block">
                                                                    @include('partials._address', ['address' => $Circuit['CategoryData']['ServiceAddress']])
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="d-block">Site Name</label>
                                                                <div class="d-block">
                                                                    @notdefined($Circuit['CategoryData']['ServiceAddress']['SiteName'])
                                                                </div>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>

                                                    <hr />
                                                    @if($Circuit['Category']->isData() || $Circuit['Category']->isVoice())
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="d-block">Site Code</label>
                                                                    <div class="d-block">
                                                                        @notdefined($Circuit['CategoryData']['QoS_CIR'])
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @if($Circuit['Category']->isData())
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="d-block">Port Speed</label>
                                                                    <div class="d-block">
                                                                        @notdefined($Circuit['CategoryData']['PortSpeed'])
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endif
                                                            <!-- <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="d-block">Mileage</label>
                                                                    <div class="d-block">
                                                                        @notdefined($Circuit['CategoryData']['Mileage'])
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="d-block">Network IP Address</label>
                                                                    <div class="d-block">
                                                                        @notdefined($Circuit['CategoryData']['NetworkIPAddress'])
                                                                    </div>
                                                                </div>
                                                            </div> -->
                                                        </div>
                                                    <hr />
                                                    @endif

                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="d-block">Location A</label>
                                                                <div class="d-block">
                                                                    @include('partials._address', ['address' => $Circuit['CategoryData']['LocationAAddress']])
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="d-block">Site Name</label>
                                                                <div class="d-block">
                                                                    @notdefined($Circuit['CategoryData']['LocationAAddress']['SiteName'])
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="d-block">Location Z</label>
                                                                <div class="d-block">
                                                                    @include('partials._address', ['address' => $Circuit['CategoryData']['LocationZAddress']])
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="d-block">Site Name</label>
                                                                <div class="d-block">
                                                                    @notdefined($Circuit['CategoryData']['LocationZAddress']['SiteName'])
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <hr />

                                                @endif

                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="d-block">District</label>
                                                            <div class="d-block">
                                                                @notdefined($Circuit['DivisionDistrict'])
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @if($Circuit['Category']->isSatellite())
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="d-block">Device Type</label>
                                                            <div class="d-block">
                                                                @notdefined($Circuit['CategoryData']['DeviceType'])
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="d-block">Device Make</label>
                                                            <div class="d-block">
                                                                @notdefined($Circuit['CategoryData']['DeviceMake'])
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>

                                                @if($Circuit['Category']->isSatellite())
                                                    <hr />

                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="d-block">Device Model</label>
                                                                <div class="d-block">
                                                                    @notdefined($Circuit['CategoryData']['DeviceModel'])
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="d-block">IMEI#/Device ID</label>
                                                                <div class="d-block">
                                                                    @notdefined($Circuit['CategoryData']['IMEI'])
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="d-block">SIM #</label>
                                                                <div class="d-block">
                                                                    @notdefined($Circuit['CategoryData']['SIM'])
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                @endif

                                                <hr />

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="d-block">Features/Cost</label>
                                                            <div class="d-block">
                                                                @if($Circuit['Features']->count())
                                                                    <table class="table">
                                                                        <tbody>
                                                                            @foreach($Circuit['Features'] as $CircuitFeature)
                                                                                <tr>
                                                                                    <td>{{ $CircuitFeature['Feature']['FeatureName'] }}</td>
                                                                                    <td>${{ \App\Models\Util::formatCurrency($CircuitFeature['FeatureCost'], true) }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                @else
                                                                    <i class="text-muted">Not defined</i>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane  @if($activeTab=='1') active @endif" id="dids" role="tabpanel">
                                    <div class="collapse show">
                                        <div class="card-block" data-id="{{request('search')}}">

                                            <form class="form-inline mb-3" method="GET" action="{{ route('dashboard.inventory.circuits.show', [$BTNAccount, $Circuit, 'page' => $page, 'search' => request('search')]) }}">
                                                <div class="form-group">
                                                    <input type="text" name="DID" class="form-control DID-search" value="{{ request('DID') }}" placeholder="Search DID">

                                                    <input type="hidden" name="page" value="{{ request('page')}}">

                                                    <input type="hidden" name="search" value="{{ request('search')}}">

                                                </div>
                                                <div class="form-group ml-2">
                                                    <button type="submit" class="btn btn-outline-primary btn-sm">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                </div>
                                                @can('edit')
                                                <div class="text-right" style="margin-left: auto">
                                                    <a href="#delete-range" data-toggle="collapse" class="col text-right">Delete DID Range</a>
                                                </div>
                                                <div>
                                                    <a href="{{ route('dashboard.inventory.circuits.dids.create', [$BTNAccount, $Circuit, 'page' => $page, 'search' => request('search'), 'DID' => request('DID'), 'did-page' => request('did-page')]) }}" class="form-group text-right">New DID</a>
                                                </div>
                                                @endcan
                                            </form>

                                            @can('edit')
                                                <form id="delete-range" class="collapse @if($errors->any()) show @endif" method="POST" action="{{ route('dashboard.inventory.circuits.dids.destroy', [$BTNAccount, $Circuit]) }}">

                                                    {!! csrf_field() !!}
                                                    {!! method_field('DELETE') !!}

                                                    <input type="hidden" value="range" name="Type" />

                                                    <input type="hidden" value="{{ $page }}" name="page" />
                                                    <input type="hidden" value="{{ request('did-page') }}" name="did-page" />

                                                    <h1>Delete DID Range</h1>
                                                    <hr />

                                                    <div class="row range-input">
                                                        <div class="col-md-6 mt-1">
                                                            <div class="form-group {{ $errors->has('DIDPrefix') ? 'has-danger': '' }}">
                                                                <label class="d-block control-label" for="DIDPrefix">DID Range Prefix</label>
                                                                <div class="d-block">
                                                                    <input type="text" id="DIDPrefix" name="DIDPrefix" class="form-control" value="{{ old('DIDPrefix') }}" />
                                                                    <span class="form-text text-danger">{{ $errors->first('DIDPrefix') }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mt-1 range-input">
                                                            <div class="form-group {{ $errors->has('DIDFrom') ? 'has-danger': '' }}">
                                                                <div class="d-block">
                                                                    <label class="d-block control-label" for="DIDFrom">DID Range Start</label>
                                                                    <input type="number" id="DIDFrom" min="0" max="9999" name="DIDFrom" class="form-control" value="{{ old('DIDFrom') }}" />
                                                                    <span class="form-text text-danger">{{ $errors->first('DIDFrom') }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mt-1 range-input">
                                                            <div class="form-group {{ $errors->has('DIDTo') ? 'has-danger': '' }}">
                                                                <div class="d-block">
                                                                    <label class="d-block control-label" for="DIDTo">DID Range End</label>
                                                                    <input type="number" id="DIDTo" min="0" max="9999" name="DIDTo" class="form-control" value="{{ old('DIDTo') }}" />
                                                                    <span class="form-text text-danger">{{ $errors->first('DIDTo') }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group pull-right">
                                                        <button type="submit" id="DeleteDIDsButton2" class="btn btn-sm btn-danger"><i class="fa fa-trash mr-2"></i>DELETE RANGE</button>
                                                    </div>

                                                    <div class="clearfix"></div>
                                                    <hr />
                                                </form>
                                            @endcan


                                            <form method="POST" action="{{ route('dashboard.inventory.circuits.dids.destroy', [$BTNAccount, $Circuit]) }}">

                                                {!! csrf_field() !!}
                                                {!! method_field('DELETE') !!}

                                                <input type="hidden" value="array" name="Type" />

                                                <input type="hidden" value="{{ $page }}" name="page" />
                                                <input type="hidden" value="{{ request('did-page') }}" name="did-page" />

                                                <div class="table-responsive">
                                                    <table class="table table-hovered table-stripped">
                                                        <thead>
                                                        <tr>
                                                            <th>DID</th>
                                                            <th>Note</th>
                                                            @can('edit')
                                                                <th>Edit</th>
                                                                <th>
                                                                    <input data-delete-toggle {{ $DIDs->count() > 0 ? '' : 'disabled="disabled"' }} type="checkbox" />
                                                                </th>
                                                            @endcan
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @forelse($DIDs as $DID)
                                                            <tr>
                                                                <td>{{ $DID['DID'] }}</td>
                                                                <td>{{ $DID['DIDNote'] }}</td>
                                                                @can('edit')
                                                                    <td><a href="{{ route('dashboard.inventory.circuits.dids.edit', [$BTNAccount, $Circuit, $DID, 'page' => $page, 'search' => request('search'), 'DID' => request('DID'), 'did-page' => request('did-page', 1)]) }}"><i class="fa fa-pencil"></i></a></td>
                                                                    <td>
                                                                        <input data-delete-did value="{{ $DID->getKey() }}" name="DID[]" type="checkbox" />
                                                                    </td>
                                                                @endcan
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="4" class="text-center no-highlight"><h3 class="not-found">No DIDs Found</h3></td>
                                                            </tr>
                                                        @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <hr />

                                                {!! $DIDs->appends([
                                                        'page' => $page,
                                                        'search' => request('search'),
                                                        'DID' => request('DID'),
                                                        'did-page' => request('did-page'),
                                                    ])->links() !!}

                                                @can('edit')
                                                    <div class="form-group pull-right">
                                                        <button type="submit"  id="DeleteDIDsButton" class="btn btn-sm btn-danger"><i class="fa fa-trash mr-2"></i>DELETE DID('s)</button>
                                                    </div>
                                                @endcan

                                            </form>

                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane  @if($activeTab=='2') active @endif" id="mac-notes" role="tabpanel">
                                    <div class="collapse show">
                                        <div class="card-block">

                                            @can('edit')
                                                <a href="{{ route('dashboard.inventory.circuits.mac.create', [$BTNAccount, $Circuit, $page, request('mac-page')]) }}" class="pull-right" style="position: absolute; right: 20px;">New MAC</a>
                                            @endcan

                                            <div class="table-responsive mt-4">
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Order #</th>
                                                            <th>Type</th>
                                                            <th>Description</th>
                                                            <th>Due Date</th>
                                                            <th>Created by</th>
                                                            <th>Created Date</th>
                                                            <th>Details</th>
                                                            @can('edit')
                                                            <th>Edit</th>
                                                            @endcan
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($CircuitMACs as $MACNote)
                                                            <tr>
                                                                <td>@notdefined($MACNote['OrderNum'])</td>
                                                                <td>@notdefined($MACNote['Type'])</td>
                                                                <td>@notdefined($MACNote['Description'])</td>
                                                                <td>@notdefined($MACNote['ContractExpDate'], $MACNote['ContractExpDate']->format('m/d/Y'))</td>
                                                                <td>@notdefined($MACNote['UpdatedByUser'], $MACNote['UpdatedByUser']->getFullName())</td>
                                                                <td>@notdefined($MACNote['Created_at'], $MACNote['Created_at']->format('m/d/Y'))</td>
                                                                <td>
                                                                    <a href="{{ route('dashboard.inventory.circuits.mac.show', [$BTNAccount, $Circuit, $MACNote, 'page' => $page, 'mac-page' => request('mac-page')]) }}">
                                                                        <i class="fa fa-eye fa-ac"></i>
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    <a href="{{ route('dashboard.inventory.circuits.mac.edit', [$BTNAccount, $Circuit, $MACNote, 'page' => $page, 'mac-page' => request('mac-page')]) }}" ><i class="fa fa-pencil fa-ac"></i></a>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="6" class="text-center no-highlight"><h3 class="not-found">No MAC Notes Found</h3></td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>

                                            {!! $CircuitMACs->appends(['page' => $page])->links() !!}

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
    @can('edit')
        <div class="bottom-block">
            <div class="container">
                <div class="col-md-10 offset-md-1">
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-xs-center text-center">
                            <a href="{{ route('dashboard.inventory.circuits.edit', ['inventory' => $BTNAccount, 'circuit' => $Circuit, 'page' => $page, 'search' => $search]) }}" class="btn-primary"><i class="fa fa-pencil"></i> EDIT</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection
