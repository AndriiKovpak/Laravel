@extends('layouts.dashboard')

@section('title', 'Inventory')

@section('content')
    <div class="main-container inventory-index">
        <div class="container">
            <div class="row">

                @if($_change)
                    <div class="col-xs-12 confirmation">
                        <a href="{{ url()->previous() }}" class="nav_button">&lt; Back</a>
                        <h3>Change BTN - Invoice # {{ $InvoiceAP['InvoiceNum'] }}</h3>

                    </div>
                @endif
                @if(!$_circuit)
                <div class="col-xs-12 list-invoice">

                    <form method="GET" action="{{ route('dashboard.inventory.index') }}" accept-charset="UTF-8">
                        {{-- Include get parameters except newSearch, page, and any shown fields. --}}
                        @foreach(request()->except(['newSearch', 'page', 'accountSearch', 'circuitSearch', 'circuitInventorySearch', 'CarrierID']) as $name => $value)
                            <input type="hidden" name="{{ $name }}" value="{{ $value }}" />
                        @endforeach

                        <div class="row">
                            <div class="col-lg-1"></div>
                            <div class="col-lg-7">

                                <h2>SEARCH {{ strtoupper(Str::plural(request('type'))) }}</h2>

                                <div class="form-group">
                                    <label for="accountSearch">Account Number/BTN</label>
                                    <input id="accountSearch" name="accountSearch" type="text" value="{{ request('accountSearch', session('inventoryIndexRequest.accountSearch')) }}" class="form-control" placeholder="Account Number/BTN">
                                </div>

                                <div class="form-group">
                                    <label for="circuitSearch">Circuit ID/Telephone #</label>
                                    <input id="circuitSearch" name="circuitSearch" type="text" value="{{ request('circuitSearch', session('inventoryIndexRequest.circuitSearch')) }}" class="form-control" placeholder="Circuit ID/Telephone #">
                                </div>

                                <div class="form-group">
                                    <label for="CarrierID">Carrier</label>
                                    <select id="CarrierID" name="CarrierID" class="form-control">
                                        <option selected value="">All Carriers</option>
                                        @foreach($_options['CarrierID'] as $value => $title)
                                            <option value="{{ $value }}" {{ request('CarrierID') === $value ? 'selected' : '' }}>{{ $title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="circuitInventorySearch">Circuit ID/Telephone # Inventory</label>
                                    <input id="circuitInventorySearch" name="circuitInventorySearch" type="text" value="{{ request('circuitInventorySearch', session('inventoryIndexRequest.circuitInventorySearch')) }}" class="form-control" placeholder="Circuit ID/Telephone # Inventory">
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-6 col-md-5">
                                        <button class="btn-primary btn-block border-white" style="width:100%" type="submit">Search</button>
                                    </div>
                                </div>
                            </div>
                            @can('edit')
                                @if(! ($_functional))
                                    <div class="col-lg-4">

                                        <div class="row">
                                            <div class="col-lg-12"><h2>&nbsp;</h2></div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <br>
                                                <div class="dropdown">
                                                    <a class="btn-primary dropdown-toggle" href="#" id="createNewMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        CREATE
                                                    </a>

                                                    <div class="dropdown-menu" aria-labelledby="createNewMenuLink">
                                                        <a class="dropdown-item" href="{{ route('dashboard.inventory.create') }}">New Account#/BTN</a>
                                                        <a class="dropdown-item" href="{{ route('dashboard.orders.create', ['from' => 'inventory', 'category' => 1]) }}">New Order</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endcan
                        </div>
                    </form>
                </div>
                @endif
            </div>

            <div class="row">
                <div class="col-lg-12 invoice-info">

                    @if($_change == true)
                        <p>Select a BTN to apply the change</p>
                    @elseif($_circuit == true)
                        <br>
                        <h2>New Circuit</h2>
                        <p>Select a BTN for new Circuit</p>
                    @endif

                    @if($Circuits)
                        @include('dashboard.inventory.index.partials._circuits', ['loc' => 'Main'])
                    @elseif($BTNAccounts)
                    {{-- {{dd($BTNAccounts)}} --}}
                        @include('dashboard.inventory.index.partials._btns')
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
