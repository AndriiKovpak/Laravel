<form method="post" action='{{route('dashboard.inventory.accounts-payable.carrier-edit', [$BTNAccount])}}' style="margin:0" id="carrier_detail_form">
    {{ csrf_field() }}

    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('BillingURL') ? 'has-danger' : '' }}">
                <label for="BillingURL" class="control-label">Billing URL</label>
                <input value="{{old('BillingURL', $CarrierDetails['BillingURL'] ?? '')}}" name="BillingURL" type="text" class="form-control" maxlength="100">
                <span class="form-control-feedback">{{ $errors->first('BillingURL') }}</span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('InvoiceAvailableDate') ? 'has-danger' : '' }}">
                <label for="InvoiceAvailableDate" class="control-label">Invoice Available Date</label>
                <input value="{{old('InvoiceAvailableDate', $CarrierDetails['InvoiceAvailableDate'] ?? '')}}" name="InvoiceAvailableDate" type="text" class="form-control" maxlength="50">
                <span class="form-control-feedback">{{ $errors->first('InvoiceAvailableDate') }}</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('Username') ? 'has-danger' : '' }}">
                <label for="Username" class="control-label">Username</label>
                <input value="{{old('Username', $CarrierDetails['Username'] ?? '')}}" name="Username" type="text" class="form-control" maxlength="100">
                <span class="form-control-feedback">{{ $errors->first('Username') }}</span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('Password') ? 'has-danger' : '' }}">
                <label for="Password" class="control-label">Password</label>
                <input value="{{old('Password', $CarrierDetails['Password'] ?? '')}}" name="Password" type="text" class="form-control" maxlength="250">
                <span class="form-control-feedback">{{ $errors->first('Password') }}</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('IsPaperless') ? 'has-danger' : '' }}">
                <input class="custom-check" {{(old('IsPaperless', $CarrierDetails['IsPaperless'] ?? '') == '1' ? 'checked': '')}} name="IsPaperless" id="IsPaperless" type="checkbox" value="1">
                <label for="IsPaperless">Paperless</label>
                <span class="form-control-feedback">{{ $errors->first('IsPaperless') }}</span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('PIN') ? 'has-danger' : '' }}">
                <label for="PIN" class="control-label">PIN</label>
                <input value="{{old('PIN', $CarrierDetails['PIN'] ?? '')}}" name="PIN" type="text" class="form-control" maxlength="100">
                <span class="form-control-feedback">{{ $errors->first('PIN') }}</span>
            </div>
        </div>
    </div>

    <hr />

    <div id="CarrierDetailsNotesRows">
        <div class="row CarrierDetailsNotesRow d-none" id="CarrierDetailsNoteTemplate">
            <div class="col-xs-10 col-sm-11">
                <div class="form-group">
                    <label class="control-label CarrierDetailsNoteLabel">Note</label>
                    <input type="hidden" value="" class="CarrierDetailsNoteID" />
                    <textarea type="text" class="form-control CarrierDetailsNote" maxlength="4000"></textarea>
                    <span class="form-control-feedback"></span>
                </div>
            </div>
            <div class="col-xs-1">
                <a><i class="fa fa-times deleteNote" aria-hidden="true"></i></a>
            </div>
        </div>
        @foreach(old('Notes', Arr::get($CarrierDetails, 'Notes', [])) as $index => $Note)
        <div class="row CarrierDetailsNotesRow">
            <div class="col-xs-10 col-sm-11">
                <div class="form-group {{ $errors->has('Notes.'.$index.'.DetailNotes') ? 'has-danger' : '' }}">
                    <label for="Notes.{{ $index }}.DetailNotes" class="control-label CarrierDetailsNoteLabel">Note</label>
                    <input type="hidden" name="Notes[{{$index}}][BTNAccountCarrierDetailNoteID]" value="{{ Arr::get($Note, 'BTNAccountCarrierDetailNoteID') }}" class="CarrierDetailsNoteID" />
                    <textarea id="Notes.{{ $index }}.DetailNotes" name="Notes[{{ $index }}][DetailNotes]" type="text" class="form-control CarrierDetailsNote" maxlength="4000">{{ Arr::get($Note, 'DetailNotes') }}</textarea>
                    <span class="form-control-feedback">{{ $errors->first('Notes.'.$index.'.DetailNotes')}}</span>
                </div>
            </div>
            <div class="col-xs-1">
                <a><i class="fa fa-times deleteNote" aria-hidden="true"></i></a>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-md-6 addNote">
            <a><i class="fa fa-plus" aria-hidden="true"></i> Add Note</a>
        </div>
    </div>
</form>
