@extends('layouts.dashboard')

@section('title', 'Bill Edit - Invoice # '.$AccountPayable['InvoiceNum'].' - ACCT # '.$BTNAccount->getAttribute('AccountNum'))

@section('content')
    <div class="main-container inventory-accounts-payable">
        <!-- start container -->
        <div class="container">
            <!-- start confirmation -->
            <div class="confirmation">
                <p>
                    <a href="{{ route('dashboard.inventory.accounts-payable.index', [$BTNAccount]) }}" class="nav_button">&lt; Back</a>
                </p>

                <h3>Bill Edit</h3>
                <hr />

                <div class="tab-content mt-3">
                    <div class="tab-pane active">
                        <form id="AccountPayableForm" method="POST" action="{{ route('dashboard.inventory.accounts-payable.update', [$BTNAccount, $AccountPayable]) }}">
                            {!! method_field('PUT') !!}
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
                        <a href="{{ route('dashboard.inventory.accounts-payable.show', [$BTNAccount, $AccountPayable]) }}" class="btn-secondary">CANCEL</a>
                        <button class="btn-primary pull-right" onclick="return document.getElementById('AccountPayableForm').submit();">SAVE</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end main-container -->
@endsection
