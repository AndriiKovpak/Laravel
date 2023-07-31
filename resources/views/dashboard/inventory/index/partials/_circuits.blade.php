<div class="table-responsive">
    <table class="table table-striped table-sm text-xs-center">
        <thead>
        <tr>
            <th>Circuit ID</th>
            <th>Service</th>
            <th>Status</th>
            <th>Bill Under</th>
            <th>Details</th>
            @can('edit')
                <th>Edit</th>
            @endcan
            @can('edit')
                <th>Delete</th>
            @endcan
        </tr>
        </thead>
        <tbody>
        @forelse($Circuits as $Circuit)
            <tr>
                <td>{{ $Circuit['CarrierCircuitID'] }}</td>
                <td>{{ $Circuit['Category'] }}</td>
                <td>{{ $Circuit['StatusType'] }}</td>
                <td>{{ $Circuit['BillUnderBTN'] }}</td>
                <td>
                    @if($loc === 'Main')
                    <a href="{{ route('dashboard.inventory.circuits.show', [$Circuit->BTNAccount, $Circuit, 'page' => session()->get('inventoryIndexRequest.page', request('page')), 'search' => session()->get('inventoryIndexRequest.search')]) }}"><i class="fa fa-eye fa-ac"></i></a>
                    @else
                    <a href="{{ route('dashboard.inventory.circuits.show', [$Circuit->BTNAccount, $Circuit, 'page' => $page, 'search' => $search]) }}"><i class="fa fa-eye fa-ac"></i></a>
                    @endif
                </td>
                @can('edit')
                <td>
                    <a href="{{ route('dashboard.inventory.circuits.edit', [$BTNAccount, $Circuit, 'page' => $page, 'search' => $search]) }}" ><i class="fa fa-pencil fa-ac"></i></a>
                </td>
                @endcan

                @can('edit')
                    <td>
                        @if($Circuit['StatusType']->isActive())
                            <a href="#" data-confirmation-btn="true"><i class="fa fa-trash fa-ac" aria-hidden="true"></i></a>
                        @endif
                    </td>

                    <td data-confirmation-body colspan="7" class="text-left">

                        <form style="display: none;" method="POST" action="{{ route('dashboard.inventory.circuits.destroy', [$Circuit->BTNAccount, $Circuit]) }}">
                            {!! csrf_field() !!} {!! method_field('DELETE') !!}
                            <input type="hidden" name="page" value="{{ session()->get('inventoryIndexRequest.page') }}" />
                            <input type="hidden" name="loc" value="{{ $loc }}" />
                        </form>

                        <i class="fa fa-info-circle fa-ac" aria-hidden="true"></i>
                        Are you sure you want to mark it as deleted?

                        <a href="" data-delete-form>Yes</a>
                        <a href="#" data-confirmation-btn="false">No</a>
                    </td>
                @endcan
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
<div class="col-lg-12 text-center">
    @if($loc === 'Main')
    {!! $Circuits->appends(session()->get('inventoryIndexRequest'))->links() !!}
    @else
    {!! $Circuits->appends(['search' => request('search')])->links() !!}
    @endif
</div>
