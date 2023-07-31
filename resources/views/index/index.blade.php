@extends('layouts.public')

@section('content')
<div class="col-lg-4 float-xl-right">
    <div class="login-form">

        <h1>Welcome</h1>

        <p>In the ACE-IT online database the US Army Corps of Engineers can view, manage, and create invoices for all types of communication devices/methods used in the field today.</p>

        <form method="post" action="{{ route('auth.login.login') }}">

            {!! csrf_field() !!}
            <span class="form-control-feedback text-danger">{{ $errors->first('csrf') }}</span>

            <div class="form-group {{ $errors->has('UserName') ? 'has-danger' : '' }}">
                <label for="UserName" class="control-label">Username</label>
                <input type="text" id="UserName" value="{{ old('UserName') }}" name="UserName" class="form-control field-padding">
                <span class="form-control-feedback text-danger">{{ $errors->first('UserName') }}</span>
            </div>

            <div class="form-Password {{ $errors->has('Password') ? 'has-danger' : '' }}">
                <label for="Password" class="control-label">Password</label>
                <input name="Password" id="Password" type="password" class="form-control">
                <span class="form-control-feedback text-danger">{{ $errors->first('Password') }}</span>
                <span> <a href="{{ route('auth.password.forgot') }}" class="forgot-pass">Forgot your
                        Password?</a></span>
            </div>

            <div class="remember-pass">
                <input class="custom-check" {{ old('Remember') == 'on' ? 'checked': '' }} name="Remember" id="Remember" type="checkbox" value="on">
                <label for="Remember">Remember me on this computer</label>
            </div>

            <button type="submit" class="btn-primary">Enter</button>

            <div class="clearfix"></div>

        </form>

    </div>
</div>

<div class="col-lg-8 left-side clearfix">
    <div class="logo"><a href="{{ route('index.index') }}"><img src="{{ asset('assets/images/logo.png') }}" width="245" height="225" alt="logo"></a></div>
</div>
@endsection
