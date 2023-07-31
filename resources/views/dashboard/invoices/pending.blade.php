@extends('layouts.dashboard')

@section('title', 'Pending Invoices')

@section('content')
<!-- start main-container -->

<div class="main-container invoices">
    <!-- start container -->

    <div class="container">
        <form class="controls">
            <div class="row">
                <div class="col-md-4">
                    <select class="form-control invoice-type" data-url-scanned="{{ route('dashboard.invoices.index') }}" data-url-pending="{{ route('dashboard.invoices.pending') }}">
                        <option value="scanned">Scanned Invoices</option>
                        <option value="pending" selected="selected">Pending Invoices</option>
                    </select>
                </div>
                <div class="col-md-8 float-xs-right">
                    <div class="option-ico second clearfix">
                        <ul>
                            <li><a id='scan_pending_invoices' href="{{  route('dashboard.invoices.process-pending')  }}"><i class="fa fa-print"></i>Scan Pending Invoices</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- start list-invoice -->
        </form>
        <!-- end list-invoice -->
        <div class="col-md-12" id="invoiceList">
            <div class="row" id="displayInvoiceListing">
                <!-- start invoice-info -->
                <div class="invoice-info">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm text-xs-center">
                            <thead>
                                <tr>
                                    <th>File Name</th>
                                    <th>Uploaded Date</th><!-- dashboard.invoices.edit-pending-->
                                    @if($ScanErrors)
                                    <th>Error Reason</th>
                                    @endif
                                    <th class="text-right">Rename Invoice</th>
                                    <th class="text-right">View PDF</th>
                                    <th class="text-right">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($invoices))
                                @foreach($invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->getFilename() }}</td>
                                    <td>{{ date('m/d/Y', filectime($invoice)) }}</td>
                                    @if($ScanErrors)
                                    <td>
                                        @if(Arr::get($ScanErrors,$invoice->getFilename()))
                                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i> {{Arr::get($ScanErrors,$invoice->getFilename(), '')}}
                                        @endif
                                    </td>
                                    @endif
                                    <td class="text-right"><a class='editPendingInvoice' href="#editPendingInvoiceNameModal" data-toggle="modal"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
                                    <!--<td class="text-right"><a href="{{route('dashboard.invoices.edit-pending',[$invoice->getFilename()])}}"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>-->
                                    <td class="text-right"><a target="_blank" href="{{ route('dashboard.invoices.view-pdf', [$invoice->getFilename()]) }}"><i class="fa fa-eye"></i></a></td>
                                    <td class="text-right"><a href="" data-confirmation-btn="true"><i class="fa fa-trash" aria-hidden="true"></i></a></td>

                                    <td data-confirmation-body colspan="6" class="text-left">

                                        <form style="display: none;" method="POST" action="{{ route('dashboard.invoices.destroy-pending', [$invoice->getFilename()]) }}">
                                            {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                            <input type="hidden" name="page" value="{{ request('page') }}" />
                                        </form>

                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                        Are you sure you want to remove this file?

                                        <a href="" data-delete-form>Yes</a>
                                        <a href="#" data-confirmation-btn="false">No</a>
                                    </td>
                                </tr>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="8" class="no-highlight text-center">
                                        <h3 class="not-found">Sorry, No Pending Invoices Found</h3>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end container -->

    <div class="modal settings-modal" id="editPendingInvoiceNameModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rename Pending Invoice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style='overflow-y: auto'>
                    <div class="row">
                        <div class="col-12">
                            <form name="edit-pending-form" method="POST" action="{{ route('dashboard.invoices.edit-pending') }}">
                                {{ csrf_field() }}
                                <div class="form-group {{ $errors->has('FileName') ? 'has-danger' : '' }}">
                                    <label for="FileName" class="control-label">New File Name</label>
                                    <input name="FileName" value="{{old('FileName')}}" type="text" class="form-control">
                                    <input hidden name="OldFileName" value="{{old('OldFileName')}}" type="text" class="form-control">
                                    <span class="form-control-feedback">{{ $errors->first('FileName') }}</span>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button type="button" class="btn-primary" id="save-file-name">SAVE</button>
                    <input value="Update" class="btn-primary submit_update_carrier_detials_btn">
                </div>
            </div>
        </div>
    </div>
    <script>
        @if($errors->first('FileName'))
        $('#editPendingInvoiceNameModal').modal('show');
        @endif
    </script>
</div>
<!-- end main-container -->
@stop
