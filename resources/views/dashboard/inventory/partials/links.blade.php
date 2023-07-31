<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ $active == 'info' ? 'active' : '' }}" href="{{ route('dashboard.inventory.show', [$BTNAccount]) }}"><span>General Info</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active == 'accounts-payable' ? 'active' : '' }}" href="{{ route('dashboard.inventory.accounts-payable.index', [$BTNAccount]) }}"><span>Accounts Payable</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active == 'mac' ? 'active' : '' }}" href="{{ route('dashboard.inventory.mac.index', [$BTNAccount]) }}"><span>MAC</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active == 'circuits' ? 'active' : '' }}" href="{{ route('dashboard.inventory.circuits.index', [$BTNAccount]) }}"><span>Circuits</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active == 'csr' ? 'active' : '' }}" href="{{ route('dashboard.inventory.csr.index', [$BTNAccount]) }}"><span>CSRs/Orders</span></a>
    </li>
</ul>
