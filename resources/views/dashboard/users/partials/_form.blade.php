{!! csrf_field() !!}
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('UserStatus') ? 'has-danger' : '' }}">
            <label for="UserStatus" class="control-label">Status</label>
            <select value="" name="UserStatus" id="UserStatus" type="text" class="form-control">
                @foreach($statuses as $status)
                @if(old('UserStatus', Arr::get($user, 'UserStatus')) == $status['UserStatus'])
                <option selected="selected" value="{{$status['UserStatus']}}">{{$status['UserStatusName']}}</option>
                @else
                <option value="{{$status['UserStatus']}}">{{$status['UserStatusName']}}</option>
                @endif
                @endforeach
            </select>
            <span class="form-control-feedback">{{ $errors->first('UserStatus') }}</span>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('FirstName') ? 'has-danger' : '' }}">
            <label for="FirstName" class="control-label">First Name</label>
            <input value="{{old('FirstName', Arr::get($user, 'FirstName'))}}" name="FirstName" id="FirstName" type="text" class="form-control">
            <span class="form-control-feedback">{{ $errors->first('FirstName') }}</span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('LastName') ? 'has-danger' : '' }}">
            <label for="LastName" class="control-label">Last Name</label>
            <input value="{{old('LastName', Arr::get($user, 'LastName'))}}" name="LastName" id="LastName" type="text" class="form-control">
            <span class="form-control-feedback">{{ $errors->first('LastName') }}</span>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('EmailAddress') ? 'has-danger' : '' }}">
            <label for="EmailAddress" class="control-label">Email</label>
            <input value="{{old('EmailAddress', Arr::get($user, 'EmailAddress'))}}" name="EmailAddress" id="EmailAddress" type="text" class="form-control">
            <span class="form-control-feedback">{{ $errors->first('EmailAddress') }}</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('PhoneNumber') ? 'has-danger' : '' }}">
            <label for="PhoneNumber" class="control-label">Phone</label>
            <input value="{{old('PhoneNumber', Arr::get($user, 'PhoneNumber'))}}" name="PhoneNumber" id="PhoneNumber" type="text" class="form-control">
            <span class="form-control-feedback">{{ $errors->first('PhoneNumber') }}</span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('PhoneExt') ? 'has-danger' : '' }}">
            <label for="PhoneExt" class="control-label">Ext</label>
            <input value="{{old('PhoneExt', Arr::get($user, 'PhoneExt'))}}" name="PhoneExt" id="PhoneExt" type="text" class="form-control">
            <span class="form-control-feedback">{{ $errors->first('PhoneExt') }}</span>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('SecurityGroup') ? 'has-danger' : '' }}">
            <label for="SecurityGroup" class="control-label">Users Group</label>
            <select value="" name="SecurityGroup" id="SecurityGroup" type="text" class="form-control">
                @foreach($securityGroup as $group)
                @if(old('SecurityGroup', Arr::get($user, 'SecurityGroup')) == $group['SecurityGroup'])
                <option selected="selected" value="{{$group['SecurityGroup']}}">{{$group['SecurityGroupName']}}</option>
                @else
                <option value="{{$group['SecurityGroup']}}">{{$group['SecurityGroupName']}}</option>
                @endif
                @endforeach
            </select>
            <span class="form-control-feedback">{{ $errors->first('SecurityGroup') }}</span>
        </div>
    </div>
</div>
@include('dashboard.users.partials.DivisionDistricts')
<hr>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('UserName') ? 'has-danger' : '' }}">
            <label for="UserName" class="control-label">User Name</label>
            <input value="{{old('UserName', Arr::get($user, 'UserName'))}}" name="UserName" id="UserName" type="text" class="form-control">
            <span class="form-control-feedback">{{ $errors->first('UserName') }}</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('Password') ? 'has-danger' : '' }}">
            <label for="Password" class="control-label">Password</label>
            <input value="{{old('Password')}}" name="Password" id="Password" type="password" type="text" class="form-control">
            <span class="form-control-feedback">{{ $errors->first('Password') }}</span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('newPassword') ? 'has-danger' : '' }}">
            <label for="newPassword" class="control-label">Retype New Password</label>
            <input value="{{old('newPassword')}}" name="newPassword" id="newPassword" type="password" type="text" class="form-control">
            <span class="form-control-feedback">{{ $errors->first('newPassword') }}</span>
        </div>
    </div>
</div>
@if(isset($user->UserID))
<div class="row">
    <div class="col-6 col-sm-4">
        <a href="{{route('dashboard.users.session',[ $user->UserID, 'edit'])}}"><i class="fa fa-undo fa-ac"></i>
            Reset Session</a>
    </div>
</div>
@endif
<br><br>
