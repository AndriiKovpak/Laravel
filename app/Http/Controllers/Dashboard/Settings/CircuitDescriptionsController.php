<?php

namespace App\Http\Controllers\Dashboard\Settings;
use App\Http\Controllers\Controller;
use App\Models\Circuit;
use App\Models\CircuitDescription;
use Illuminate\Http\Request;
use App\Http\Requests\Settings\CircuitDescriptions\StoreRequest;

class CircuitDescriptionsController extends Controller
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
        $CircuitDescriptions = CircuitDescription::orderBy('Description', 'asc')->paginate(12);

        return view('dashboard.settings.circuit-descriptions.index',
            ['CircuitDescriptions' => $CircuitDescriptions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.settings.circuit-descriptions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @param CircuitDescription $circuitDescription
     * @return array
     */
    public function store(StoreRequest $request, CircuitDescription $circuitDescription)
    {
        $circuitDescription->create($request->all());

        $request->session()->flash('notification.success', 'New Circuit Description successfully created.');

        return ['message' => 'New Circuit Description successfully created.'];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CircuitDescription $circuitDescription
     * @return \Illuminate\Http\Response
     */
    public function edit(CircuitDescription $circuitDescription)
    {
        return view('dashboard.settings.circuit-descriptions.edit', [
            'CircuitDescription' => $circuitDescription
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param CircuitDescription $circuitDescription
     * @return array
     */
    public function update(StoreRequest $request, CircuitDescription $circuitDescription)
    {
        $circuitDescription->update($request->all());

        $request->session()->flash('notification.success', 'Circuit Description successfully updated.');

        return ['message' => 'Circuit Description successfully updated.'];
    }

}