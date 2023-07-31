@extends('emails')

@section('content')
    An error occurred in the USACE Comms application that needs your attention:<br>
    <br>
    Message: {{$error}}<br>
    <br>
    Trace: {{$trace}}<br>
@endsection

