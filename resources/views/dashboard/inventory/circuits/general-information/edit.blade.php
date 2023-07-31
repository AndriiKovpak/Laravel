@extends('layouts.dashboard')

@section('title', 'Edit Circuit # '.$Circuit['CarrierCircuitID'].' - ACCT # '.$BTNAccount->getAttribute('AccountNum'))

@section('content')
    <div class="main-container inventory-circuits inventory-circuits-form">
        <div class="container">
            <div class="row">
                <a href="{{ route('dashboard.inventory.circuits.show', [$BTNAccount, $Circuit, 'page' => $page, 'search' => request('search')]) }}" class="nav_button">&lt; Back </a>
                <br/>
                <br/>
                {{-- <div class="col-lg-4 mb-5">
                    @include('dashboard.inventory.circuits.partials._table')
                </div> --}}

                <div class="col-lg-12 circuit-container">

                    <div class="row circuit-content">

                        <div class="col-lg-12">
                            <strong>Circuit # {{ $Circuit['CarrierCircuitID'] }}</strong>
                            <hr />
                        </div>

                        <div class="col-lg-12">
                            <div>
                                <div class="card2">
                                    <div class="card-header2">
                                        <h5 class="mb-0">
                                            <a href="{{ route('dashboard.inventory.circuits.show', [$BTNAccount, $Circuit, 'page' => $page]) }}">
                                                General Information
                                            </a>
                                        </h5>
                                    </div>

                                    <div class="collapse show" >
                                        <div class="card-block">

                                            <form method="POST" action="{{ route('dashboard.inventory.circuits.update', [$BTNAccount, $Circuit]) }}">
                                                {!! method_field('PUT') !!}
                                                @include('dashboard.inventory.circuits.general-information.partials._form')
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
