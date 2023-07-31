@extends('layouts.dashboard')

@section('title', 'Edit Invoice')

@section('content')
    <!-- start main-container -->
    <div class="main-container">
        <!-- start container -->
        <div class="container">
            <!-- start confirmation -->
            <div class="confirmation">
                <p>
                    <a href="{{ $new ? route('dashboard.inventory.accounts-payable.index', [$invoice->BTNAccount]) : url()->previous() }}" class="nav_button">&lt; Back</a>
                </p>
                <h3>{{$new ? 'New' :'Edit'}} Invoice</h3>
                <hr />

                <div class="tab-content mt-3">
                    <div class="tab-pane active">
                        <form id="InvoiceForm" method="POST" action="{{ route('dashboard.invoices.update', [$invoice->InvoiceAPID, 'new' => $new ?: '' ]) }}">
                            {!! method_field('PUT') !!}
                            @include('dashboard.invoices.partials._form', ['BTNAccount' => null])
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
                        <a href="{{ $new ? url()->previous() : route('dashboard.invoices.show', [$invoice->InvoiceAPID]) }}" class="btn-secondary">Cancel</a>
                        <button class="btn-primary pull-right" onclick={{ $new ? "saveInvoice('InvoiceForm');" : "document.getElementById('InvoiceForm').submit();"}}>Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end main-container -->
@stop
