@extends('layouts.dashboard')

@section('title', 'New Invoice')

@section('content')
    <!-- start main-container -->
    <div class="main-container">
        <!-- start container -->
        <div class="container">
            <!-- start confirmation -->
            <div class="confirmation">
                <p>
                    <a href="{{ url()->previous() }}" class="nav_button">&lt; Back</a>
                </p>

                <h3>New Invoice</h3>
                <hr />

                <div class="tab-content mt-3">
                    <div class="tab-pane active">
                        <form id="InvoiceForm" method="POST" action="{{ route('dashboard.invoices.store') }}">
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
                        <a href="{{ route('dashboard.invoices.index') }}" class="btn-secondary">Cancel</a>
                        <button class="btn-primary pull-right" onclick="saveInvoice('InvoiceForm');">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end main-container -->
@stop
