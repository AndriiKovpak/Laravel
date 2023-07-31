@extends('layouts.public')

@section('content')
<div class="col-lg-4 float-xl-right">
    <div class="login-form">
        <a href="{{ route('index.index') }}" id="forgot-pass"> &lt; Back to Sign In</a><br>
        <br>
        <h1>FORGOT YOUR PASSWORD</h1>

        @if(isset($status))
        <p>{{ $status }}</p>
        @else
        <p>To recover your password please enter the email address used when creating your account.</p>
        <form method="post" action="{{ route('auth.password.send') }}">

            {!! csrf_field() !!}

            <div class="form-group {{ $errors->has('EmailAddress') ? 'has-danger' : '' }}">
                <label for="EmailAddress" class="control-label">Your Email</label>
                <input name="EmailAddress" id="EmailAddress" type="email" value="{{ old('EmailAddress') }}" class="form-control field-padding">
                @if($errors->has('EmailAddress'))
                <span class="form-text text-danger">{{ $errors->first('EmailAddress') }}</span>
                @endif
            </div>

            <input type="submit" value="Send" class="btn-primary btn-group-vertical">
        </form>
        @endif
        <div class="clearfix"></div>
    </div>
</div>

<div class="col-md-8 left-side clearfix">
    <div class="logo"><a href="{{ route('index.index') }}"><img src="{{ asset('images/logo.png') }}" width="245" height="225" alt="logo"></a></div>
</div>
@endsection
