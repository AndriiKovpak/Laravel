<div class="row">
    <div class="col-6 mt-1">
        <div class="form-group">
            <label class="d-block">Billing URL</label>
            <div class="d-block">
                <span>@notdefined($CarrierDetails['BillingURL'])</span>
            </div>
        </div>
    </div>
    <div class="col-6 mt-1">
        <div class="form-group">
            <label class="d-block">Invoice Available Date</label>
            <div class="d-block">
                <span>@notdefined($CarrierDetails['InvoiceAvailableDate'])</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6 mt-1">
        <div class="form-group">
            <label class="d-block">Username</label>
            <div class="d-block">
                <span>@notdefined($CarrierDetails['Username'])</span>
            </div>
        </div>
    </div>
    <div class="col-6 mt-1">
        <div class="form-group">
            <label class="d-block">Password</label>
            <div class="d-block">
                <span>@notdefined($CarrierDetails['Password'])</span>
            </div>
        </div>
    </div>
</div>

<hr />

<div class="row">
    <div class="col-6 mt-1">
        <label class="d-block">Paperless</label>
        @if(isset($CarrierDatails['IsPaperless']) && $CarrierDetails['IsPaperless'])
        <span>Yes</span>
        @else
        <span>No</span>
        @endif
    </div>
    <div class="col-6 mt-1">
        <div class="form-group">
            <label class="d-block">PIN</label>
            <div class="d-block">
                <span>@notdefined($CarrierDetails['PIN'])</span>
            </div>
        </div>
    </div>
</div>

<hr />

<div class="row">
    <div class="col-12 mt-1">
        <div class="form-group">
            <label class="d-block">Notes</label>
            @forelse(Arr::get($CarrierDetails, 'Notes', []) as $Note)
            <p>{{ $Note->DetailNotes }} </p>
            @empty
            <i class="text-muted">Not defined</i>
            @endforelse
        </div>
    </div>
</div>
