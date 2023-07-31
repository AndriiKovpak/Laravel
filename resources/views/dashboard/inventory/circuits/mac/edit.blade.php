@extends('layouts.dashboard')

@section('title', 'Edit MAC Order # '.$CircuitMAC['OrderNum'].' - Circuit # '.$Circuit['CarrierCircuitID'].' - ACCT # '.$BTNAccount->getAttribute('AccountNum'))

@section('content')
    <div class="main-container inventory-circuits inventory-circuits-mac-edit">
        <div class="container">
            <a href="{{ route('dashboard.inventory.circuits.show', [$BTNAccount, $Circuit, 'tab' => 2, 'page' => $page, 'mac-page' => request('mac-page')]) }}" class="nav_button">&lt; Back </a>
            <br/>
            <br/>
            <div class="row">

                {{-- <div class="col-lg-4 mb-5">
                    @include('dashboard.inventory.circuits.partials._table')
                </div> --}}

                <div class="col-lg-12 circuit-container">

                    <div class="row circuit-content">

                        <div class="col-lg-12">
                            <strong>Circuit # {{ $Circuit['CarrierCircuitID'] }}</strong>
                            {{-- <a href="{{ route('dashboard.inventory.circuits.index', [$BTNAccount, $page]) }}" class="pull-right"><i class="fa fa-times"></i></a> --}}
                            <hr />
                        </div>

                        <div class="col-lg-12">
                            <div>

                                <div class="card2">

                                    <div class="collapse show" >
                                        <div class="card-block">

                                            <a href="{{ route('dashboard.inventory.circuits.show', [$BTNAccount, $Circuit, 'tab' => 2, 'page' => $page, 'mac-page' => request('mac-page')]) }}" class="">&lt; Back to MAC List</a>

                                            <h1 class="mt-1">Edit MAC Order # {{ $CircuitMAC['OrderNum'] }}</h1>

                                            <hr />

                                            <form method="POST" action="{{ route('dashboard.inventory.circuits.mac.update', [$BTNAccount, $Circuit, $CircuitMAC]) }}">

                                                {!! method_field('PUT') !!}

                                                @include('dashboard.inventory.circuits.mac.partials._form', [
                                                    'BTNAccount'    =>  $BTNAccount,
                                                    'Circuit'       =>  $Circuit,
                                                    'CircuitMAC'    =>  $CircuitMAC
                                                ])

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
