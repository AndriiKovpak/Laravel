<form action="{{ route('dashboard.settings.features.store') }}" method="post">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="feature-name" class="form-control-label">Feature Name:</label>
        <input type="text" class="form-control" id=feature-name" name="FeatureName">
    </div>

    <div class="form-group">
        <label for="feature-code" class="form-control-label">Feature Code:</label>
        <input type="text" class="form-control" id=feature-code" name="FeatureCode">
    </div>

    <div class="form-group">
        <label for="category-id" class="form-control-label">Category:</label>
        <select id="category-id" name="CategoryID" class="form-control">
            @foreach($categories as $category)
                <option value="{{ $category->CategoryID }}">{{ $category->CategoryName }}</option>
            @endforeach
        </select>
    </div>
</form>