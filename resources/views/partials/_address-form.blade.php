@php ($isRemittance = $type == 'Remittance')
@php ($SiteName = $isRemittance ? 'RemittanceName' : 'SiteName')
<div class="row">
    <div class="col-md-6">
        @if(!$isRemittance)
            <div class="form-group {{ $errors->has($SiteName . $type) ? 'has-danger' : '' }}">
                <label for="{{ $SiteName . $type }}" class="d-block">{{ $title ?: $type }} Name</label>
                <input id="{{ $SiteName . $type }}"
                    name="{{ $SiteName . $type }}"
                    class="form-control"
                    type="text"
                    maxlength="100"
                    value="{{ old($SiteName . $type, $Address[$SiteName] ?? '')}}">
                <span class="text-danger form-text">{{ $errors->first($SiteName . $type) }}</span>
            </div>

            <input name="AddressType{{ $type }}" type="hidden" value="{{ $AddressType }}">
        @endif
    </div>
    <div class="col-md-6">
        <div id="{{ $type }}AddressExisting" {{ old($type . 'AddressType', 'existing') != 'existing' ? 'style=display:none;' : '' }}>
            <div class="form-group">
                <label for="{{ $type }}AddressSearch" class="d-block">{{ $title ?: $type }} Address</label>
                <input id="{{ $type }}AddressSearch"
                       name="{{ $type }}AddressSearch"
                       class="form-control"
                       data-address-search
                       {{ $isRemittance ? 'data-address-source=remittance' : '' }}
                       data-address-json="{{ $type }}AddressJSON"
                       data-address-string="{{ $type }}AddressString"
                       {{ $isRemittance ? 'data-address-remittance-id=RemittanceAddressID' : '' }}
                       data-address-type="{{ $type }}"
                       {{ !$isRemittance ? 'data-address-addresstype=' . $AddressType : '' }}
                       value="{{ old($type . 'AddressSearch') ?? $Address }}">
                <input id="{{ $type }}AddressJSON"
                       name="{{ $type }}AddressJSON"
                       type="hidden"
                       value="{{ old($type . 'AddressJSON', ($Address instanceof Illuminate\Database\Eloquent\Model) ? $Address->toJson() : '') }}">
                <input id="{{ $type }}AddressString"
                       name="{{ $type }}AddressString"
                       type="hidden"
                       value="{{ old($type . 'AddressString') ?? $Address}}">
                @if($isRemittance)
                    <input id="RemittanceAddressID"
                           name="RemittanceAddressID"
                           type="hidden"
                           value="{{ old('RemittanceAddressID', $Address['RemittanceAddressID'] ?? '') }}">
                @endif
                <span class="text-danger form-text">{{ $errors->first($type . 'AddressSearch') }}</span>
            </div>
        </div>
    </div>
</div>


<div id="{{ $type }}AddressNewOrUpdate" {{ old($type . 'AddressType', 'existing') == 'existing' ? 'style=display:none;' : '' }}>
    <div class="row">
        <div class="col-6 mt-1">
            <h2>{{ $title ?: $type }} Address</h2>
        </div>
        <div class="col-6 mt-1 text-right">
            <label>
                <i class="fa fa-times fa-ac address-x"></i>
                <input name="{{ $type }}AddressType"
                       class="d-none"
                       type="radio"
                       autocomplete="off"
                       {{ old($type . 'AddressType', 'existing') == 'existing' ? 'checked' : '' }}
                       value="existing">
            </label>
        </div>
    </div>
    @if($isRemittance)
        <div class="row">
            <div class="col-12 mt-1">
                <div class="form-group {{ $errors->has($SiteName . $type) ? 'has-danger' : '' }}">
                    <label for="{{ $SiteName . $type }}" class="d-block">{{ $type }} Name</label>
                    <input id="{{ $SiteName . $type }}"
                           name="{{ $SiteName . $type }}"
                           class="form-control"
                           type="text"
                           maxlength="100"
                           value="{{ old($SiteName . $type,  $Address[$SiteName] ?? '') }}">
                    <span class="text-danger form-text">{{ $errors->first($SiteName . $type) }}</span>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-6 mt-1">
            <div class="form-group {{ $errors->has('Address1' . $type) ? 'has-danger' : '' }}">
                <label for="Address1{{ $type }}" class="d-block">Address 1</label>
                <input id="Address1{{ $type }}"
                       name="Address1{{ $type }}"
                       class="form-control"
                       type="text"
                       maxlength="50"
                       value="{{ old('Address1' . $type, $Address['Address1'] ?? '') }}">
                <span class="text-danger form-text">{{ $errors->first('Address1'. $type)  }}</span>
            </div>
        </div>
        <div class="col-md-6 mt-1">
            <div class="form-group {{ $errors->has('Address2' . $type) ? 'has-danger' : '' }}">
                <label for="Address2{{ $type }}" class="d-block">Address 2</label>
                <input id="Address2{{ $type }}"
                       name="Address2{{ $type }}"
                       class="form-control"
                       type="text"
                       maxlength="50"
                       value="{{ old('Address2' . $type,  $Address['Address2'] ?? '') }}">
                <span class="text-danger form-text">{{ $errors->first('Address2' . $type) }}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mt-1">
            <div class="form-group {{ $errors->has('City') ? 'has-danger' : '' }}">
                <label for="City{{ $type }}" class="d-block">City</label>
                <input id="City{{ $type }}"
                       name="City{{ $type }}"
                       class="form-control"
                       type="text"
                       maxlength="50"
                       value="{{ old('City' . $type,   $Address['City'] ?? '') }}">
                <span class="text-danger form-text">{{ $errors->first('City'. $type) }}</span>
            </div>
        </div>

        <div class="col-md-6 mt-1">
            <div class="form-group {{ $errors->has('State' . $type) ? 'has-danger' : '' }}">
                <label for="State{{ $type }}" class="d-block">State</label>
                <select id="State{{ $type }}"
                        name="State{{ $type }}"
                        class="form-control">
                    <option value="">- Select State -</option>
                    @foreach($_options['State'] as $value => $title)
                        <option {{ old('State' . $type, $Address['State'] ?? '') == $value ? 'selected' : '' }}
                                value="{{ $value }}">
                            {{ $title }}
                        </option>
                    @endforeach
                </select>
                <span class="text-danger form-text">{{ $errors->first('State' . $type) }}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mt-1">
            <div class="form-group {{ $errors->has('Zip' . $type) ? 'has-danger' : '' }}">
                <label for="Zip{{ $type }}" class="d-block">Zip</label>
                <input id="Zip{{ $type }}"
                       name="Zip{{ $type }}"
                       class="form-control"
                       type="text"
                       maxlength="20"
                       value="{{ old('Zip'. $type, $Address['Zip'] ?? '') }}">
                <span class="text-danger form-text">{{ $errors->first('Zip'. $type) }}</span>
            </div>
        </div>
    </div>
</div>

<div id="{{ $type }}AddressButtons" {{ old($type . 'AddressType', 'existing') != 'existing' ? 'style=display:none;' : '' }}>
    {{-- Uncomment to re-enable update button
    <label id="{{ $type }}AddressButtonsUpdate" class="btn-primary" {{ old($type . 'AddressID', $Address[$isRemittance ? 'RemittanceAddressID' : 'AddressID']) == '' ? 'style=display:none' : '' }}>
        <input name="{{ $type }}AddressType"
               class="d-none"
               type="radio"
               autocomplete="off"
               {{ old($type . 'AddressType', 'existing') == 'update' ? 'checked' : '' }}
               value="update">
        Update Address
    </label>
    --}}
    <label class="btn-primary">
        <input name="{{ $type }}AddressType"
               class="d-none"
               type="radio"
               autocomplete="off"
               {{ old($type . 'AddressType', 'existing') == 'new' ? 'checked' : '' }}
               value="new">
        New Address
    </label>
</div>
