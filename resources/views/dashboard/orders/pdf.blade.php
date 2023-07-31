<style>
    p{
        font-family: Calibri, Arial, sans-serif;
        font-size:.8em;
    }
    h2{
        font-family: Calibri, Arial, sans-serif;
    }
    th, td {
        width:50%;
        text-align:left;
    }
    td{
        padding-bottom:15px;
    }
    .title {
        font-weight:bold;
    }
    table {
        font-size:.8em;
        font-family: Calibri, Arial, sans-serif;
        width:100%;
    }
</style>

<div style="padding:15px;">
    <h2 class="common-heading" style="border-bottom:8px solid #cbcbcb; font-size:1.5em;">New ACE-IT Order</h2>
    <table style="width:100%">
        <tr>
            <th class="title">SNOW #</th>
            <th class="title">Telco #</th>
        </tr>
        <tr>
            <td>{{$Order->ACEITOrderNum ?: $message}}</td>
            <td>{{$Order->CarrierOrderNum ?: $message}}</td>
        </tr>
        <br>
        <tr>
            <th class="title">Account #</th>
            <th class="title">BTN #</th>
        </tr>
        <tr>
            <td>{{$BTNAccount->AccountNum ?: $message}}</td>
            <td>{{$BTNAccount->BTN ?: $message}}</td>
        </tr>
    </table>
    @if($Circuit)
        <h2 class="common-heading" style="border-bottom:8px solid #cbcbcb; font-size:1.5em;">New Circuit</h2>
    @else
        <h2 class="common-heading" style="border-bottom:8px solid #cbcbcb; font-size:1.5em;">New BTN Account</h2>
    @endif
        <table style="width:100%">
            @foreach(array_chunk($OrderInfo,2,true) as $value)
                <?php $i = 0; $row1 ='';  $row2 ='';?>
                @foreach($value as $key=>$field)
                        @if(!trim($field))
                            <?php $field = $message; ?>
                        @endif
                        @if($i % 2 == 0)
                            <?php $row1 = '<tr><th>' . $key . '</th>'; ?>
                            <?php $row2 = '<tr><td>'. $field . '</td>'; ?>
                            @if(count($value) == 1)
                                <tr>
                                    <th>{{$key}}</th>
                                </tr>
                                <tr>
                                    <td>{{$field}}</td>
                                </tr>
                            @endif
                        @else
                            <?php $row1 .= '<th class="title">' . $key . '</th></tr>'; ?>
                            <?php $row2 .= '<td>' . $field .'</td></tr>'; ?>
                            <?php echo $row1 . $row2; ?>
                        @endif()
                        <?php $i++; ?>
                @endforeach
            @endforeach
        </table>
    @if($Circuit)
        <p style="font-weight:bold; margin-left:5px;">Features</p>
        @if($Features)
        @foreach($Features as $feature)
            <p style="width:100%; margin-left:5px;"> {{$feature['FeatureName']}} (Cost:${{$feature['Amount']}}) </p>
        @endforeach
        @else
            <p style="width:100%; margin-left:5px;">No Features </p>
        @endif
    @endif
    <p style="font-weight:bold; margin-left:5px;">Notes</p>
    @foreach($Notes as $note)
        <p style="width:100%; margin-left:5px;">{{$note}}</p>
    @endforeach
</div>
