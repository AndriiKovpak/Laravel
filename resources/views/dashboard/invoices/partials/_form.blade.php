{!! csrf_field() !!}
<div class="row">
    <div class="col-md-12 mt-1">
        <div class="form-group {{ $errors->has('ProcessedMethod') ? 'has-danger': '' }}">
            <label class="d-block control-label">Processed</label>
            @foreach($_options['ProcessedMethod'] as $value => $title)
            @if($value != 4 ||
                ($value == 4 && Arr::get($invoice, 'ProcessedMethod') == 4) ||
                ($value == 4 && Arr::get($invoice, 'ProcessedMethod') == 1 && app('request')->input('new') == "1")
            )
            <label class="custom-control custom-radio" style="display: inline-block;">
                <input id="ProcessedMethod{{ $value }}" {{ old('ProcessedMethod', Arr::get($invoice, 'ProcessedMethod')) == $value ? 'checked' : '' }} value="{{ $value }}" name="ProcessedMethod" type="radio" class="custom-control-input">
                <span class="custom-control-indicator" style="top: 0;"></span>
                <span class="custom-control-description" style="color: #000;">{{ $title }}</span>
            </label>
            @endif
            @endforeach
            <span class="form-text text-danger">{{ $errors->first('ProcessedMethod') }}</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="d-block">Account #</label>
            <div>
                <span>{{ Arr::get($invoice, 'BTNAccount.AccountNum', Arr::get($BTNAccount, 'AccountNum')) }}</span>
                @if(isset($BTNAccount))
                <input type="hidden" name="BTNAccountID" value="{{ $BTNAccount->BTNAccountID }}">
                @endif
            </div>
        </div>
    </div>
    @if($invoice->ScannedImage)
    <div class="col-md-6">
        <div class="form-group clearfix">
            <label class="d-block">&nbsp;</label>
            <div class="d-block">
                <span><a target="_blank" href="{{route('dashboard.invoices.scanned-images', $invoice->ScannedImage->ScannedImageID)}}" class="grey-color"><i class="fa fa-file-pdf-o"></i> View Image</a></span>
            </div>
        </div>
    </div>
    @endif
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('InvoiceNum') ? 'has-danger': '' }}">
            <label for="InvoiceNum" class="d-block">Invoice #</label>
            <input id="InvoiceNum" name="InvoiceNum" type="text" maxlength="50" class="form-control" value="{{ old('InvoiceNum', Arr::get($invoice, 'InvoiceNum')) }}">
            <span class="form-text text-danger">{{ $errors->first('InvoiceNum') }}</span>
        </div>
    </div>
    @if($invoice->ScannedImage)
    <div class="col-md-6">
        <div class="form-group">
            <label for="ProcessCode" class="d-block">Process Code</label>
            <select id="ProcessCode" name="ProcessCode" class="form-control">
                <option value="">All</option>
                @foreach($_options['processCodes'] as $code)
                <option value="{{ $code->ProcessCode }}" {{ old('ProcessCode', Arr::get($invoice, 'ScannedImage.ProcessCode')) == $code->ProcessCode ? 'selected' : ''}}>{{ $code->ProcessCodeName }}</option>
                @endforeach
            </select>
            <span class="form-text text-danger">{{ $errors->first('ProcessCode') }}</span>
        </div>
    </div>
    @endif
</div>

<hr>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('BillDate') ? 'has-danger': '' }}">
            <label for="BillDate" class="d-block">Bill Date</label>
            <input id="BillDate" name="BillDate" type="date" value="{{ old('BillDate', Arr::has($invoice, 'BillDate') ? $invoice['BillDate']->format('Y-m-d') : null) }}" class="form-control">
            <span class="form-text text-danger">{{ $errors->first('BillDate') }}</span>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('DueDate') ? 'has-danger': '' }}">
            <label for="DueDate" class="d-block">Due Date</label>
            <input id="DueDate" name="DueDate" type="date" value="{{ old('DueDate', Arr::has($invoice, 'DueDate') ? $invoice['DueDate']->format('Y-m-d') : null) }}" class="form-control">
            <span class="form-text text-danger">{{ $errors->first('DueDate') }}</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('ServiceFromDate') ? 'has-danger': '' }}">
            <label for="ServiceFromDate" class="d-block">Service From</label>
            <input id="ServiceFromDate" name="ServiceFromDate" type="date" value="{{ old('ServiceFromDate', Arr::has($invoice, 'ServiceFromDate') ? $invoice['ServiceFromDate']->format('Y-m-d') : null) }}" class="form-control">
            <span class="form-text text-danger">{{ $errors->first('ServiceFromDate') }}</span>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('ServiceToDate') ? 'has-danger': '' }}">
            <label for="ServiceToDate" class="d-block">Service To</label>
            <input id="ServiceToDate" name="ServiceToDate" type="date" value="{{ old('ServiceToDate', Arr::has($invoice, 'ServiceToDate') ? $invoice['ServiceToDate']->format('Y-m-d') : null) }}" class="form-control">
            <span class="form-text text-danger">{{ $errors->first('ServiceToDate') }}</span>
        </div>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('IsFinalFill') ? 'has-danger': '' }}">
            <label class="d-block">Final Bill</label>
            @foreach([0, 1] as $value)
            <label class="custom-control custom-radio" style="display: inline-block;">
                <input id="IsFinalBill{{ $value }}" name="IsFinalBill" type="radio" {{ old('IsFinalBill', intval(Arr::get($invoice, 'IsFinalBill', 0))) == $value ? 'checked' : '' }} value="{{ $value }}" class="custom-control-input">
                <span class="custom-control-indicator" style="top: 0;"></span>
                <span class="custom-control-description" style="color: #000;">{{ ($value == 1) ? 'Yes': 'No' }}</span>
            </label>
            @endforeach
            <span class="form-text text-danger">{{ $errors->first('IsFinalBill') }}</span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('CurrentChargeAmount') ? 'has-danger': '' }}">
            <label for="CurrentChargeAmount" class="d-block">Current Charges</label>
            <input id="CurrentChargeAmount" name="CurrentChargeAmount" value="{{ old('CurrentChargeAmount', Arr::get($invoice, 'CurrentChargeAmount')) }}" class="form-control">
            <span class="form-text text-danger">{{ $errors->first('CurrentChargeAmount') }}</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('PastDueAmount') ? 'has-danger': '' }}">
            <label for="PastDueAmount" class="d-block">Past Due Amount</label>
            <input id="PastDueAmount" name="PastDueAmount" value="{{ old('PastDueAmount', Arr::get($invoice, 'PastDueAmount')) }}" class="form-control">
            <span class="form-text text-danger">{{ $errors->first('PastDueAmount') }}</span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('CreditAmount') ? 'has-danger': '' }}">
            <label for="CreditAmount" class="d-block">Credit</label>
            <input id="CreditAmount" name="CreditAmount" value="{{ old('CreditAmount', Arr::get($invoice, 'CreditAmount')) }}" class="form-control">
            <span class="form-text text-danger">{{ $errors->first('CreditAmount') }}</span>
        </div>
    </div>
</div>

<hr>

@include('partials._address-form', [
'type' => 'Remittance',
'title' => 'Remittance',
'AddressType' => null,
'Address' => $Addresses,
])

<br>

<hr>

<div class="row">
    <div class="col-md-12">
        <div class="form-group {{ $errors->has('Note') ? 'has-danger': '' }}">
            <label for="Note" class="d-block">Note</label>
            <input id="Note" name="Note" type="text" maxlength="500" value="{{ old('Note', Arr::get($invoice, 'Note')) }}" class="form-control">
            <span class="form-text text-danger">{{ $errors->first('Note') }}</span>
        </div>
    </div>
</div>

<div class="col-xs-12" id="amount-alert-message">
    <div class="alert alert-danger alert-dismissible">
        <div class="row mb-2">
            <div class="col-lg-9 col-md-7 col-sm-8 col-xs-12 mt-2">
                <p class="mb-0">
                    No Current Charges, Past Due Amount, or Credit Amount's were entered. Are you sure this is correct?
                </p>
            </div>
            <div class="col-lg-3 col-md-5 col-sm-4 col-xs-6 mt-2">
                <button class="btn-secondary" id="invoice-no">NO</button>
                <button type="submit" class="btn-primary pull-right">YES</button>
            </div>
        </div>

    </div>
</div>
