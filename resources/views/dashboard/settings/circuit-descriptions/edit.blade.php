<form action="{{ route('dashboard.settings.circuit-descriptions.update', [$CircuitDescription->DescriptionID]) }}" method="post">
    {{ csrf_field() }}
    <input name="_method" type="hidden" value="PUT">
    <div class="form-group">
        <label for="circuit-description" class="form-control-label">Circuit Description:</label>
        <input type="text" class="form-control" id="circuit-description" name="Description" value="{{ $CircuitDescription->Description }}">
    </div>
</form>
