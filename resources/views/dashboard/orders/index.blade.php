@extends('layouts.dashboard')

@section('title', 'Orders')

@section('content')
    <div class="main-container">
        <!-- start container -->
        <div class="container">
            <!-- start confirmation -->
            <div class="confirmation">
                <br>
                <div class="row">
                    <div class="col-4">
                        <h1 class="carrier_header">Orders</h1>
                    </div>
                    <div class="col-8" style="text-align:right;">
                        <a href="{{route('dashboard.orders.create',['category' => \App\Models\Category::VOICE])}}" style="color:white" class="btn-blue">Create New Order</a>
                    </div>
                </div>

                <div class="clearfix"></div>
                <div class="tab-content mt-3">
                    <div class="tab-pane active">
                        <div class="table-responsive">
                            <table class="carrier_table table table-striped table-sm text-xs-center">
                                <thead>
                                <tr>
                                    <th>SNOW #</th>
                                    <th>Status</th>
                                    <th>Carrier</th>
                                    <th>Site Name</th>
                                    <th>Created Date</th>
                                    <th>Last Updated By</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($Orders as $order)
                                    <tr>
                                        <td>{{ $order['ACEITOrderNum'] }}</td>
                                        <td>{{ $order['OrderStatusType'] }}</td>
                                        <td>
                                            @notdefined($order['CarrierName'])
                                        </td>
                                        <td>{{ $order['SiteName'] }}</td>
                                        <td>{{ $order['Created_at']->format('m/d/Y') }}</td>
                                        <td>
                                            @notdefined($order['UpdatedByUser'], $order['UpdatedByUser']->getFullName())
                                        </td>
                                        <td>
                                            <a href="{{ route('dashboard.orders.edit', [ 'order' => $order, 'p' => request('page')]) }}"><i class="fa fa-pencil fa-ac"></i></a>
                                        </td>
                                        <td>
                                            <a href="#" data-confirmation-btn="true"><i class="fa fa-trash fa-ac" aria-hidden="true"></i></a>
                                        </td>

                                        <td data-confirmation-body colspan="8" class="text-left">

                                            <form style="display: none;" method="POST" action="{{ route('dashboard.orders.destroy', ['order' => $order]) }}">
                                                {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                                <input type="hidden" name="p" value="{{ request('page') }}" />
                                            </form>

                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            Are you sure you want to decline it?

                                            <a href="" data-delete-form>Yes</a>
                                            <a href="#" data-confirmation-btn="false">No</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center no-highlight">
                                            <h3 class="not-found text-center">No results displayed</h3>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        {!! $Orders->links() !!}
                    </div>
                </div>
            </div>
            <!-- end confirmation -->
        </div>
        <!-- end container -->
    </div>
@endsection
