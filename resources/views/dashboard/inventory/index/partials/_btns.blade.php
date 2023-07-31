<div class="table-responsive">
    <table class="table table-striped table-sm text-xs-center">
        <thead>
        <tr>
            <th>BTN</th>
            <th>Account #</th>
            <th>Status</th>
            <th>Carrier Name</th>
            <th>District</th>

            @if($_functional == false)
                <th>Site Map</th>
            @endif

            @if($_functional == false)
                <th>View</th>
                @can('edit')
                    <th>Delete</th>
                @endcan
            @else
                <th>SELECT</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @forelse($BTNAccounts as $btn)
            <tr>
                <td>{{ $btn['BTN'] }}</td>
                <td>{{ $btn['AccountNum'] }}</td>
                <td>{{ $btn['BTNStatusType']['BTNStatusName'] }}</td>
                <td>{{ isset($btn['Carrier']['CarrierName']) ? $btn['Carrier']['CarrierName'] : 'Not defined' }}</td>
                <td>{{ isset($btn['DivisionDistrict']['DivisionDistrictName']) ? $btn['DivisionDistrict']['DivisionDistrictName'] : 'Not defined' }}</td>

                @if($_functional == false)
                    <td>
                        @if ($btn['SiteAddress'])
                            @include('partials._address', ['address' => $btn['SiteAddress'], 'useNotDefined' => false])
                        @else
                            Not defined
                        @endif
                    </td>
                @endif

                @if($_functional == false)
                    <td>
                        <a href="{{ route('dashboard.inventory.show', [$btn]) }}"><i class="fa fa-eye"></i></a>
                    </td>

                    @can('edit')
                        <td>
                            @if($btn['BTNStatusType']->isActive())
                                <a href="#" data-confirmation-btn="true"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            @endif
                        </td>
                    @endcan
                @elseif($_change)
                    <td>
                        <a href="#" data-confirmation-btn="true"><i class="fa fa-check-square-o" aria-hidden="true"></i></a>
                    </td>
                @elseif($_circuit)
                    <td>
                        <a href="{{ route('dashboard.inventory.circuits.create', [$btn, 'category' => \App\Models\Category::VOICE]) }}" ><i class="fa fa-check-square-o" aria-hidden="true"></i></a>
                    </td>
                @endif

                @if($_functional == true)
                    <td data-confirmation-body colspan="6">
                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                        Are you sure you want to update the invoice?

                        <a href="{{ route('dashboard.inventory.accounts-payable.apply', [$btn, request('invoice')]) }}">Yes</a>
                        <a href="#" data-confirmation-btn="false">No</a>
                    </td>
                @else
                    @can('edit')
                        <td data-confirmation-body colspan="8" class="text-left">
                            <form style="display: none;" method="POST" action="{{ route('dashboard.inventory.destroy', [$btn]) }}">
                                {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                <input type="hidden" name="page" value="{{ session()->get('inventoryIndexRequest.page') }}" />
                            </form>

                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                            Are you sure you want to mark it as deleted?

                            <a href="" data-delete-form>Yes</a>
                            <a href="#" data-confirmation-btn="false">No</a>
                        </td>
                    @endcan
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ $_functional ? 5 : 8 }}" class="text-center no-highlight">
                    <h3 class="not-found">No results displayed</h3>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="col-lg-12 text-center">
    {!! $BTNAccounts->appends(session()->get('inventoryIndexRequest'))->links() !!}
</div>
