<form action="{{ route('dashboard.settings.circuit-descriptions.store') }}" method="post">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="circuit-description" class="form-control-label">Circuit Description:</label>
        <input type="text" class="form-control" id="circuit-description" name="Description">
    </div>
</form>