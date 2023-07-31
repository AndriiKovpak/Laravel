@extends('layouts.dashboard')

@section('title', 'New Order')

@section('content')
    <div class="main-container" style="padding-bottom:0">
        <form enctype="multipart/form-data" class='order-create-form' method="POST" id='new_order_form' action="{{ route('dashboard.orders.store') }}">
            {!! method_field('POST') !!}
            @include('dashboard.orders.partials._form')
            <br>
            <div class="bottom_container_edit">
                <div class="container">
                    <div class="row">
                        <div class="col-md-5 offset-md-1 col-6">
                            <a href="{{ route('dashboard.orders.index') }}" class="btn-secondary"> CANCEL</a>
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