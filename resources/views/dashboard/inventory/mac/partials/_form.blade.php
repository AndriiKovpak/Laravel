<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('MACType') ? 'has-danger': '' }}">
            <label class="d-block" for="MACType">Type</label>
            <div class="d-block">
                <select name="MACType" id="MACType" class="form-control" data-href="{{ request()->url() }}">
                    <option disabled selected>- Select MAC Type -</option>
                    @foreach($_options['MACType'] as $value => $title)
                    <option value="{{ $value }}" {{ old('MACType', $MACType->getKey()) == $value ? 'selected' : '' }}>{{ $title }}</option>
                    @endforeach
                </select>
                <span class="text-danger form-text">{{ $errors->first('MACType') }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('OrderNum') ? 'has-danger': '' }}">
            <label class="d-block" for="OrderNum">Order #</label>
            <div class="d-block">
                <input type="text" id="OrderNum" name="OrderNum" value="{{ old('OrderNum', Arr::get($BTNAccountMAC, 'OrderNum')) }}" class="form-control" maxlength="50">
                <span class="text-danger form-text">{{ $errors->first('OrderNum') }}</span>
            </div>
        </div>
    </div>
</div>

<hr />

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('CarrierOrder') ? 'has-danger': '' }}">
            <label class="d-block" for="CarrierOrder">Carrier Order</label>
            <div class="d-block">
                <input type="text" id="CarrierOrder" name="CarrierOrder" class="form-control" maxlength="50" value="{{ old('CarrierOrder', Arr::get($BTNAccountMAC, 'CarrierOrder')) }}">
                <span class="text-danger form-text">{{ $errors->first('CarrierOrder') }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group {{ $errors->has('Description') ? 'has-danger' : '' }}">
            <label class="d-block" for="Description">Description</label>
            <div class="d-block">
                <textarea type="text" id="Description" name="Description" class="form-control" maxlength="1000" >{{ old('Description', Arr::get($BTNAccountMAC, 'Description')) }}</textarea>
                <span class="text-danger form-text">{{ $errors->first('Description') }}</span>
            </div>
        </div>
    </div>
</div>

<hr />

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('ContactName') ? 'has-danger' : '' }}">
            <label class="d-block" for="ContactName">Contact Name</label>
            <input type="text" id="ContactName" name="ContactName" maxlength="50" class="form-control" value="{{ old('ContactName', Arr::get($BTNAccountMAC, 'ContactName')) }}">
            <span class="text-danger form-text">{{ $errors->first('ContactName') }}</span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('ContactPhone') ? 'has-danger' : '' }}">
            <label class="d-block" for="ContactPhone">Contact Phone</label>
            <input type="text" id="ContactPhone" name="ContactPhone" maxlength="20" class="form-control" value="{{ old('ContactPhone', Arr::get($BTNAccountMAC, 'ContactPhone')) }}">
            <span class="text-danger form-text">{{ $errors->first('ContactPhone') }}</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('ContactPhoneExt') ? 'has-danger' : '' }}">
            <label class="d-block" for="ContactPhoneExt">Ext</label>
            <input type="text" id="ContactPhoneExt" name="ContactPhoneExt" maxlength="20" class="form-control" value="{{ old('ContactPhoneExt', Arr::get($BTNAccountMAC, 'ContactPhoneExt')) }}">
            <span class="text-danger form-text">{{ $errors->first('ContactPhoneExt') }}</span>
        </div>
    </div>
</div>

<hr />

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('ContractDate') ? 'has-danger' : '' }}">
            <label class="d-block" for="ContractDate">Contract Date</label>
            <div class="d-block">
                <input type="date" id="ContractDate" name="ContractDate" class="form-control" value="{{ old('ContractDate', Arr::has($BTNAccountMAC, 'ContractDate') ? $BTNAccountMAC['ContractDate']->format('Y-m-d') : null) }}">
                <span class="text-danger form-text">{{ $errors->first('ContractDate') }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('ContractExpDate') ? 'has-danger' : '' }}">
            <label class="d-block" for="ContractExpDate">Contract Exp Date</label>
            <div class="d-block">
                <input type="date" id="ContractExpDate" name="ContractExpDate" class="form-control" value="{{ old('ContractExpDate', Arr::has($BTNAccountMAC, 'ContractExpDate') ? $BTNAccountMAC['ContractExpDate']->format('Y-m-d') : null) }}">
                <span class="text-danger form-text">{{ $errors->first('ContractExpDate') }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row">

    @if($MACType->isContractInfo())
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('RequestedContractRenewalDate') ? 'has-danger' : '' }}">
            <label class="d-block" for="RequestedContractRenewalDate">Request Contract Renewal Date</label>
            <div class="d-block">
                <input type="date" id="RequestedContractRenewalDate" name="RequestedContractRenewalDate" class="form-control" value="{{ old('RequestedContractRenewalDate', Arr::has($BTNAccountMAC, 'RequestedContractRenewalDate') ? $BTNAccountMAC['RequestedContractRenewalDate']->format('Y-m-d') : null) }}">
                <span class="text-danger form-text">{{ $errors->first('RequestedContractRenewalDate') }}</span>
            </div>
        </div>
    </div>
    @endif
    @if($MACType->isGeneral())
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('DisconnectRequestDate') ? 'has-danger' : '' }}">
            <label class="d-block" for="DisconnectRequestDate">Disconnect Request Date</label>
            <div class="d-block">
                <input type="date" id="DisconnectRequestDate" name="DisconnectRequestDate" class="form-control" value="{{ old('DisconnectRequestDate', Arr::has($BTNAccountMAC, 'DisconnectRequestDate') ? $BTNAccountMAC['DisconnectRequestDate']->format('Y-m-d') : null) }}">
                <span class="text-danger form-text">{{ $errors->first('DisconnectRequestDate') }}</span>
            </div>
        </div>
    </div>
    @endif

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('DisconnectDate') ? 'has-danger' : '' }}">
            <label class="d-block" for="DisconnectDate">Disconnect Date</label>
            <div class="d-block">
                <input type="date" id="DisconnectDate" name="DisconnectDate" class="form-control" value="{{ old('DisconnectDate', Arr::has($BTNAccountMAC, 'DisconnectDate') ? $BTNAccountMAC['DisconnectDate']->format('Y-m-d') : null) }}">
                <span class="text-danger form-text">{{ $errors->first('DisconnectDate') }}</span>
            </div>
        </div>
    </div>
</div>

@if($MACType->isContractInfo())
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="d-block" for="ContractTerm">Contract Term</label>
            <div class="d-block">
                <input type="text" id="ContractTerm" name="ContractTerm" maxlength="50" class="form-control" value="{{ old('ContractTerm', Arr::get($BTNAccountMAC, 'ContractTerm')) }}">
                <span class="text-danger form-text">{{ $errors->first('ContractTerm') }}</span>
            </div>
        </div>
    </div>
</div>
@endif

<hr />

@if($MACType->isGeneral())
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="d-block" for="RequestorName">Requestor name</label>
            <div class="d-block">
                <input type="text" id="RequestorName" name="RequestorName" maxlength="50" class="form-control" value="{{ old('RequestorName', Arr::get($BTNAccountMAC, 'RequestorName')) }}">
                <span class="text-danger form-text">{{ $errors->first('RequestorName') }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label class="d-block" for="CarrierDueDate">Carrier Due Date</label>
            <div class="d-block">
                <input type="date" id="CarrierDueDate" name="CarrierDueDate" class="form-control" value="{{ old('CarrierDueDate', Arr::has($BTNAccountMAC, 'CarrierDueDate') ? $BTNAccountMAC['CarrierDueDate']->format('Y-m-d') : null) }}">
                <span class="text-danger form-text">{{ $errors->first('CarrierDueDate') }}</span>
            </div>
        </div>
    </div>
</div>

<hr />
@endif

@if($MACType->isGeneral())
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="d-block" for="FinalCreditAmount">Final Credit Amount</label>
            <div class="d-block">
                <input id="FinalCreditAmount" name="FinalCreditAmount" class="form-control" value="{{ old('FinalCreditAmount', Arr::get($BTNAccountMAC, 'FinalCreditAmount')) }}">
                <span class="text-danger form-text">{{ $errors->first('FinalCreditAmount') }}</span>
            </div>
        </div>
    </div>
</div>

<hr />
@endif

<div class="row">
    <div class="col-md-12">
        <div class="form-group {{ $errors->has('Note') ? 'has-danger' : '' }}">
            <label class="d-block" for="Note">Note</label>
            <div class="d-block">
                <textarea type="text" id="Note" maxlength="4000" name="Note" class="form-control" value="{{ old('Note') }}" ></textarea>
                <span class="text-danger form-text">{{ $errors->first('Note') }}</span>
            </div>
        </div>
    </div>
</div>
