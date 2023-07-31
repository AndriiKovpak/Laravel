@extends('layouts.dashboard')
@section('content')
    <div class="main-container" style="padding-bottom:0">
        <form style="margin:0" method="POST" action="{{ route('dashboard.carriers.contact.store',[$Carrier, '']) }}">
        {!! method_field('POST') !!}
            <!-- start container -->
            <div class="container">
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <a href="{{ url()->previous() }}" class="nav_button">&lt; Back</a>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <h2 class="common-heading">New Carrier Contact for {{ $Carrier->CarrierName }}</h2>
                    </div>

                    <div class="carrier-details col-md-10 offset-md-1" style="padding-bottom:35%;">

                        {!! csrf_field() !!}
                        @include('dashboard.carriers.partials._contact_form')
                    </div>
                </div>
            </div>
            <div class="row bottom_container_edit">
                <div class="container">
                    <div class="row">
                        <div class="col-md-5 offset-md-1 col-6">
                            <a href="{{route('dashboard.carriers.show',  $Carrier->CarrierID)}}" class="btn-secondary">Cancel</a>
                        </div>
                        <div class="col-md-4 offset-md-1 col-6 text-right">
                            <input type="submit" value="Save" class="btn-primary">
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- end container -->
    </div>
@endsection
