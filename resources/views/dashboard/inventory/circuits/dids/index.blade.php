@extends('layouts.dashboard')

@section('title', 'DIDs - Circuit # '.$Circuit['CarrierCircuitID'].' - ACCT # '.$BTNAccount->getAttribute('AccountNum'))

@section('content')
    <div class="main-container inventory-circuits inventory-circuits-dids">
        <div class="container">
            <div class="row">
                <a href="{{ route('dashboard.inventory.circuits.index', [$BTNAccount, $page]) }}" class="nav_button">&lt; Back </a>
                <br/>
                <br/>
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

                                            <form class="form-inline mb-3" method="GET" action="{{ route('dashboard.inventory.circuits.show', [$BTNAccount, $Circuit, 'page' => $page, 'did-page' => request('did-page')])]) }}">
                                                <div class="form-group">
                                                    <input type="text" name="DID" class="form-control DID-search" value="{{ request('DID') }}" placeholder="Search DID">
                                                </div>
                                                <div class="form-group ml-2">
                                                    <button type="submit" class="btn btn-outline-primary btn-sm">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                </div>
                                                @can('edit')
                                                    <a href="#delete-range" data-toggle="collapse" class="col text-right">Delete DID Range</a>
                                                    <a href="{{ route('dashboard.inventory.circuits.dids.create', [$BTNAccount, $Circuit, $page, request('did-page')]) }}" class="form-group text-right">New DID</a>
                                                @endcan
                                            </form>

                                            @can('edit')
                                                <form id="delete-range" class="collapse @if($errors->any()) show @endif" method="POST" action="{{ route('dashboard.inventory.circuits.dids.destroy', [$BTNAccount, $Circuit]) }}">

                                                    {!! csrf_field() !!}
                                                    {!! method_field('DELETE') !!}

                                                    <input type="hidden" value="range" name="Type" />

                                                    <input type="hidden" value="{{ $page }}" name="page" />
                                                    <input type="hidden" value="{{ request('did-page') }}" name="did-page" />

                                                    <h1>Delete DID Range</h1>
                                                    <hr />

                                                    <div class="row range-input">
                                                        <div class="col-md-6 mt-1">
                                                            <div class="form-group {{ $errors->has('DIDPrefix') ? 'has-danger': '' }}">
                                                                <label class="d-block control-label" for="DIDPrefix">DID Range Prefix</label>
                                                                <div class="d-block">
                                                                    <input type="text" id="DIDPrefix" name="DIDPrefix" class="form-control" value="{{ old('DIDPrefix') }}" />
                                                                    <span class="form-text text-danger">{{ $errors->first('DIDPrefix') }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mt-1 range-input">
                                                            <div class="form-group {{ $errors->has('DIDFrom') ? 'has-danger': '' }}">
                                                                <div class="d-block">
                                                                    <label class="d-block control-label" for="DIDFrom">DID Range Start</label>
                                                                    <input type="number" id="DIDFrom" min="0" max="9999" name="DIDFrom" class="form-control" value="{{ old('DIDFrom') }}" />
                                                                    <span class="form-text text-danger">{{ $errors->first('DIDFrom') }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mt-1 range-input">
                                                            <div class="form-group {{ $errors->has('DIDTo') ? 'has-danger': '' }}">
                                                                <div class="d-block">
                                                                    <label class="d-block control-label" for="DIDTo">DID Range End</label>
                                                                    <input type="number" id="DIDTo" min="0" max="9999" name="DIDTo" class="form-control" value="{{ old('DIDTo') }}" />
                                                                    <span class="form-text text-danger">{{ $errors->first('DIDTo') }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group pull-right">
                                                        <button type="submit" id="DeleteDIDsButton2" class="btn btn-sm btn-danger"><i class="fa fa-trash mr-2"></i>DELETE RANGE</button>
                                                    </div>

                                                    <div class="clearfix"></div>
                                                    <hr />
                                                </form>
                                            @endcan


                                            <form method="POST" action="{{ route('dashboard.inventory.circuits.dids.destroy', [$BTNAccount, $Circuit]) }}">

                                                {!! csrf_field() !!}
                                                {!! method_field('DELETE') !!}

                                                <input type="hidden" value="array" name="Type" />

                                                <input type="hidden" value="{{ $page }}" name="page" />
                                                <input type="hidden" value="{{ request('did-page') }}" name="did-page" />

                                                <div class="table-responsive">
                                                    <table class="table table-hovered table-stripped">
                                                        <thead>
                                                        <tr>
                                                            <th>DID</th>
                                                            <th>Note</th>
                                                            @can('edit')
                                                                <th>Edit</th>
                                                                <th>
                                                                    <input data-delete-toggle {{ $DIDs->count() > 0 ? '' : 'disabled="disabled"' }} type="checkbox" />
                                                                </th>
                                                            @endcan
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @forelse($DIDs as $DID)
                                                            <tr>
                                                                <td>{{ $DID['DID'] }}</td>
                                                                <td>{{ $DID['DIDNote'] }}</td>
                                                                @can('edit')
                                                                    <td><a href="{{ route('dashboard.inventory.circuits.dids.edit', [$BTNAccount, $Circuit, $DID, $page, request('did-page')]) }}"><i class="fa fa-pencil fa-ac"></i></a></td>
                                                                    <td>
                                                                        <input data-delete-did value="{{ $DID->getKey() }}" name="DID[]" type="checkbox" />
                                                                    </td>
                                                                @endcan
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="4" class="text-center no-highlight"><h3 class="not-found">No DIDs Found</h3></td>
                                                            </tr>
                                                        @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <hr />

                                                {!! $DIDs->appends([$page])->links() !!}

                                                @can('edit')
                                                    <div class="form-group pull-right">
                                                        <button type="submit" disabled="disabled" id="DeleteDIDsButton" class="btn btn-sm btn-danger"><i class="fa fa-trash mr-2"></i>DELETE DID('s)</button>
                                                    </div>
                                                @endcan

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
