@extends('layouts.dashboard')

@section('title', 'Edit Carrier - '.$carrier->CarrierName)

@section('content')
    <div class="main-container" style="padding-bottom:0">
        <form enctype="multipart/form-data" method="post" action="{{ route('dashboard.carriers.update', $carrier) }}" style="margin:0">
        {!! method_field('PUT') !!}
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
                        <h2 class="common-heading">Edit Carrier</h2>
                    </div>

                    @include('dashboard.carriers.partials._form')

                </div>
            </div>
            <br>
            <div class="bottom_container_edit">
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

<style>
    .bottom_container_edit{
        padding-top:25px;
        padding-bottom:25px;
        background-color:lightgrey;
    }
    .nav_button {
        color:#93958c;
    }
</style>
