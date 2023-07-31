<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('Name') ? 'has-danger' : '' }}">
            <label for="Name" class="control-label">Contact Name</label>
            <input value="{{old('Name', Arr::get($Contact, 'Name'))}}" id="Name" name="Name" type="text" class="form-control" autocomplete="off">
            <span class="form-control-feedback">{{ $errors->first('Name') }}</span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('Title') ? 'has-danger' : '' }}">
            <label for="Title" class="control-label">Title</label>
            {{--<span id="show-hide"><img src="{{ asset('/assets/images/password-ico.png') }}"></span>--}}
            <input value="{{old('Title', Arr::get($Contact,'Title'))}}" name="Title" value="" id="Title" type="text" class="form-control Title" autocomplete="off">
            <span class="form-control-feedback">{{ $errors->first('Title') }}</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('MobilePhoneNumber') ? 'has-danger' : '' }}">
            <label for="MobilePhoneNumber" class="control-label">Mobile Phone #</label>
            <input value="{{old('MobilePhoneNumber',Arr::get($Contact, 'MobilePhoneNumber'))}}" id="MobilePhoneNumber" name="MobilePhoneNumber" type="text" class="form-control" autocomplete="off">
            <span class="form-control-feedback">{{ $errors->first('MobilePhoneNumber') }}</span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('OfficePhoneNumber') ? 'has-danger' : '' }}">
            <label for="OfficePhoneNumber" class="control-label">Office Phone #</label>
            <input value="{{old('OfficePhoneNumber',Arr::get($Contact, 'OfficePhoneNumber'))}}" id="OfficePhoneNumber" name="OfficePhoneNumber" type="text" class="form-control" autocomplete="off">
            <span class="form-control-feedback">{{ $errors->first('OfficePhoneNumber') }}</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('EmailAddress') ? 'has-danger' : '' }}">
            <label for="EmailAddress" class="control-label">Email</label>
            <input value="{{old('EmailAddress', Arr::get($Contact,'EmailAddress'))}}" id="EmailAddress" name="EmailAddress" type="text" class="form-control" autocomplete="off">
            <span class="form-control-feedback">{{ $errors->first('EmailAddress') }}</span>
        </div>
    </div>
</div>
