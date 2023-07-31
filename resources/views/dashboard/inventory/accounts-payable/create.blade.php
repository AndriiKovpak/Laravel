@extends('layouts.dashboard')

@section('title', 'New Bill - ACCT # '.$BTNAccount->getAttribute('AccountNum'))

@section('content')
    <div class="main-container inventory-accounts-payable">
        <!-- start container -->
        <div class="container">
            <!-- start confirmation -->
            <div class="confirmation">
                <p>
                    <a href="{{ route('dashboard.inventory.accounts-payable.index', [$BTNAccount]) }}" class="nav_button">&lt; Back</a>
                </p>

                <h3>New Bill</h3>
                <hr />

                <div class="tab-content mt-3">
                    <div class="tab-pane active">
                        <form id="AccountPayableForm" method="POST" action="{{ route('dashboard.inventory.accounts-payable.store', [$BTNAccount]) }}">
                            @include('dashboard.inventory.accounts-payable.partials._form', ['invoice' => $AccountPayable])
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
                        <a href="{{ route('dashboard.inventory.accounts-payable.index', [$BTNAccount]) }}" class="btn-secondary">CANCEL</a>
                        <button class="btn-primary pull-right" onclick="saveInvoice('AccountPayableForm');">SAVE</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end main-container -->
@endsection
