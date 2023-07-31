@extends('layouts.dashboard')

@section('title', 'MAC - ACCT # '.$BTNAccount->getAttribute('AccountNum'))

@section('content')
    <div class="main-container">
        <!-- start container -->
        <div class="container">
            <!-- start confirmation -->
            <div class="confirmation">

                <a href="{{ route('dashboard.inventory.index') }}" class="nav_button">&lt; Back</a>

                <h3 class="ml-3">ACCT # {{ $BTNAccount->getAttribute('AccountNum') }}</h3>

                @include('dashboard.inventory.partials.links', ['active' => 'mac'])

                <div class="clearfix"></div>

                <div class="tab-content mt-3">
                    <div class="tab-pane active">
                        <div class="table-responsive">
                            <table class="table table-striped table-sm text-xs-center">
                                <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Type</th>
                                    <th>Due Date</th>
                                    <th>Created by</th>
                                    <th>Created Date</th>
                                    <th>Details</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($MACs as $mac)
                                    <tr>
                                        <td>@notdefined($mac['OrderNum'])</td>
                                        <td>@notdefined($mac['Type'])</td>
                                        <td>@notdefined($mac['ContractExpDate'], $mac['ContractExpDate']->format('m/d/Y'))</td>
                                        <td>@notdefined($mac['UpdatedByUser'], $mac['UpdatedByUser']->getFullName())</td>
                                        <td>@notdefined($mac['Created_at'], $mac['Created_at']->format('m/d/Y'))</td>
                                        <td><a href="{{ route('dashboard.inventory.mac.show', [$BTNAccount, $mac]) }}"><i class="fa fa-eye"></i></a></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center no-highlight">
                                            <h3 class="not-found">No results displayed</h3>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        {!! $MACs->links() !!}
                    </div>
                </div>
            </div>
            <!-- end confirmation -->
        </div>
        <!-- end container -->
    </div>
    <!-- end main-container -->
    @can('edit')
        <div class="bottom-block">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-xs-12 text-center">
                        <a href="{{ route('dashboard.inventory.mac.create', [$BTNAccount]) }}" class="btn-primary">NEW MAC</a>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    <!-- end main-container -->
@endsection
