@extends('layouts.dashboard')

@section('title', 'Accounts Payable - ACCT # '.$BTNAccount->getAttribute('AccountNum'))

@section('content')
    <div class="main-container">
        <!-- start container -->
        <div class="container">
            <!-- start confirmation -->
            <div class="confirmation">
                <span style="color: #93958c">
                    &lt;
                    <a href="{{ route('dashboard.inventory.index') }}" class="nav_button">Back to Inventory</a>
                    /
                    <a href="{{ route('dashboard.invoices.index') }}" class="nav_button">Back to Invoices</a>
                </span>

                <h3 class="ml-3">ACCT # {{ $BTNAccount->getAttribute('AccountNum') }}</h3>

                @include('dashboard.inventory.partials.links', ['active' => 'accounts-payable'])

                <div class="clearfix"></div>

                <div class="tab-content mt-3">
                    <div class="tab-pane active">
                        <div class="row">
                            <div class="col-lg-2 col-md-3 col-12">
                                <a href="#carrierDetailsModal" data-toggle="modal" class="ap_btn btn-primary carrier_details_btn">CARRIER BILLING DETAILS</a>
                            </div>
                            <div class="col-lg-2 col-md-3 col-12 offset-lg-6 offset-md-3 offset-0">
                                @can('edit')
                                    <a href="{{ route('dashboard.inventory.accounts-payable.create', [$BTNAccount]) }}" class="ap_btn btn-blue" style="color:white;">NEW BILL</a>
                                @endcan
                            </div>
                            <div class="col-lg-2 col-md-3 col-12">
                               <form class="ap_btn form-inline pull-right" method="get" action="{{ route('dashboard.inventory.accounts-payable.index', [$BTNAccount]) }}">
                                    <div class="form-group" style="width:100%">
                                        <select name="FiscalYearID" class=" form-control" style="width:100%" onchange="submit()">
                                            @foreach($_options['FiscalYearID'] as $value => $title)
                                                <option value="{{ $value }}" {{ $value == request('FiscalYearID', 0) ? 'selected' : '' }}>{{ $title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive mt-3">
                            <table class="table table-striped table-sm text-xs-center">
                                <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Processed by</th>
                                    <th>Bill Date</th>
                                    <th>Entry Date</th>
                                    <th>Processed</th>
                                    <th>Current Charges</th>
                                    <th>Past Due</th>
                                    <th>Credit</th>
                                    <th>View Image</th>
                                    @can('edit')
                                        <th>Change BTN</th>
                                    @endcan
                                    <th>Details</th>
                                    @can('edit')
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    @endcan
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($AccountsPayable as $invoice)
                                    <tr>
                                        <td>{{ $invoice['InvoiceNum'] }}</td>
                                        <td>
                                            @if($invoice['UpdatedByUser'])
                                            {{ $invoice['UpdatedByUser']->getFullName() }}
                                            @else
                                                <i>Not defined</i>
                                            @endif
                                        </td>
                                        <td>
                                            @if($invoice['BillDate'])
                                                {{ $invoice['BillDate']->format('m/d/Y') }}
                                            @else
                                                <i>Not defined</i>
                                            @endif
                                        </td>
                                        <td>{{ $invoice['Created_at']->format('m/d/Y') }}</td>
                                        <td>@notdefined($invoice['ProcessedMethodType'])</td>
                                        <td>${{ \App\Models\Util::formatCurrency($invoice['CurrentChargeAmount'], true) }}</td>
                                        <td>${{ \App\Models\Util::formatCurrency($invoice['PastDueAmount'], true) }}</td>
                                        <td>${{ \App\Models\Util::formatCurrency($invoice['CreditAmount'], true) }}</td>
                                        <td>
                                            @if($invoice['ScannedImage'])
                                                <a href="{{ route('dashboard.inventory.accounts-payable.document', [$BTNAccount, $invoice]) }}?t={{ time() }}" target="_blank"><i class="fa fa-file-picture-o fa-ac"></i></a>
                                            @endif
                                        </td>
                                            @can('edit')
                                            <td>
                                                <a href="{{ route('dashboard.inventory.index', ['change-btn', $BTNAccount, $invoice]) }}"><i class="fa fa-files-o fa-ac" aria-hidden="true"></i></a>
                                            </td>
                                        @endcan
                                        <td>
                                            <a href="{{ route('dashboard.inventory.accounts-payable.show', [$BTNAccount, $invoice]) }}"><i class="fa fa-eye fa-ac"></i></a>
                                        </td>
                                        @can('edit')
                                            <td>
                                                <a href="{{ route('dashboard.inventory.accounts-payable.edit', [$BTNAccount, $invoice]) }}"><i class="fa fa-pencil fa-ac"></i></a>
                                            </td>
                                            <td>
                                                <a href="#" data-confirmation-btn="true"><i class="fa fa-trash fa-ac" aria-hidden="true"></i></a>
                                            </td>
                                            <td data-confirmation-body colspan="13" class="text-left">

                                                <form style="display: none;" method="POST" action="{{ route('dashboard.inventory.accounts-payable.destroy', [$BTNAccount, $invoice]) }}">
                                                    {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                                    <input type="hidden" name="p" value="{{ request('page') }}" />
                                                </form>

                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                Are you sure you want to delete it?

                                                <a href="" data-delete-form>Yes</a>
                                                <a href="#" data-confirmation-btn="false">No</a>
                                            </td>
                                        @endcan
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center no-highlight">
                                            <h3 class="not-found">No results displayed</h3>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                {!! $AccountsPayable->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end confirmation -->
        </div>
        <!-- end container -->

        <div class="modal settings-modal" id="carrierDetailsModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Carrier Billing Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style='overflow-y: auto'>
                        <div class="clearfix carrier_details_view">
                            @include('dashboard.inventory.accounts-payable.partials.carrier-details._show')
                        </div>
                        @can('edit')
                            <div class="clearfix carrier_details_edit">
                                @include('dashboard.inventory.accounts-payable.partials.carrier-details._edit')
                            </div>
                        @endcan
                    </div>
                    @can('edit')
                        <div class="modal-footer text-center">
                            <button type="button" class="btn-primary edit_carrier_details_btn">EDIT</button>
                            <input value="Update" class="btn-primary submit_update_carrier_detials_btn">
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    <!-- end main-container -->
@endsection
