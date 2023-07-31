@extends('layouts.public')

@section('content')
    <div class="col-md-4 float-xl-right">
        <a href="{{ route('index.index') }}" id="forgot-pass"> &lt; Back to Sign In</a><br>
        <br>

        <div class="login-form">
            <h1>RESET PASSWORD</h1>

            <p>All passwords are to be treated as sensitive and confidential information.</p>

            @if(isset($status))
                <p class="text-danger">{{ $status }}</p>
            @endif


            <form method="post" action="{{ route('auth.password.store') }}">

                {!! csrf_field() !!}

                <input type="hidden" name="Token" value="{{ $token }}"/>

                <div class="form-group {{ $errors->has('Password') ? 'has-danger' : '' }}">
                    <label for="Password" class="control-label">Password</label>
                    <input name="Password" id="Password" type="password" class="form-control field-padding">
                    @if($errors->has('Password'))
                        <span class="form-text text-danger">{{ $errors->first('Password') }}</span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('PasswordConfirmation') ? 'has-danger' : '' }}">
                    <label for="PasswordConfirmation" class="control-label">Password Confirmation</label>
                    <input name="PasswordConfirmation" id="PasswordConfirmation" type="password"
                           class="form-control field-padding">
                    @if($errors->has('PasswordConfirmation'))
                        <span class="form-text text-danger">{{ $errors->first('PasswordConfirmation') }}</span>
                    @endif
                </div>

                <input type="submit" value="Save" class="btn-primary btn-group-vertical">
            </form>

            <div class="clearfix"></div>

        </div>
    </div>

    <div class="col-md-8 left-side clearfix">
        <div class="logo">
            <a href="{{ route('index.index') }}">
                <img src="{{ asset('/assets/images/logo.png') }}" width="245" height="225" alt="logo">
            </a>
        </div>
    </div>
@endsection
