@extends('layouts.dashboard')

@section('title', 'Edit General Info - ACCT # '.$BTNAccount->getAttribute('AccountNum'))

@section('content')
    <div class="main-container inventory">
        <!-- start container -->
        <div class="container">
            <!-- start confirmation -->
            <div class="confirmation">

                <a href="{{ url()->previous() }}" class="nav_button">&lt; Back</a>
                <h3 class="ml-3">ACCT # {{ $BTNAccount->getAttribute('AccountNum') }}</h3>

                @include('dashboard.inventory.partials.links', ['active' => 'info'])

                <div class="clearfix"></div>

                <div class="tab-content mt-3">
                    <div class="tab-pane active">
                        <form id="BTNAccountUpdate" name="BTNAccountUpdate" class="clearfix" method="POST" action="{{ route('dashboard.inventory.update', [$BTNAccount]) }}">

                            {!! method_field('PUT') !!}
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
                    <a href="{{ route('dashboard.inventory.show', [$BTNAccount]) }}" class="btn-secondary">CANCEL</a>
                    <button class="btn-primary pull-right" onclick="return document.getElementById('BTNAccountUpdate').submit();">SAVE</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end main-container -->
@endsection
