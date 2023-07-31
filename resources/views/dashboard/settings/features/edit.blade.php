<form action="{{ route('dashboard.settings.features.update', [$featureType->FeatureType]) }}" method="post">
    {{ csrf_field() }}
    <input name="_method" type="hidden" value="PUT">

    <div class="form-group">
        <label for="feature-name" class="form-control-label">Feature Name:</label>
        <input type="text" class="form-control" id=feature-name" name="FeatureName" value="{{ $featureType->FeatureName }}">
    </div>

    <div class="form-group">
        <label for="feature-code" class="form-control-label">Feature Code:</label>
        <input type="text" class="form-control" id=feature-code" name="FeatureCode" value="{{ $featureType->FeatureCode }}">
    </div>

    <div class="form-group">
        <label for="category-id" class="form-control-label">Category:</label>
        <select id="category-id" name="CategoryID" class="form-control">
            @foreach($categories as $category)
                <option value="{{$category->CategoryID}}" {{ $category->CategoryID == $featureType->CategoryID ? 'selected' : '' }}>{{ $category->CategoryName }}</option>
            @endforeach
        </select>
    </div>
</form>
