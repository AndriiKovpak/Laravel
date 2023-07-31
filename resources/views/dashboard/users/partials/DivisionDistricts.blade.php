<div class="row" id="DivisionDistricts" @if(Arr::get($user, 'SecurityGroup' , 1)=='1' )style="display:none" @endif>
    <div class="col-5 col-md-4 col-lg-3">
        <label>Available Districts</label>
        <select name="from[]" id="multiselect" class="form-control DivisionDistrictsScroll" size="8" multiple="multiple">
            @if(old('from'))
            @foreach(old('from') as $selectedID)
            @foreach($allDistricts as $id => $name)
            @if($selectedID == $id)
            <option value="{{$id}}">{{$name}}</option>
            @endif
            @endforeach
            @endforeach
            @else
            @foreach($otherDistricts as $district)
            <option value="{{$district['id']}}">{{$district['name']}}</option>
            @endforeach
            @endif
        </select>
    </div>

    <div class="col-2 district_navigation">
        <button type="button" id="multiselect_rightAll" class="btn btn-block"><i class="fa fa-chevron-right"></i><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
        <button type="button" id="multiselect_rightSelected" class="btn btn-block"><i class="fa fa-chevron-right"></i></button>
        <button type="button" id="multiselect_leftSelected" class="btn btn-block"><i class="fa fa-chevron-left"></i></button>
        <button type="button" id="multiselect_leftAll" class="btn btn-block"><i class="fa fa-chevron-left"></i><i class="fa fa-chevron-left"></i></button>
    </div>

    <div class="col-5 col-md-4 col-lg-3">
        <label>Selected Districts</label>
        <select name="to[]" id="multiselect_to" class="form-control DivisionDistrictsScroll" size="8" multiple="multiple">
            @if(old('to'))
            @foreach(old('to') as $selectedID)
            @foreach($allDistricts as $id =>$name)
            @if($selectedID == $id)
            <option value="{{$id}}">{{$name}}</option>
            @endif
            @endforeach
            @endforeach
            @else
            @if(isset($selectedDistricts))
            @foreach($selectedDistricts as $district)
            <option value="{{$district['DivisionDistrictID']}}">{{$district['DivisionDistrictName']}}</option>
            @endforeach
            @endif
            @endif
        </select>
    </div>
</div>
