{!! csrf_field() !!}
<input type="hidden" name="page" value="{{ $page }}" />
<input type="hidden" name="CategoryID" value="{{ $Category->getKey() }}" />

<div class="row">
    <div class="col-md-3 mt-1">
        <div class="form-group {{ $errors->has('CategoryID') ? 'has-danger' : '' }}">
            <label class="d-block" for="CategoryID">Category</label>
            <div class="d-block">
                @if(is_array($Circuit))
                <select class="form-control" id="CategoryID" name="CategoryID" @if(isset($order)) data-href="{{route('dashboard.orders.create', [$BTNAccount, $page]) }}" @else data-href="{{route('dashboard.inventory.circuits.create', [$BTNAccount, $page]) }}" @endif>
                    @foreach($_options['Category'] as $value => $title)
                    <option value="{{ $value }}" {{ $value == old('CategoryID', Arr::get($Circuit, 'CategoryID', request('category', 1))) ? 'selected' : '' }}>{{ $title }}</option>
                    @endforeach
                </select>
                @else
                <input type="hidden" name="CategoryID" id="CategoryID" value="{{ $Circuit->getAttribute('CategoryID') }}" />
                {{ $Category }}
                @endif
            </div>
        </div>
    </div>
    @if(! $Category->isSatellite())
    <div class="col-md-3 mt-1">
        <div class="form-group {{ $errors->has('DisconnectDate') ? 'has-danger' : '' }}">
            <label class="d-block" for="DisconnectDate">Disconnect Date</label>
            <div class="d-block">
                <input type="date" id="DisconnectDate" class="form-control" name="DisconnectDate" value="{{ old('DisconnectDate', Arr::has($Circuit, 'DisconnectDate') ? $Circuit['DisconnectDate']->format('Y-m-d') : null) }}" />
                <span class="text-danger form-text">{{ $errors->first('DisconnectDate') }}</span>
            </div>
        </div>
    </div>
    @endif
    @if(!isset($order))
    <div class="col-md-6 mt-1">
        <div class="form-group">
            <label class="d-block" for="Status">Status</label>
            <div class="d-block">
                <select class="form-control" name="Status" id="Status">
                    <option disabled selected>- Select Status -</option>
                    @foreach($_options['Status'] as $value => $title)
                    <option value="{{ $value }}" {{ $value == old('Status', Arr::get($Circuit, 'Status', 1)) ? 'selected' : '' }}>{{ $title }}</option>
                    @endforeach
                </select>
                <span class="text-danger form-text">{{ $errors->first('Status') }}</span>
            </div>
        </div>
    </div>
    @endif
</div>

<div class="row">
    <div class="col-md-3 mt-1">
        <div class="form-group {{ $errors->has('BillingStartDate') ? 'has-danger' : '' }}">
            <label class="d-block" for="BillingStartDate">Start Date</label>
            <div class="d-block">
                <input type="date" id="BillingStartDate" class="form-control" name="BillingStartDate" value="{{ old('BillingStartDate', Arr::has($Circuit, 'BillingStartDate') ? $Circuit['BillingStartDate']->format('Y-m-d') : Carbon\Carbon::today()->format('Y-m-d')) }}" />
                <span class="text-danger form-text">{{ $errors->first('BillingStartDate') }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3 mt-1">
        <div class="form-group {{ $errors->has('InstallationDT') ? 'has-danger' : '' }}">
            <label class="d-block" for="InstallationDT">Installation Date</label>
            <div class="d-block">
                <input type="date" id="InstallationDT" class="form-control" name="InstallationDT" value="{{ old('InstallationDT', Arr::has($Circuit, 'InstallationDT') ? $Circuit['InstallationDT']->format('Y-m-d') : null) }}" />
                <span class="text-danger form-text">{{ $errors->first('InstallationDT') }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-6 mt-1">
        <div class="form-group {{ $errors->has('BillUnderBTN') ? 'has-danger' : '' }}">
            <label class="d-block" for="BillUnderBTN">Bill under BTN</label>
            <div class="d-block">
                <input type="text" maxlength="50" class="form-control" name="BillUnderBTN" id="BillUnderBTN" value="{{ old('BillUnderBTN', Arr::get($Circuit, 'BillUnderBTN')) }}" />
                <span class="text-danger form-text">{{ $errors->first('BillUnderBTN') }}</span>
            </div>
        </div>
    </div>
</div>

<hr>

<div class="row">
    @if(! $Category->isSatellite())
    <div class="col-md-6 mt-1">
        <div class="form-group {{ $errors->has('CarrierCircuitID') ? 'has-danger': '' }}">
            <label class="d-block" for="CarrierCircuitID">Circuit ID Phone</label>
            <div class="d-block">
                <div class="form-group">
                    <input type="text" maxlength="50" id="CarrierCircuitID" name="CarrierCircuitID" class="form-control" value="{{ old('CarrierCircuitID', Arr::get($Circuit, 'CarrierCircuitID')) }}">
                    <span class="text-danger form-text">{{ $errors->first('CarrierCircuitID') }}</span>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="col-md-3 mt-1">
        <div class="form-group {{ $errors->has('ServiceType') ? 'has-danger' : '' }}">
            <label class="d-block" for="ServiceType">Service Type</label>
            <div class="d-block">
                <select name="ServiceType" id="ServiceType" class="form-control">
                    <option selected disabled>- Select Service Type -</option>
                    @foreach($_options['ServiceType'] as $value => $title)
                    <option value="{{ $value }}" {{ $value == old('ServiceType', Arr::get($Circuit, 'ServiceType')) ? 'selected' : '' }}>{{ $title }}</option>
                    @endforeach
                </select>
                <span class="text-danger form-text">{{ $errors->first('ServiceType') }}</span>
            </div>
        </div>
    </div>

    @if(! $Category->isSatellite())
    <div class="col-md-3 mt-1">
        <div class="form-group {{ $errors->has('DescriptionID') ? 'has-danger' : '' }}">
            <label class="d-block" for="DescriptionID">Bandwidth</label>
            <div class="d-block">
                <select class="form-control" name="DescriptionID" id="DescriptionID">
                    <option selected value="">- Select Bandwidth -</option>
                    @foreach($_options['DescriptionID'] as $value => $title)
                    <option value="{{ $value }}" {{ old('DescriptionID', Arr::get($Circuit, 'CategoryData.DescriptionID')) == $value ? 'selected' : '' }}>{{ $title }}</option>
                    @endforeach
                </select>
                <span class="text-danger form-text">{{ $errors->first('DescriptionID') }}</span>
            </div>
        </div>
    </div>
    @endif
</div>

@if(! $Category->isSatellite())
<div class="row-fluid">
    <div class="mt-1">
        <div class="form-group {{ $errors->has('Description2') ? 'has-danger': '' }}">
            <label class="d-block" for="Description2">Description</label>
            <div class="d-block">
                <div class="form-group">
                    <input type="text" maxlength="50" id="Description2" name="Description2" class="form-control" value="{{ old('Description2', Arr::get($Circuit, 'Description2')) }}">
                    <span class="text-danger form-text">{{ $errors->first('Description2') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if(! $Category->isSatellite())
<div class="row-fluid">
    <div class="col-xs-12 mt-1" id="duplicate-alert-message">
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" id="close-duplicate-alert-message" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span><b>Warning:</b> A Circuit with that Circuit ID Phone already exists for this account.</span>
        </div>
    </div>
</div>
@endif


@if($Category->isVoice())

<div class="row">
    <div class="col-md-6 mt-1">
        <div class="form-group {{ $errors->has('SPID_Phone1') ? 'has-danger': '' }}">
            <label class="d-block" for="SPID_Phone1">SPID/phone#1</label>
            <div class="d-block">
                <input type="text" id="SPID_Phone1" name="SPID_Phone1" class="form-control" maxlength="50" value="{{ old('SPID_Phone1', Arr::get($Circuit, 'CategoryData.SPID_Phone1')) }}">
                <span class="text-danger form-text">{{ $errors->first('SPID_Phone1') }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-6 mt-1">
        <div class="form-group {{ $errors->has('SPID_Phone2') ? 'has-danger': '' }}">
            <label class="d-block" for="SPID_Phone2">SPID/phone#2</label>
            <div class="d-block">
                <input type="text" id="SPID_Phone2" name="SPID_Phone2" class="form-control" maxlength="50" value="{{ old('SPID_Phone2', Arr::get($Circuit, 'CategoryData.SPID_Phone2')) }}">
                <span class="text-danger form-text">{{ $errors->first('SPID_Phone2') }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- <div class="col-md-6 mt-1">
        <div class="form-group {{ $errors->has('Email') ? 'has-danger': '' }}">
            <label class="d-block" for="Email">Email</label>
            <div class="d-block">
                <input type="text" id="Email" name="Email" class="form-control" maxlength="50" value="{{ old('Email', Arr::get($Circuit, 'CategoryData.Email')) }}">
                <span class="text-danger form-text">{{ $errors->first('Email') }}</span>
            </div>
        </div>
    </div> -->
    <div class="col-md-3 mt-1">
        <div class="form-group {{ $errors->has('LD_PIC') ? 'has-danger': '' }}">
            <label class="d-block" for="LD_PIC">LD PIC</label>
            <div class="d-block">
                <input type="text" id="LD_PIC" name="LD_PIC" class="form-control" maxlength="50" value="{{ old('LD_PIC', Arr::get($Circuit, 'CategoryData.LD_PIC')) }}">
                <span class="text-danger form-text">{{ $errors->first('LD_PIC') }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 mt-1">
        <div class="form-group {{ $errors->has('PointToNumber') ? 'has-danger' : '' }}">
            <label class="d-block" for="PointToNumber">Point to no.</label>
            <div class="d-block">
                <input type="text" id="PointToNumber" name="PointToNumber" class="form-control" maxlength="50" value="{{ old('PointToNumber', Arr::get($Circuit, 'CategoryData.PointToNumber')) }}">
                <span class="text-danger form-text">{{ $errors->first('PointToNumber') }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 mt-1">
        <div class="form-group {{ $errors->has('TelcoNum') ? 'has-danger' : '' }}">
            <label class="d-block" for="TelcoNum">Telco Num</label>
            <div class="d-block">
                <input type="text" id="TelcoNum" name="TelcoNum" class="form-control" maxlength="50" value="{{ old('TelcoNum', Arr::get($Circuit, 'TelcoNum')) }}">
                <span class="text-danger form-text">{{ $errors->first('TelcoNum') }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 mt-1">
        <div class="form-group {{ $errors->has('SNOWTicketNum') ? 'has-danger' : '' }}">
            <label class="d-block" for="SNOWTicketNum">SNOW Ticket Num</label>
            <div class="d-block">
                <input type="text" id="SNOWTicketNum" name="SNOWTicketNum" class="form-control" maxlength="50" value="{{ old('SNOWTicketNum', Arr::get($Circuit, 'SNOWTicketNum')) }}">
                <span class="text-danger form-text">{{ $errors->first('SNOWTicketNum') }}</span>
            </div>
        </div>
    </div>
</div>
<hr>

@elseif($Category->isData())
<div class="row">
    <div class="col-md-3 mt-1">
        <div class="form-group {{ $errors->has('TelcoNum') ? 'has-danger' : '' }}">
            <label class="d-block" for="TelcoNum">Telco Num</label>
            <div class="d-block">
                <input type="text" id="TelcoNum" name="TelcoNum" class="form-control" maxlength="50" value="{{ old('TelcoNum', Arr::get($Circuit, 'TelcoNum')) }}">
                <span class="text-danger form-text">{{ $errors->first('TelcoNum') }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 mt-1">
        <div class="form-group {{ $errors->has('SNOWTicketNum') ? 'has-danger' : '' }}">
            <label class="d-block" for="SNOWTicketNum">SNOW Ticket Num</label>
            <div class="d-block">
                <input type="text" id="SNOWTicketNum" name="SNOWTicketNum" class="form-control" maxlength="50" value="{{ old('SNOWTicketNum', Arr::get($Circuit, 'SNOWTicketNum')) }}">
                <span class="text-danger form-text">{{ $errors->first('SNOWTicketNum') }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 mt-1">
        <div class="form-group">
            <label class="d-block" for="HandoffID">Handoff</label>
            <div class="d-block">
                <select class="form-control" name="HandoffID" id="HandoffID">
                    <option disabled selected>- Select Handoff -</option>
                    @foreach($_options['HandoffType'] as $value => $title)
                    <option value="{{ $value }}" {{ $value == old('HandoffID', Arr::get($Circuit, 'CategoryData.HandoffID', 1)) ? 'selected' : '' }}>{{ $title }}</option>
                    @endforeach
                </select>
                <span class="text-danger form-text">{{ $errors->first('Status') }}</span>
            </div>
        </div>
</div>
    <div class="col-md-3 mt-1">
        <div class="form-group {{ $errors->has('Dmarc') ? 'has-danger' : '' }}">
            <label class="d-block" for="Dmarc">Dmarc</label>
            <div class="d-block">
                <input type="text" id="Dmarc" name="Dmarc" class="form-control" maxlength="50" value="{{ old('CategoryData.Dmarc', Arr::get($Circuit, 'CategoryData.Dmarc')) }}">
                <span class="text-danger form-text">{{ $errors->first('Dmarc') }}</span>
            </div>
        </div>
    </div>
</div>
<!--
<div class="row">
    <div class="col-md-6 mt-1">
        <div class="form-group {{ $errors->has('Email') ? 'has-danger': '' }}">
            <label class="d-block" for="Email">Email</label>
            <div class="d-block">
                <input type="text" id="Email" name="Email" class="form-control" maxlength="50" value="{{ old('Email', Arr::get($Circuit, 'CategoryData.Email')) }}">
                <span class="text-danger form-text">{{ $errors->first('Email') }}</span>
            </div>
        </div>
    </div>
</div> -->
@endif

<div class="row">
    @if($Category->isSatellite())
    <div class="col-md-3 mt-1">
        <div class="form-group {{ $errors->has('CarrierCircuitID') ? 'has-danger': '' }}">
            <label class="d-block" for="CarrierCircuitID">Phone #</label>
            <div class="d-block">
                <input type="text" id="CarrierCircuitID" name="CarrierCircuitID" class="form-control" value="{{ old('CarrierCircuitID', Arr::get($Circuit, 'CarrierCircuitID')) }}">
                <span class="text-danger form-text">{{ $errors->first('CarrierCircuitID') }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 mt-1">
        <div class="form-group {{ $errors->has('AssignedToName') ? 'has-danger': '' }}">
            <label class="d-block" for="AssignedToName">Name</label>
            <div class="d-block">
                <input type="text" id="AssignedToName" name="AssignedToName" class="form-control" maxlength="50" value="{{ old('AssignedToName', Arr::get($Circuit, 'CategoryData.AssignedToName')) }}">
                <span class="text-danger form-text">{{ $errors->first('AssignedToName') }}</span>
            </div>
        </div>
    </div>
    @endif
    @if(! $Category->isSatellite())
    <div class="col-md-3 mt-1">
        <div class="form-group {{ $errors->has('ILEC_ID1') ? 'has-danger': '' }}">
            <label class="d-block" for="ILEC_ID1">ILEC ID 1</label>
            <div class="d-block">
                <input type="text" id="ILEC_ID1" name="ILEC_ID1" class="form-control" maxlength="50" value="{{ old('ILEC_ID1', Arr::get($Circuit, 'CategoryData.ILEC_ID1')) }}">
                <span class="text-danger form-text">{{ $errors->first('ILEC_ID1') }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 mt-1">
        <div class="form-group {{ $errors->has('ILEC_ID2') ? 'has-danger': '' }}">
            <label class="d-block" for="ILEC_ID2">ILEC ID 2</label>
            <div class="d-block">
                <input type="text" id="ILEC_ID2" name="ILEC_ID2" class="form-control" maxlength="50" value="{{ old('ILEC_ID2', Arr::get($Circuit, 'CategoryData.ILEC_ID2')) }}">
                <span class="text-danger form-text">{{ $errors->first('ILEC_ID2') }}</span>
            </div>
        </div>
    </div>
    @endif
    <div class="col-md-6 mt-1">
        <div class="form-group {{ $errors->has('Cost') ? 'has-danger': '' }}">
            <label class="d-block" for="Cost">Cost</label>
            <div class="d-block">
                <input id="Cost" name="Cost" class="form-control" value="{{ old('Cost', Arr::get($Circuit, 'Cost')) }}">
                <span class="text-danger form-text">{{ $errors->first('Cost') }}</span>
            </div>
        </div>
    </div>
</div>
@if($Category->isSatellite())
<div class="row-fluid">
    <div class="col-xs-12 mt-1" id="duplicate-alert-message">
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" id="close-duplicate-alert-message" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span><b>Warning:</b> A Circuit with that Phone # already exists for this account.</span>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-12 mt-1">
        <div class="form-group {{ $errors->has('Notes') ? 'has-danger' : '' }}">
            <label class="d-block" for="Notes">Notes</label>
            <div class="d-block">
                @if($Circuit)
                @php($Note = $Circuit->Notes()->latest()->first())
                <input type="text" id="Notes" maxlength="4000" name="Notes" class="form-control" value="{{ old('Notes', $Note['Note'] ?? '') }}">
                @else
                <input type="text" id="Notes" maxlength="4000" name="Notes" class="form-control" value="{{ old('Notes') }}">
                @endif
                <span class="text-danger form-text">{{ $errors->first('Notes') }}</span>
            </div>
        </div>
    </div>
</div>

<hr>

@if(! $Category->isSatellite())

    @include('partials._address-form', [
        'type' => 'Service',
        'title' => 'Service',
        'AddressType' => \App\Models\AddressType::where('AddressTypeName', '=', 'Service Address')->first()['AddressType'],
        'Address' => Arr::get($Circuit, 'CategoryData.ServiceAddress', Arr::get($BTNAccount, 'SiteAddress')),
    ])

    <hr>

    @if($Category->isData())
        <div class="row">
            <div class="col-md-3 mt-1">
                <div class="form-group {{ $errors->has('QoS_CIR') ? 'has-danger' : '' }}">
                    <label class="d-block" for="QoS_CIR">QoS/CIR</label>
                    <div class="d-block">
                        <input type="text" maxlength="50" id="QoS_CIR" name="QoS_CIR" class="form-control" value="{{ old('QoS_CIR', Arr::get($Circuit, 'CategoryData.QoS_CIR')) }}">
                        <span class="text-danger form-text">{{ $errors->first('QoS_CIR') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mt-1">
                <div class="form-group {{ $errors->has('PortSpeed') ? 'has-danger' : '' }}">
                    <label class="d-block" for="PortSpeed">Port Speed</label>
                    <div class="d-block">
                        <input type="text" maxlength="50" id="PortSpeed" name="PortSpeed" class="form-control" value="{{ old('PortSpeed', Arr::get($Circuit, 'CategoryData.PortSpeed')) }}">
                        <span class="text-danger form-text">{{ $errors->first('PortSpeed') }}</span>
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-3 mt-1">
                <div class="form-group {{ $errors->has('Mileage') ? 'has-danger' : '' }}">
                    <label class="d-block" for="Mileage">Mileage</label>
                    <div class="d-block">
                        <input type="text" maxlength="50" id="Mileage" name="Mileage" class="form-control" value="{{ old('Mileage', Arr::get($Circuit, 'CategoryData.Mileage')) }}">
                        <span class="text-danger form-text">{{ $errors->first('Mileage') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mt-1">
                <div class="form-group {{ $errors->has('NetworkIPAddress') ? 'has-danger' : '' }}">
                    <label class="d-block" for="NetworkIPAddress">Network IP Address</label>
                    <div class="d-block">
                        <div class="d-block">
                            <input type="text" maxlength="50" id="NetworkIPAddress" name="NetworkIPAddress" class="form-control" value="{{ old('NetworkIPAddress', Arr::get($Circuit, 'CategoryData.NetworkIPAddress')) }}">
                            <span class="text-danger form-text">{{ $errors->first('NetworkIPAddress') }}</span>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>


        <hr>
    @endif

    @include('partials._address-form', [
        'type' => 'LocationA',
        'title' => 'Location A',
        'AddressType' => \App\Models\AddressType::where('AddressTypeName', '=', 'Location A Address')->first()['AddressType'],
        'Address' => Arr::get($Circuit, 'CategoryData.LocationAAddress'),
    ])

    <hr>

    @include('partials._address-form', [
        'type' => 'LocationZ',
        'title' => 'Location Z',
        'AddressType' => \App\Models\AddressType::where('AddressTypeName', '=', 'Location Z Address')->first()['AddressType'],
        'Address' => Arr::get($Circuit, 'CategoryData.LocationZAddress'),
    ])

    <hr>
@endif

<div class="row">
    <div class="col-md-6 mt-1">
        <div class="form-group {{ $errors->has('DivisionDistrictID') ? 'has-danger': '' }}">
            <label class="d-block" for="DivisionDistrictID">District</label>
            <div class="d-block">
                <select name="DivisionDistrictID" id="DivisionDistrictID" class="form-control">
                    <option selected value="">- Select District -</option>
                    @foreach($_options['DivisionDistrict'] as $value => $title)
                    <option value="{{ $value }}" {{ old('DivisionDistrictID', Arr::get($Circuit, 'DivisionDistrictID')) == $value ? 'selected' : '' }}>{{ $title }}</option>
                    @endforeach
                </select>
                <span class="text-danger form-text">{{ $errors->first('DivisionDistrictID') }}</span>
            </div>
        </div>
    </div>
</div>

@if($Category->isSatellite())

<hr>

<div class="row">
    <div class="col-md-6 mt-1">
        <div class="form-group {{ $errors->has('DeviceType') ? 'has-danger': '' }}">
            <label class="d-block" for="DeviceType">Device Type</label>
            <div class="d-block">
                <input type="text" maxlength="50" name="DeviceType" id="DeviceType" class="form-control" value="{{ old('DeviceType', Arr::get($Circuit, 'CategoryData.DeviceType')) }}">
                <span class="text-danger form-text">{{ $errors->first('DeviceType') }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-6 mt-1">
        <div class="form-group {{ $errors->has('DeviceMake') ? 'has-danger': '' }}">
            <label class="d-block" for="DeviceMake">Device Make</label>
            <div class="d-block">
                <input type="text" maxlength="50" name="DeviceMake" id="DeviceMake" class="form-control" value="{{ old('DeviceMake', Arr::get($Circuit, 'CategoryData.DeviceMake')) }}">
                <span class="text-danger form-text">{{ $errors->first('DeviceMake') }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mt-1">
        <div class="form-group {{ $errors->has('DeviceModel') ? 'has-danger': '' }}">
            <label class="d-block" for="DeviceModel">Device Model</label>
            <div class="d-block">
                <input type="text" maxlength="50" name="DeviceModel" id="DeviceModel" class="form-control" value="{{ old('DeviceModel', Arr::get($Circuit, 'CategoryData.DeviceModel')) }}">
                <span class="text-danger form-text">{{ $errors->first('DeviceModel') }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mt-1">
        <div class="form-group {{ $errors->has('IMEI') ? 'has-danger': '' }}">
            <label class="d-block" for="IMEI">IMEI#/Device ID</label>
            <div class="d-block">
                <input type="text" maxlength="50" name="IMEI" id="IMEI" class="form-control" value="{{ old('IMEI', Arr::get($Circuit, 'CategoryData.IMEI')) }}">
                <span class="text-danger form-text">{{ $errors->first('IMEI') }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-6 mt-1">
        <div class="form-group {{ $errors->has('SIM') ? 'has-danger': '' }}">
            <label class="d-block" for="SIM">SIM #</label>
            <div class="d-block">
                <input type="text" maxlength="50" name="SIM" id="SIM" class="form-control" value="{{ old('SIM', Arr::get($Circuit, 'CategoryData.SIM')) }}">
                <span class="text-danger form-text">{{ $errors->first('SIM') }}</span>
            </div>
        </div>
    </div>
</div>
@endif


@if(count($_options['FeatureType']))
<hr />
<div class="row">
    <div class="col-md-12 mt-1">
        <div class="form-group">
            <label class="d-block">Features/Cost</label>
            <div id="FeatureRows">
                @foreach(old('CircuitFeatures', Arr::get($Circuit, 'Features', [])) as $index => $CircuitFeature)
                <div class="row FeatureRow">
                    <div class="col-8 form-group {{ $errors->has('CircuitFeatures.'.$index.'.FeatureType') ? 'has-danger': '' }}">
                        <label class="d-block" for="FeatureType{{ $index }}">Feature</label>
                        <div class="d-block">
                            <select name="CircuitFeatures[{{ $index }}][FeatureType]" id="FeatureType{{ $index }}" class="form-control FeatureType">
                                <option selected disabled value="">- Select Feature -</option>
                                @foreach($_options['FeatureType'] as $FeatureType => $FeatureName)
                                <option value="{{ $FeatureType }}" {{ Arr::get($CircuitFeature, 'FeatureType') == $FeatureType ? 'selected' : '' }}>
                                    {{ $FeatureName }}
                                </option>
                                @endforeach
                            </select>
                            <span class="text-danger form-text">
                                {{ $errors->first('CircuitFeatures.'.$index.'.FeatureType') }}
                            </span>
                        </div>
                    </div>
                    <div class="col-3 form-group {{ $errors->has('CircuitFeatures.'.$index.'.FeatureCost') ? 'has-danger': '' }}">
                        <label class="d-block" for="FeatureCost{{ $index }}">Cost</label>
                        <div class="d-block">
                            <input id="FeatureCost{{ $index }}" name="CircuitFeatures[{{ $index }}][FeatureCost]" class="form-control" value="{{ Arr::get($CircuitFeature, 'FeatureCost') }}">
                            <span class="text-danger form-text">{{ $errors->first('CircuitFeatures.'.$index.'.FeatureCost') }}</span>
                            <input type="hidden" id="CircuitFeatureID{{ $index }}" name="CircuitFeatures[{{ $index }}][CircuitFeatureID]" value="{{ Arr::get($CircuitFeature, 'CircuitFeatureID') }}" />
                        </div>
                    </div>
                    <div class="col-1 form-group text-center">
                        <div class="d-block">
                            &nbsp;
                        </div>
                        <div class="d-block">
                            <a href="#" class="deleteFeature">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
                {{-- This is copied to create new rows when Add Feature is clicked. --}}
                <div class="row FeatureRow d-none" id="FeatureTypeTemplate">
                    <div class="col-8 form-group">
                        <label class="d-block FeatureTypeLabel">Feature</label>
                        <div class="d-block">
                            <select class="form-control FeatureType">
                                <option selected disabled value="">- Select Feature -</option>
                                @foreach($_options['FeatureType'] as $FeatureType => $FeatureName)
                                <option value="{{ $FeatureType }}">
                                    {{ $FeatureName }}
                                </option>
                                @endforeach
                            </select>
                            <span class="text-danger form-text"></span>
                        </div>
                    </div>
                    <div class="col-3 form-group">
                        <label class="d-block FeatureCostLabel">Cost</label>
                        <div class="d-block">
                            <input class="form-control FeatureCost" value="">
                            <input type="hidden" id="CircuitFeatureID" value="" />
                        </div>
                    </div>
                    <div class="col-1 form-group text-center">
                        <div class="d-block">
                            &nbsp;
                        </div>
                        <div class="d-block">
                            <a href="#" class="deleteFeature">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-primary addFeature">Add Feature</button>
        </div>
    </div>
</div>
@endif

@if(!isset($order))
<div class="bottom-block">
    <div class="container">
        <div class="col-md-10 offset-md-1">
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    @if(! is_array($Circuit) && ! empty($Circuit))
                    <a href="{{ route('dashboard.inventory.circuits.show', [$BTNAccount, $Circuit, 'page' => $page]) }}" class="btn-secondary">CANCEL</a>
                    @else
                    <a href="{{ route('dashboard.inventory.circuits.index', [$BTNAccount, 'page' => $page]) }}" class="btn-secondary">CANCEL</a>
                    @endif

                    <input type="submit" value="save" style="width:113px;font-size:12px" class="btn-blue pull-right">
                </div>
            </div>
        </div>
    </div>
</div>
@endif
