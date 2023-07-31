<div class="carrier-details col-md-10 offset-md-1">

    {!! csrf_field() !!}

    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('CarrierName') ? 'has-danger' : '' }}">
                <label for="CarrierName" class="control-label">Carrier Name</label>
                <input id="CarrierName" name="CarrierName" value="{{ old('CarrierName', Arr::get($carrier, 'CarrierName')) }}" type="text" class="form-control">
                <span class="form-control-feedback">{{ $errors->first('CarrierName') }}</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('CarrierPhoneNum') ? 'has-danger' : '' }}">
                <label for="CarrierPhoneNum" class="control-label">Phone #</label>
                <input id="CarrierPhoneNum" name="CarrierPhoneNum" value="{{ old('CarrierPhoneNum', Arr::get($carrier,'CarrierPhoneNum')) }}" type="text" class="form-control phone-format-required" autocomplete="off">
                <span class="form-control-feedback">{{ $errors->first('CarrierPhoneNum') }}</span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('CarrierSupportPhoneNum') ? 'has-danger' : '' }}">
                <label for="CarrierSupportPhoneNum" class="control-label">Support Phone #</label>
                <input id="CarrierSupportPhoneNum" name="CarrierSupportPhoneNum" value="{{ old('CarrierSupportPhoneNum', Arr::get($carrier,'CarrierSupportPhoneNum')) }}" type="text" class="form-control" autocomplete="off">
                <span class="form-control-feedback">{{ $errors->first('CarrierSupportPhoneNum') }}</span>
            </div>
        </div>
    </div>

    @if(!$edit)
    <div class="NewCarrierContactTemplate" style="display:none;">
        <div class="carrier_contact">
            <hr>
            <div class="row">
                <div class="col-10 col-sm-11">
                    <h2>Carrier Contact</h2>
                </div>
                <div class="col-1" style="text-align:right;">
                    <a><i class="fa fa-times delete_contact" aria-hidden="true"></i></a>
                </div>
            </div>
            @include('dashboard.carriers.partials._contact_form_2')
        </div>
    </div>
    <div class="NewCarrierContact">
        @if(old('Name'))
        @foreach(old('Name') as $key => $val)
        @if(old('Name')[$key])
        <div class="carrier_contact">
            <hr>
            <div class="row">
                <div class="col-10 col-sm-11">
                    <h2>Carrier Contact</h2>
                </div>
                <div class="col-1" style="text-align:right;">
                    <a><i class="fa fa-times delete_contact" aria-hidden="true"></i></a>
                </div>
            </div>
            @include('dashboard.carriers.partials._contact_form_2')
        </div>
        @endif
        @endforeach
        @endif
    </div>
    <div class="row" style="margin-bottom:15%;">
        <div class="col-md-6 Add_New_Carrier_Contact_Button">
            <a><i class="fa fa-plus" aria-hidden="true"></i> Add Carrier Contact</a>
        </div>
    </div>
    @else
    <div style="margin-top:20%;"></div>
    @endif
</div>
