@extends('layouts.dashboard')

@section('title', 'Create New BTN')

@section('content')
    <div class="main-container">
        <!-- start container -->
        <div class="container">
            <!-- start confirmation -->
            <div class="confirmation">

                <p><a href="{{  url()->previous() }}">&lt; Back</a></p>

                <h3>Create New BTN</h3>

                <div class="clearfix"></div>

                <div class="tab-content mt-3">
                    <div class="tab-pane active">
                        <form id="BTNAccountForm" name="BTNAccountForm" class="clearfix" method="POST" action="{{ route('dashboard.inventory.store') }}">

                            {!! csrf_field() !!}

                            @include('dashboard.inventory.index.partials._form')

                        </form>
                    </div>
                </div>
            </div>
            <!-- end confirmation -->
        </div>
        <!-- end container -->
    </div>
    <!-- end main-container -->
    <div class="bottom-block">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <a href="{{ route('dashboard.inventory.index') }}" class="btn-secondary">CANCEL</a>
                    <button class="btn-primary pull-right" onclick="return document.getElementById('BTNAccountForm').submit();">SAVE</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end main-container -->
@endsection
