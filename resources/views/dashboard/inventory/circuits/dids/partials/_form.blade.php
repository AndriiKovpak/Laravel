{!! csrf_field() !!}

<input type="hidden" value="{{ $page }}" name="page" />
<input type="hidden" value="{{ request('search') }}" name="search" />
<input type="hidden" value="{{ request('DID') }}" name="DID-search" />
<input type="hidden" value="{{ request('did-page') }}" name="did-page" />

@if(is_array($CircuitDID))
<div class="row">
    <div class="col-md-6 mt-1">
        <div class="form-group">
            <label class="d-block" for="Type">Type</label>
            <div class="d-block">
                <select name="Type" id="Type" class="form-control">
                    @foreach($_options['Type'] as $value => $title)
                    <option value="{{ $value }}" {{ $value == old('Type', 'single') ? 'selected' : '' }}>{{ $title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row single-input">
    <div class="col-md-12 mt-1">
        <div class="form-group {{ $errors->has('DID') ? 'has-danger': '' }}">
            <label class="d-block control-label" for="DID">DID</label>
            <div class="d-block">
                <input type="text" id="DID" name="DID" class="form-control" value="{{ old('DID', Arr::get($CircuitDID, 'DID')) }}" />
                <span class="form-text text-danger">{{ $errors->first('DID') }}</span>
            </div>
        </div>
    </div>
</div>

@if(is_array($CircuitDID))
<div class="row range-input">
    <div class="col-md-6 mt-1">
        <div class="form-group {{ $errors->has('DIDPrefix') ? 'has-danger': '' }}">
            <label class="d-block control-label" for="DIDPrefix">DID Range Prefix</label>
            <div class="d-block">
                <input type="text" id="DIDPrefix" name="DIDPrefix" class="form-control" value="{{ old('DIDPrefix') }}" />
                <span class="form-text text-danger">{{ $errors->first('DIDPrefix') }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 mt-1 range-input">
        <div class="form-group {{ $errors->has('DIDFrom') ? 'has-danger': '' }}">
            <div class="d-block">
                <label class="d-block control-label" for="DIDFrom">DID Range Start</label>
                <input type="number" id="DIDFrom" min="0" max="9999" name="DIDFrom" class="form-control" value="{{ old('DIDFrom') }}" />
                <span class="form-text text-danger">{{ $errors->first('DIDFrom') }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 mt-1 range-input">
        <div class="form-group {{ $errors->has('DIDTo') ? 'has-danger': '' }}">
            <div class="d-block">
                <label class="d-block control-label" for="DIDTo">DID Range End</label>
                <input type="number" id="DIDTo" min="0" max="9999" name="DIDTo" class="form-control" value="{{ old('DIDTo') }}" />
                <span class="form-text text-danger">{{ $errors->first('DIDTo') }}</span>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-12 mt-1">
        <div class="form-group {{ $errors->has('DIDNote') ? 'has-danger' : '' }}">
            <label class="d-block" for="DIDNote">DID Notes</label>
            <div class="d-block">
                <textarea rows="10" style="height: 100px;" class="form-control" maxlength="500" id="DIDNote" name="DIDNote">{{ old('DIDNote', Arr::get($CircuitDID, 'DIDNote')) }}</textarea>
                <span class="form-text text-danger">{{ $errors->first('DIDNote') }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row mt-1">
    <div class="col-md-6">
        <div class="form-group">
            <a href="{{ route('dashboard.inventory.circuits.show', [$BTNAccount, $Circuit, 'page' => $page, 'search' => request('search'), 'DID' => request('DID'), 'did-page' => request('did-page')]) }}" class="btn-secondary">CANCEL</a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <button type="submit" class="btn-primary pull-right">SAVE</button>
        </div>
    </div>
</div>
