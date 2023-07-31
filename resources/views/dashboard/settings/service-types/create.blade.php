<form action="{{ route('dashboard.settings.service-types.store') }}" method="post">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="service-type-name" class="form-control-label">Service Type:</label>
        <input type="text" class="form-control" id=service-type-name" name="ServiceTypeName">
    </div>

    <div class="form-group">
        <label for="category" class="form-control-label">Category:</label>
        <select name="Category" class="form-control service-type-category">
            @foreach($categories as $category)
                <option value="{{$category->CategoryID}}" >{{ $category->CategoryName }}</option>
            @endforeach
        </select>
    </div>
</form>