<form action="{{ route('dashboard.settings.division-districts.update', [$divisionDistrict->DivisionDistrictID]) }}" method="post">
    {{ csrf_field() }}
    <input name="_method" type="hidden" value="PUT">
    <div class="form-group">
        <label for="division-district-code" class="form-control-label">District Code:</label>
        <input type="text" class="form-control" id="division-district-code" name="DivisionDistrictCode" value="{{ $divisionDistrict->DivisionDistrictCode }}">
    </div>
</form>
