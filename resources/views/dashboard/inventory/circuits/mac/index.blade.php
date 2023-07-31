{{-- this page will be removed later --}}
@extends('layouts.dashboard')

@section('title', 'MAC Notes - Circuit # '.$Circuit['CarrierCircuitID'].' - ACCT # '.$BTNAccount->getAttribute('AccountNum'))

@section('content')
    <div class="main-container inventory-circuits inventory-circuits-mac">
        <div class="container">
            <a href="{{ route('dashboard.inventory.circuits.index', [$BTNAccount, $page]) }}" class="nav_button">&lt; Back </a>
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
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <a class="collapsed" href="{{ route('dashboard.inventory.circuits.mac.index', [$BTNAccount, $Circuit, $page]) }}">
                                                MAC Notes
                                            </a>
                                        </h5>
                                    </div>
                                    <div class="collapse show" >
                                        <div class="card-block">

                                            @can('edit')
                                                <a href="{{ route('dashboard.inventory.circuits.mac.create', [$BTNAccount, $Circuit, $page, request('mac-page')]) }}" class="pull-right" style="position: absolute; right: 20px;">New MAC</a>
                                            @endcan

                                            <div class="table-responsive mt-4">
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Order #</th>
                                                            <th>Type</th>
                                                            <th>Description</th>
                                                            <th>Due Date</th>
                                                            <th>Created by</th>
                                                            <th>Created Date</th>
                                                            @can('edit')
                                                            <th>Edit</th>
                                                            @endcan
                                                            <th>Details</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($CircuitMACs as $MACNote)
                                                            <tr>
                                                                <td>@notdefined($MACNote['OrderNum'])</td>
                                                                <td>@notdefined($MACNote['Type'])</td>
                                                                <td>@notdefined($MACNote['Description'])</td>
                                                                <td>@notdefined($MACNote['ContractExpDate'], $MACNote['ContractExpDate']->format('m/d/Y'))</td>
                                                                <td>@notdefined($MACNote['UpdatedByUser'], $MACNote['UpdatedByUser']->getFullName())</td>
                                                                <td>@notdefined($MACNote['Created_at'], $MACNote['Created_at']->format('m/d/Y'))</td>
                                                                <td>
                                                                    <a href="{{ route('dashboard.inventory.circuits.mac.show', [$BTNAccount, $Circuit, $MACNote, $page, request('mac-page')]) }}">
                                                                        <i class="fa fa-eye fa-ac"></i>
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    <a href="{{ route('dashboard.inventory.circuits.mac.edit', [$BTNAccount, $Circuit, $MACNote, $page, request('mac-page')]) }}" ><i class="fa fa-pencil fa-ac"></i></a>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="6" class="text-center no-highlight"><h3 class="not-found">No MAC Notes Found</h3></td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>

                                            {!! $CircuitMACs->appends(['page' => $page])->links() !!}

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
