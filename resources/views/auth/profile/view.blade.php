@extends('layouts.dashboard')

@section('title', 'My Profile')

@section('content')
    <div class="main-container">
        <!-- start container -->
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <h2 class="common-heading">MY PROFILE</h2>

                    <!-- start my-profile -->
                    <div class="my-profile">
                        <form class="col-md-10 offset-md-1" id="user_form" method="post" action="{{ route('auth.profile.update') }}">

                            {!! csrf_field() !!}

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('FirstName') ? 'has-danger' : '' }}">
                                        <label for="FirstName" class="control-label">First Name</label>
                                        <input name="FirstName" id="FirstName" type="text" value="{{ old('FirstName', $user->FirstName) }}" class="form-control">
                                        <span class="form-control-feedback">{{ $errors->first('FirstName') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('LastName') ? 'has-danger' : '' }}">
                                        <label for="LastName" class="control-label">Last Name</label>
                                        <input name="LastName" id="LastName" type="text" value="{{ old('LastName', $user->LastName) }}" class="form-control">
                                        <span class="form-control-feedback">{{ $errors->first('LastName') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('EmailAddress') ? 'has-danger' : '' }}">
                                        <label for="EmailAddress" class="control-label">Email</label>
                                        <input name="EmailAddress" id="EmailAddress" type="email" value="{{ old('EmailAddress', $user->EmailAddress) }}" class="form-control">
                                        <span class="form-control-feedback">{{ $errors->first('EmailAddress') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group {{ $errors->has('PhoneNumber') ? 'has-danger' : '' }}">
                                        <label for="PhoneNumber" class="control-label">Phone #</label>
                                        <input name="PhoneNumber" id="PhoneNumber" type="text" value="{{ old('PhoneNumber', $user->PhoneNumber) }}" class="form-control">
                                        <span class="form-control-feedback">{{ $errors->first('PhoneNumber') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group {{ $errors->has('PhoneExt') ? 'has-danger' : '' }}">
                                        <label for="PhoneExt" class="control-label">Ext.</label>
                                        <input name="PhoneExt" id="PhoneExt" type="text" value="{{ old('PhoneExt', $user->PhoneExt) }}" class="form-control">
                                        <span class="form-control-feedback">{{ $errors->first('PhoneExt') }}</span>
                                    </div>
                                </div>
                            </div>

                            <hr />

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('UserName') ? 'has-danger' : '' }}">
                                        <label for="UserName" class="control-label">Username</label>
                                        <input autocomplete="false" id="UserName" name="UserName" type="text" value="{{ old('UserName', $user->UserName) }}" class="form-control" autocomplete="off">
                                        <span class="form-control-feedback">{{ $errors->first('UserName') }}</span>
                                        <span class="form-text text-muted">NOTE: Your email will be your username by default. But, you can change it anytime.</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('Password') ? 'has-danger' : '' }}">
                                        <label for="Password" class="control-label">Password</label>
                                        {{--<span id="show-hide"><img src="{{ asset('/assets/images/password-ico.png') }}"></span>--}}
                                        <input autocomplete="new-password" name="Password" value="" id="Password" type="password" class="form-control password" autocomplete="off">
                                        <span class="form-control-feedback">{{ $errors->first('Password') }}</span>
                                    </div>
                                </div>
                            </div>

                            <hr />

                            <div class="row">
                                <div class="col-6">
                                    <a href="{{ route('dashboard.home.index') }}" class="btn-secondary">Cancel</a>
                                </div>
                                <div class="col-6 text-right">
                                    <input type="submit" value="Save" class="submit_user_form btn-primary">
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- end my-profile -->
                </div>
            </div>
        </div>
        <!-- end container -->
    </div>
@endsection