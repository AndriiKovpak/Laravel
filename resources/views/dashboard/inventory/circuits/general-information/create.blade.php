@extends('layouts.dashboard')

@section('title', 'New Circuit - ACCT # '.$BTNAccount->getAttribute('AccountNum'))

@section('content')
    <div class="main-container inventory-circuits inventory-circuits-form">
        <div class="container">
            <a href="{{ route('dashboard.inventory.circuits.index', [$BTNAccount, 'page' => $page, 'search' => request('search')]) }}" class="nav_button">&lt; Back </a>
            <br/>
            <br/>
            <div class="row">

                {{-- <div class="col-lg-4 mb-5">
                    @include('dashboard.inventory.circuits.partials._table')
                </div> --}}

                <div class="col-lg-12 circuit-container">

                    <div class="row circuit-content">

                        <div class="col-lg-12">
                            <strong>New Circuit</strong>
                            {{-- <a href="{{ route('dashboard.inventory.circuits.index', [$BTNAccount, $page]) }}" class="pull-right"><i class="fa fa-times"></i></a> --}}
                            <hr />
                        </div>

                        <div class="col-lg-12">
                            <div>
                                <div class="card2">

                                    <div class="collapse show" >
                                        <div class="card-block">

                                            <form method="POST" action="{{ route('dashboard.inventory.circuits.store', [$BTNAccount]) }}">
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
