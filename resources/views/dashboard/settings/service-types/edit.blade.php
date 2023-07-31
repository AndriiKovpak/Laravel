<form action="{{ route('dashboard.settings.service-types.update', [$serviceType->ServiceType]) }}" method="post">
    {{ csrf_field() }}
    <input name="_method" type="hidden" value="PUT">
    <div class="form-group">
        <label for="service-type-name" class="form-control-label">Service Type Name:</label>
        <input type="text" class="form-control" id=service-type-name" name="ServiceTypeName" value="{{ $serviceType->ServiceTypeName }}">
    </div>

    <div class="form-group">
        <label for="category" class="form-control-label">Category:</label>
        <select name="Category" class="form-control service-type-category">
            @foreach($categories as $category)
                <option value="{{$category->CategoryID}}" @if ($category->CategoryID == $serviceType->Category) selected="selected" @endif>{{ $category->CategoryName }}</option>
            @endforeach
        </select>
    </div>
</form>
