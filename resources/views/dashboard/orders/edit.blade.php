@extends('layouts.dashboard')

@section('title', 'Edit Order # '.$Order->ACEITOrderNum)

@section('content')
    <br>
    <div class="container">
        @if(! $Order->isApproved())
            <form class="pull-right" method="post" action="{{ route('dashboard.orders.approve',[$Order]) }}">
                {!! csrf_field() !!}
                <input type="hidden" name="page" value="{{ request('page') }}" />
                <button type="submit" style="padding-top:5px;" class="btn-blue"><i class="fa fa-check"></i>Approve Order</button>
            </form>
        @endif
    </div>
    <div class="main-container" style="padding-bottom:0">
        <form enctype="multipart/form-data" class='order-edit-form' method="post" action="{{ route('dashboard.orders.update',[$Order]) }}">
            {!! method_field('PUT') !!}
            @include('dashboard.orders.partials._form')
            <br>
            <div class="bottom_container_edit">
                <div class="container">
                    <div class="row">
                        <div class="col-md-5 offset-md-1 col-6">
                            <a href="{{ route('dashboard.orders.index') }}" class="btn-secondary">CANCEL</a>
                        </div>
                        <div class="col-md-4 offset-md-1 col-6 text-right">
                            <input value="Save" type='submit' class="btn-primary">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
