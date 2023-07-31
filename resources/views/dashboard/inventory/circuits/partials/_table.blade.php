@inject('btn', 'App\Services\View\BTN')

<h2>ACCT # {{ $btn->getAccountNumber() }}</h2>

<div class="clearfix"></div>

<div class="mt-3">
    <form action="{{ route('dashboard.inventory.circuits.index', [$BTNAccount]) }}" class="simple-search">
        <input name="search" type="text" value="{{old('search')}}" class="form-control search-field" placeholder="Search Circuits">
        <button class="search-btn"><i class="fa fa-search"></i></button>
    </form>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
        <tr>
            <th>Circuit ID</th>
            <th>Service</th>
            <th>Details</th>
        </tr>
        </thead>
        <tbody>
        @foreach($Circuits as $Circuit)
            <tr class="{{ $btn->isActiveCircuit($Circuit) ? 'table-active' : '' }}">
                <td>{{$Circuit['CarrierCircuitID']}}</td>
                <td>@notdefined($Circuit['Category'])</td>
                <td>
                    <a href="{{ route('dashboard.inventory.circuits.show', [$BTNAccount, $Circuit, 'page' => $page]) }}"><i class="fa fa-eye"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<hr/>
<div class="pagination-narrow">
@if(isset($CategoryID))
    {!! $Circuits->appends(['page' => $page, 'did-page' => request('did-page'), request('mac-page'), 'category' => $CategoryID ])->links() !!}
@else
    {!! $Circuits->appends(['page' => $page, 'did-page' => request('did-page'), request('mac-page')])->links() !!}
@endif
</div>
