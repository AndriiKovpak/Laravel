
@if((isset($address) && $address->__toString()) || (isset($useNotDefined) && $useNotDefined === false))
    {{ $address['SiteName'] }}@if($address['SiteName'])<br>@endif
    {{ $address['RemittanceName'] }}@if($address['RemittanceName'])<br>@endif
    {{ $address['Address1'] }}@if($address['Address1'])<br>@endif
    {{ $address['Address2'] }}@if($address['Address2'])<br>@endif
    {{ $address['City'] }}@if($address['City']), @endif{{ $address['State'] }} {{ $address['Zip'] }}
@else
    <i class="text-muted">Not defined</i>
@endif
