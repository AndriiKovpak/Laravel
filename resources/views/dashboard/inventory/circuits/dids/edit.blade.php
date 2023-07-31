@extends('layouts.dashboard')

@section('title', 'Edit DID # '.$CircuitDID['DID'].' - Circuit # '.$Circuit['CarrierCircuitID'].' - ACCT # '.$BTNAccount->getAttribute('AccountNum'))

@section('content')
    <div class="main-container inventory-circuits inventory-circuits-dids-edit">
        <div class="container">
            <a href="{{ route('dashboard.inventory.circuits.show', [$BTNAccount, $Circuit, 'tab' => '1', 'page' => $page, 'search' => request('search'), 'DID' => request('DID'), 'did-page' => request('did-page')]) }}" class="nav_button">&lt; Back </a>
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

                                            <a href="{{ route('dashboard.inventory.circuits.show', [$BTNAccount, $Circuit, 'tab' => '1', 'page' => $page, 'search' => request('search'), 'DID' => request('DID'), 'did-page' => request('did-page')]) }}" class="">&lt; Back to DIDs List</a>

                                            <h1 class="mt-1">DID # {{ $CircuitDID['DID'] }}</h1>

                                            <hr />

                                            <form method="POST" action="{{ route('dashboard.inventory.circuits.dids.update', [$BTNAccount, $Circuit, $CircuitDID]) }}">

                                                {!! method_field('PUT') !!}

                                                @include('dashboard.inventory.circuits.dids.partials._form', [
                                                    'BTNAccount'    =>  $BTNAccount,
                                                    'Circuit'       =>  $Circuit,
                                                    'CircuitDID'    =>  $CircuitDID
                                                ])

                                            </form>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="clearfix"></div>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
