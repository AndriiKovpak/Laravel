@extends('layouts.dashboard')

@section('title', 'New Carrier')

@section('content')
    <div class="main-container" style="padding-bottom:0">
        <form style="margin:0" method="POST" id="createCarrierForm" action="{{ route('dashboard.carriers.store') }}">
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
                        <h2 class="common-heading">New Carrier</h2>
                    </div>

                    @include('dashboard.carriers.partials._form')

                </div>
            </div>
            <br>
            <div class="row bottom_container_edit">
                <div class="container">
                    <div class="row">
                        <div class="col-md-5 offset-md-1 col-6">
                            <a href="{{route('dashboard.carriers.index')}}" class="btn-secondary">Cancel</a>
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
