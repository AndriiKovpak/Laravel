<form action="{{ route('dashboard.settings.division-districts.store') }}" method="post">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="division-district-code" class="form-control-label">District Code:</label>
        <input type="text" class="form-control" id="division-district-code" name="DivisionDistrictCode">
    </div>
</form>