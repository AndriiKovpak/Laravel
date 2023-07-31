@extends('layouts.dashboard')

@section('title', 'General Info - ACCT # '.$BTNAccount->getAttribute('AccountNum'))

@section('content')
    <div class="main-container">
        <!-- start container -->
        <div class="container">
            <!-- start confirmation -->
            <div class="confirmation">

                <a href="{{ route('dashboard.inventory.index') }}" class="nav_button">&lt; Back</a>

                <h3 class="ml-3">ACCT # {{ $BTNAccount->getAttribute('AccountNum') }}</h3>

                @include('dashboard.inventory.partials.links', ['active' => 'info'])

                <div class="clearfix"></div>
                <div class="tab-content mt-3">
                    <div class="tab-pane active">
                        <div class="clearfix">
                            <div class="row">
                                <div class="col-md-6 mt-1">
                                    <div class="form-group clearfix">
                                        <label class="d-block">Status</label>
                                        <div class="d-block">
                                            <span>@notdefined($BTNAccount['BTNStatusType'])</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-1">
                                    <form method="POST" action="{{ route('dashboard.inventory.saic', [$BTNAccount]) }}">
                                    {!! csrf_field() !!}
                                        <div class="form-group {{ $errors->has('IsPapIsSAICerless') ? 'has-danger' : '' }}">
                                            <input type="submit" value="Submit" style="display: none;" id="submitBtn">
                                            <input class="custom-check" {{(old('IsSAIC', $BTNAccount['IsSAIC'] ?? '') == '1' ? 'checked': '')}} name="IsSAIC" id="IsSAIC" type="checkbox" value="1" onClick="Submit()">
                                            <label for="submitBtn">IsSAIC</label>
                                            @if($BTNAccount['IsSAIC'])
                                            <div class="d-block">
                                                <span>{{ $BTNAccount['SAICDate'] }}</span>
                                            </div>
                                            @endif
                                        </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group clearfix">
                                        <label class="d-block">Account #</label>
                                        <div class="d-block">
                                            <span>@notdefined($BTNAccount['AccountNum'])</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group clearfix">
                                        <label class="d-block">Billing Telephone #</label>
                                        <div class="d-block">
                                            <span>{{ App\Models\Util::formatPhoneNumber($BTNAccount['BTN']) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group clearfix">
                                        <label class="d-block">Carrier</label>
                                        <div class="d-block">
                                            <span> @notdefined($BTNAccount['Carrier'])</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6 mt-1">
                                    <div class="form-group">
                                        <label class="d-block">Account Address</label>
                                        <div class="d-block">
                                            @include('partials._address', ['address' => $BTNAccount['SiteAddress']])
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-1">
                                    <div class="form-group">
                                        <label class="d-block">Account Name</label>
                                        <div class="d-block">
                                            @notdefined($BTNAccount['SiteAddress']['SiteName'])
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group clearfix">
                                        <label class="d-block">District</label>
                                        <span class="d-block"> @notdefined($BTNAccount['DivisionDistrict'])</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="d-block">Last Updated</label>
                                        <span class="d-block"> @notdefined($BTNAccount['Updated_at']) by @notdefined($BTNAccount->UpdatedByUser->UserName)</span>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group clearfix">
                                        <label class="d-block">Notes</label>
                                        @if($BTNAccount['Notes']->count())
                                            <div class="inventory-show-note">
                                                <div class="last-note">{{ $BTNAccount['notes']->first()['Note'] }}<span class="text-muted"> {{$BTNAccount['notes']->first()['Created_at']->format('m/d/Y')}}</span></div>
                                                <div class="icon-wrapper">
                                                    <i class="fa fa-book toggleInventoryNoteHistory" title="More history notes..."></i>
                                                </div>
                                            </div>
                                            <div class="table-responsive mt-2 inventoryNoteHistory">
                                                <table class="carrier_table table table-striped table-sm text-xs-center">
                                                    <thead>
                                                        <tr>
                                                            <th>Note</th>
                                                            <th>Written By</th>
                                                            <th>Registered Date</th>
                                                            <th>Delete</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($BTNAccount->Notes as $Note)
                                                        <tr>
                                                            <td>
                                                                <div class="break-word text-left">{{ $Note['Note'] }}</div>
                                                            </td>
                                                            <td>
                                                                <div class="break-word text-center">{{ $Note->UpdatedByUser ? $Note->UpdatedByUser->FirstName . ' ' . $Note->UpdatedByUser->LastName : '' }}</div>
                                                            </td>
                                                            <td class="text-muted">{{ $Note['Created_at']->format('m/d/Y') }}</td>
                                                            <td class="text-center">
                                                                <a href="#" data-confirmation-btn="true"><i class="fa fa-trash fa-ac" aria-hidden="true"></i></a>
                                                            </td>
                                                            <td data-confirmation-body="" colspan="7" class="text-left">
                                                                <form style="display: none;" method="POST" action="{{ route('dashboard.inventory.notes.destory', [$BTNAccount, $Note]) }}">
                                                                    {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                                                    <input type="hidden" name="page" value="{{ request('page') }}" />
                                                                </form>

                                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                                Are you sure you want to delete this note?

                                                                <a href="" data-delete-form="">Yes</a>
                                                                <a href="#" data-confirmation-btn="false">No</a>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <i class="text-muted">No notes.</i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
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
                <div class="col-md-10 offset-md-1">
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-xs-center text-center">
                            <a href="{{ route('dashboard.inventory.edit', [$BTNAccount]) }}" class="btn-primary"><i class="fa fa-pencil"></i> EDIT</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    <!-- end main-container -->
@endsection
