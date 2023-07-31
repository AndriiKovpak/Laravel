<div class="col-12" id="list_carriers">
    <br>
    <div class="list_carriers">
        <div class="row">
            <div class="col-10">
                <h3 class="carrier_header" style="color:white;">LIST CARRIERS BY</h3>
            </div>
            <div class="col-2 align-right" style="text-align:right;">
                <a id="hide_carrier_list" class="close" data-dismiss="alert" aria-label="close">×</a>
            </div>
        </div>
        <br>
        <div style="text-align:center;">
            <a class='letters' href="{{route('dashboard.carriers.index')}}?ListBy=all">All</a>
            <a>|</a>
            @for($i = 0;$i <26; $i++)
                @if(chr(97+$i) == $ListBy)
                    <a class='letters' style="color:white; text-decoration: underline; font-size:1.7em;" href="{{route('dashboard.carriers.index')}}?ListBy={{chr(97+$i)}}">{{  strtoupper(chr(97+$i)) }}</a>
                @else
                    <a class='letters' href="{{route('dashboard.carriers.index')}}?ListBy={{chr(97+$i)}}">{{  strtoupper(chr(97+$i)) }}</a>
                @endif
                @if($i < 25)
                    <a>|</a>
                @endif
            @endfor
        </div>
    </div>
</div>

