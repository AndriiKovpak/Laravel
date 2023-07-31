<br>
<div class="row">
    <div class="col-md-12">
        <h2 class="common-heading" style="border-bottom:8px solid #cbcbcb; font-size:1.5em;">BTN Account</h2>
    </div>
</div>
<div class="row">
    <div class="col-md-6 mt-1">
        <div class="form-group {{ $errors->has('CarrierID') ? 'has-danger' : '' }}">
            <label class="d-block" for="CarrierID">Carrier</label>
            <div class="d-block">
                <select class="form-control" name="CarrierID" id="CarrierID">
                    <option disabled selected>- Select Carrier -</option>
                    @foreach($_options['CarrierID'] as $value => $title)
                    <option value="{{ $value }}" {{ $value == old('CarrierID',Arr::get($BTNAccount, 'CarrierID', -1)) ? 'selected' : '' }}>{{ $title }}</option>
                    @endforeach
                </select>
                <span class="text-danger form-text">{{ $errors->first('CarrierID') }}</span>
            </div>
        </div>
    </div>
</div>

@include('partials._address-form', [
'type' => 'Site',
'title' => 'Site',
'AddressType' => \App\Models\AddressType::where('AddressTypeName', '=', 'Site Address')->first()['AddressType'],
'Address' => $Addresses,
])

<hr />
<div class="row">
    <div class="col-md-6 mt-1">
        <div class="form-group {{ $errors->has('DivisionDistrictID') ? 'has-danger' : '' }}">
            <label class="d-block" for="DivisionDistrictID">District</label>
            <div class="d-block">
                <select class="form-control" name="DivisionDistrictID" id="DivisionDistrictID">
                    <option disabled selected>- Select District -</option>
                    @foreach($_options['DivisionDistrict'] as $value => $title)
                    <option value="{{ $value }}" {{ $value == old('DivisionDistrictID', Arr::get($BTNAccount, 'DivisionDistrictID')) ? 'selected' : '' }}>{{ $title }}</option>
                    @endforeach
                </select>
                <span class="text-danger form-text">{{ $errors->first('DivisionDistrictID') }}</span>
            </div>
        </div>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-12">
        <div class="form-group {{ $errors->has('Note') ? 'has-danger' : '' }}">
            <label class="d-block" for="Note">Note</label>
            <div class="d-block">
                @if($BTNAccount)
                @if(isset($Order['BTN']['Notes']))
                <ul class="mb-3">
                    @foreach($Order['BTN']['Notes'] as $Note)
                    <li><small class="text-muted">{{ $Note['Created_at']->format('m/d/Y') }} &bull;</small> {{ $Note->Note }}</li>
                    @endforeach
                </ul>
                @endif
                @endif
                <input type="text" id="Note" maxlength="4000" name="Note" class="form-control" value="{{ old('Note', Arr::get($Order, 'Note')) }}" />
                <span class="text-danger form-text">{{ $errors->first('Note') }}</span>
            </div>
        </div>
    </div>
</div>
