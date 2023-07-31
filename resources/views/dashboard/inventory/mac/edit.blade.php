@extends('layouts.dashboard')

@section('title', 'Edit MAC Order # '.$BTNAccountMAC['OrderNum'].' - ACCT # '.$BTNAccount->getAttribute('AccountNum'))

@section('content')
    <div class="main-container inventory inventory-mac">
        <!-- start container -->
        <div class="container">
            <!-- start confirmation -->
            <div class="confirmation">

                <p>
                    <a href="{{ url()->previous() }}" class="nav_button">&lt; Back</a>
                </p>

                <h2 class="common-heading">MAC Edit</h2>

                <div class="clearfix"></div>

                <div class="tab-content mt-3">
                    <div class="tab-pane active">
                        <form id="MACForm" method="POST" action="{{ route('dashboard.inventory.mac.update', [$BTNAccount, $BTNAccountMAC]) }}">
                            {!! method_field('PUT') !!}
                            {!! csrf_field() !!}
                            @include('dashboard.inventory.mac.partials._form')
                        </form>
                    </div>
                </div>
            </div>
            <!-- end confirmation -->
        </div>
        <!-- end container -->
        <div class="bottom-block">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <a href="{{ route('dashboard.inventory.mac.show', [$BTNAccount, $BTNAccountMAC]) }}" class="btn-secondary">CANCEL</a>
                        <button class="btn-primary pull-right" onclick="return document.getElementById('MACForm').submit();">SAVE</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
