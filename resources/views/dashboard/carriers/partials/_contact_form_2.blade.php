<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('Name.'. $key) ? 'has-danger' : '' }}">
            <label for="Name{{$key}}" class="control-label">Contact Name</label>
            <input id="Name{{$key}}"
                   name="Name[{{$key}}]"
                   value="{{ $key ? old('Name')[$key] : old('Name[]') }}"
                   type="text"
                   class="form-control"
                   autocomplete="off">
            <span class="form-control-feedback">{{ $errors->first('Name.'. $key) }}</span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('Title.'. $key) ? 'has-danger' : '' }}">
            <label for="Title{{$key}}" class="control-label">Title</label>
            <input id="Title{{$key}}"
                   name="Title[{{$key}}]"
                   value="{{ $key ? old('Title')[$key] : old('Title[]') }}"
                   type="text"
                   class="form-control Title"
                   autocomplete="off">
            <span class="form-control-feedback">{{ $errors->first('Title.'. $key) }}</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('MobilePhoneNumber.'. $key) ? 'has-danger' : '' }}">
            <label for="MobilePhoneNumber{{$key}}" class="control-label">Mobile Phone #</label>
            <input id="MobilePhoneNumber{{$key}}"
                   value="{{ $key ? old('MobilePhoneNumber')[$key] : old('MobilePhoneNumber[]') }}"
                   name="MobilePhoneNumber[{{$key}}]"
                   type="text"
                   class="form-control"
                   autocomplete="off">
            <span class="form-control-feedback">{{ $errors->first('MobilePhoneNumber.'. $key) }}</span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('OfficePhoneNumber.'. $key) ? 'has-danger' : '' }}">
            <label for="OfficePhoneNumber{{$key}}" class="control-label">Office Phone #</label>
            <input id="OfficePhoneNumber{{$key}}"
                   name="OfficePhoneNumber[{{$key}}]"
                   value="{{ $key ? old('OfficePhoneNumber')[$key] : old('OfficePhoneNumber[]') }}"
                   type="text"
                   class="form-control"
                   autocomplete="off">
            <span class="form-control-feedback">{{ $errors->first('OfficePhoneNumber.'. $key) }}</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('EmailAddress.'. $key) ? 'has-danger' : '' }}">
            <label for="EmailAddress{{$key}}" class="control-label">Email</label>
            <input id="EmailAddress{{$key}}"
                   name="EmailAddress[{{$key}}]"
                   value="{{ $key ? old('EmailAddress')[$key] : old('EmailAddress[]') }}"
                   type="text"
                   class="form-control"
                   autocomplete="off">
            <span class="form-control-feedback">{{ $errors->first('EmailAddress.'. $key) }}</span>
        </div>
    </div>
</div>

<!---- End Create Contact ---->