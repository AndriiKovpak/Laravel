@if(! is_array($BTNAccount))
<div class="row">
    <div class="col-md-6 mt-1">
        <div class="form-group {{ $errors->has('Status') ? 'has-danger': '' }}">
            <label for="Status" class="d-block">Status</label>
            <div class="d-block">
                <select id="Status" name="Status" class="form-control">
                    @foreach($_options['Status'] as $value => $title)
                    <option value="{{ $value }}" {{ $value == old('Status', Arr::get($BTNAccount, 'Status', 1)) ? 'selected' : '' }}>{{ $title }}</option>
                    @endforeach
                </select>
                <span class="form-text text-danger">{{ $errors->first('Status') }}</span>
            </div>
        </div>
    </div>
</div>

<hr>
@else
<input type="hidden" name="Status" value="{{ \App\Models\BTNStatusType::STATUS_ACTIVE }}" />
@endif

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('AccountNum') ? 'has-danger': '' }}">
            <label class="d-block" for="AccountNum">Account #</label>
            <div class="d-block">
                <input id="AccountNum" name="AccountNum" type="text" class="form-control" placeholder="Account #" value="{{ old('AccountNum', Arr::get($BTNAccount, 'AccountNum')) }}">
                <span class="form-text text-danger">{{ $errors->first('AccountNum') }}</span>
            </div>
        </div>
    </div>

    @if(! is_array($BTNAccount))
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('BTN') ? 'has-danger': '' }}">
            <label class="d-block" for="BTN">Billing Telephone #</label>
            <div class="d-block">
                <input id="BTN" name="BTN" type="text" class="form-control" placeholder="BTN" value="{{ old('BTN', Arr::get($BTNAccount, 'BTN')) }}">
                <span class="form-text text-danger">{{ $errors->first('BTN') }}</span>
            </div>
        </div>
    </div>
    @endif
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('CarrierID') ? 'has-danger': '' }}">
            <label class="d-block" for="CarrierID">Carrier</label>
            <div class="d-block">
                <select id="CarrierID" name="CarrierID" class="form-control">
                    <option selected disabled>Select Carrier</option>
                    @foreach($_options['CarrierID'] as $value => $title)
                    <option value="{{ $value }}" {{ $value == old('CarrierID', Arr::get($BTNAccount, 'CarrierID', -1)) ? 'selected' : '' }}>{{ $title }}</option>
                    @endforeach
                </select>
                <span class="form-text text-danger">{{ $errors->first('CarrierID') }}</span>
            </div>
        </div>
    </div>
</div>

<hr>

@include('partials._address-form', [
'type' => 'Site',
'title' => 'Site',
'AddressType' => \App\Models\AddressType::where('AddressTypeName', '=', 'Site Address')->first()['AddressType'],
'Address' => $Addresses,
])

<br>

<hr>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('DivisionDistrictID') ? 'has-danger': '' }}">
            <label class="d-block" for="DivisionDistrictID">District</label>
            <span class="d-block">
                <select id="DivisionDistrictID" name="DivisionDistrictID" class="form-control">
                    <option selected disabled>Select District</option>
                    @foreach($_options['DivisionDistrictID'] as $value => $title)
                    <option value="{{ $value }}" {{ $value == old('DivisionDistrictID', Arr::get($BTNAccount, 'DivisionDistrictID', -1)) ? 'selected' : '' }}>{{ $title }}</option>
                    @endforeach
                </select>
                <span class="form-text text-danger">{{ $errors->first('DivisionDistrictID') }}</span>
            </span>
        </div>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label class="d-block" for="Note">Notes</label>
            <span class="d-block">
                <textarea rows="2" class="form-control" name="Note" maxlength="4000" id="Note">{{ old('Note') }}</textarea>
            </span>
        </div>
    </div>
</div>
