<div class="container inventory-circuits-form order-form">
    <div class="row">
        <div class="col-md-12">
            <a href="@if($back == 'inventory'){{route('dashboard.inventory.index')}}@else{{route('dashboard.orders.index')}}@endif" class="nav_button">
                &lt; Back </a>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <h2 class="common-heading" style="border-bottom:8px solid #cbcbcb; font-size:1.5em;">@if($new) New Order @else Edit Order @endif</h2>
        </div>
    </div>
    {!! csrf_field() !!}
    <input type="hidden" name="page" value="{{ request('page') }}" />
    <div class="Order_Inputs">
        <div class="row">
            <div class="col-md-6 mt-1">
                <div class="form-group {{ $errors->has('ACEITOrderNum') ? 'has-danger' : '' }}">
                    <label class="d-block" for="ACEITOrderNum">SNOW #</label>
                    <div class="d-block">
                        <input type="text" id="ACEITOrderNum" name="ACEITOrderNum" maxlength="50" class="form-control" value="{{ old('ACEITOrderNum', Arr::get($Order, 'ACEITOrderNum', Arr::get($request, 'ACEITOrderNum'))) }}">
                        <span class="text-danger form-text">{{ $errors->first('ACEITOrderNum') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-1">
                <div class="form-group {{ $errors->has('CarrierOrderNum') ? 'has-danger' : '' }}">
                    <label class="d-block" for="CarrierOrderNum">Telco #</label>
                    <div class="d-block">
                        <input type="text" id="CarrierOrderNum" name="CarrierOrderNum" maxlength="50" class="form-control" value="{{ old('CarrierOrderNum', Arr::get($Order, 'CarrierOrderNum', Arr::get($request, 'CarrierOrderNum') )) }}">
                        <span class="text-danger form-text">{{ $errors->first('CarrierOrderNum') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mt-1">
                <div class="form-group {{ $errors->has('AccountNum') ? 'has-danger' : '' }}">
                    <label class="d-block" for="AccountNum">Account #</label>
                    <div class="d-block">
                        <input type="text" id="AccountNum" name="AccountNum" maxlength="50" class="form-control" value="{{ old('AccountNum',Arr::get($BTNAccount, 'AccountNum', Arr::get($request, 'AccountNum'))) }}">
                        <span class="text-danger form-text">{{ $errors->first('AccountNum') }}</span>
                        <label id="account-found">Account found.</label>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-1">
                <div class="form-group {{ $errors->has('BTN') ? 'has-danger' : '' }}">
                    <label class="d-block" for="BTN">BTN #</label>
                    <div class="d-block">
                        <input type="text" id="BTN" name="BTN" maxlength="50" class="form-control" value="{{ old('BTN',Arr::get($BTNAccount, 'BTN', Arr::get($request, 'BTN'))) }}">
                        <span class="text-danger form-text">{{ $errors->first('BTN') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mt-1">
                <div class="form-group {{ $errors->has('OrderDate') ? 'has-danger' : '' }}">
                    <label class="d-block" for="OrderDate">Order Date</label>
                    <div class="d-block">
                        <input type="date" id="OrderDate" name="OrderDate" maxlength="50" class="form-control" value="{{ old('OrderDate',Arr::has($request, 'OrderDate') ? Carbon\Carbon::parse($request['OrderDate'])->format('Y-m-d') : (Arr::has($Order, 'OrderDate') ? $Order['OrderDate']->format('Y-m-d')  : null )) }}">
                        <span class="text-danger form-text">{{ $errors->first('OrderDate') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="BTNAccountExists" name="BTNAccountExists" maxlength="50" class="form-control" value="{{ old('BTNAccountExists',Arr::get($request, 'BTNAccountExists', $BTNAccountID)) }}">
    </div>
    @if(!$new && $Category || $new)
    <div id="ShowCircuitForm">
        <br>
        <div class="row">
            <div class="col-md-12">
                <h2 class="common-heading" style="border-bottom:8px solid #cbcbcb; font-size:1.5em;">Circuit</h2>
            </div>
        </div>
        @include('dashboard.inventory.circuits.general-information.partials._form',['order' => true])
    </div>
    @endif
    @if(!$new && !$Category || $new)
    <div id="ShowBTNForm">
        @include('dashboard.orders.partials._BTNForm')
    </div>
    @endif
    {{--<input type="file" class="form-control-file" id="file" name="file-upload">--}}
    {{--TODO: Test this across browsers and move the CSS.--}}
    <style>
        .new-custom-file {
            display: block;
        }

        .new-custom-file-input {
            width: 0;
            height: 0;
            display: block;
            overflow: hidden;
        }

        .new-custom-file-control {
            overflow: hidden;
            font-family: sans-serif;
            font-size: 12px;
            line-height: 28px;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .form-control.focus {
            color: #464a4c;
            background-color: #ffffff;
            border-color: #c1c2c3;
            outline: none;
        }
    </style>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group {{ $errors->has('OrderFiles.*') ? 'has-danger' : '' }}">
                <label class="d-block">Files</label>
                <div class="d-block">
                    @if($Order)
                    @if(isset($Order['Files']))
                    <ul class="mb-3">
                        @foreach($Order['Files'] as $File)
                        <li>
                            <small class="text-muted">{{ $File['Created_at']->format('m/d/Y') }} &bull;</small>
                            <a target="_blank" href="{{route('dashboard.orders.view-attachment', [ 'file' => basename($File->FilePath)])}} ">{{ basename($File->OriginalName) }}</a>
                            <a href="{{ route('dashboard.orders.delete-attachment', [ 'file' => $File]) }}"><i class="fa fa-times"></i></a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                    @endif
                    <script>
                        function addFileField() {
                            $('.new-files').append($('\
                                    <label class="new-custom-file mb-1">\
                                        <input\
                                            class="new-custom-file-input"\
                                            type="file"\
                                            name="OrderFiles[]"\
                                            onchange="$(this).next().html($(this).prop(\'files\')[0].name ? $(this).prop(\'files\')[0].name + \' <a onclick=&quot;$(this).parent().parent().remove(); return false;&quot;><i class=&quot;fa fa-times&quot;></i></a>\' : \'Choose File&#8230;\'); addFileField();"\
                                            onfocus="$(this).next().addClass(\'focus\')"\
                                            onblur="$(this).next().removeClass(\'focus\')">\
                                        <div class="form-control new-custom-file-control">Choose File&#8230;</div>\
                                    </label>\
                                '));
                        }
                        $(addFileField);
                    </script>
                    <div class="new-files"></div>
                    <span class="text-danger form-text">{{ $errors->first('OrderFiles.*') }}</span>
                </div>
            </div>
        </div>
    </div>
    <br>
</div>
