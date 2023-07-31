<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\DivisionDistricts\StoreRequest;
use App\Http\Requests\Settings\DivisionDistricts\UpdateRequest;
use App\Models\DivisionDistrict;
use App\Services\Settings\DivisionDistrictsService;
use Illuminate\Http\Request;


class DivisionDistrictsController extends Controller
{
    public function __construct() {
        $this->middleware('auth.admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $divisionDistricts = DivisionDistrict::where('IsActive', true)->orderBy('DivisionDistrictCode', 'asc')->paginate(12);

        return view('dashboard.settings.division-districts.index',
            ['divisionDistricts' => $divisionDistricts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.settings.division-districts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @param DivisionDistrictsService $divisionDistrictsService
     * @return array
     */
    public function store(StoreRequest $request, DivisionDistrictsService $divisionDistrictsService)
    {
        $divisionDistrictsService->store($request->all(), $request->user());

        $request->session()->flash('notification.success', 'District information was successfully saved.');

        return ['message' => 'District information was successfully saved.'];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  DivisionDistrict $divisionDistrict
     * @return \Illuminate\Http\Response
     */
    public function edit(DivisionDistrict $divisionDistrict)
    {
        return view('dashboard.settings.division-districts.edit', [
            'divisionDistrict' => $divisionDistrict
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param  DivisionDistrict $divisionDistrict
     * @return array
     */
    public function update(UpdateRequest $request, DivisionDistrict $divisionDistrict)
    {
        $divisionDistrict->DivisionDistrictCode = $request->DivisionDistrictCode;
        $divisionDistrict->save();

        $request->session()->flash('notification.success', 'District information was successfully changed.');

        return ['message' => 'District information was successfully changed.'];
    }

    /**
     * Delete DivisionDistrict
     * @param DivisionDistrict $dd
     */
    public function destroy(DivisionDistrict $dd)
    {

        $dd->setAttribute('IsActive', 0);
        $dd->save();

        return redirect()->back()
            ->with('notification.success', "DivisionDistrict has successfully been deleted.");
    }

}
