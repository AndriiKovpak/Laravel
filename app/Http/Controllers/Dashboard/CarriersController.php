<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Carrier;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Services\CarriersService;
use App\Http\Requests\Carriers\CarrierRequest;
use App\Models\Util;

class CarriersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    /**
     * Display table of carriers
     * @param Request $request
     * @param CarriersService $CarriersService
     * ---6.0 on design docs---
     */
    public function index(Request $request, CarriersService $CarriersService)
    {
        $session = $request->session();
        $CarriersService->carriersSort($request, $session);
        return view('dashboard.carriers.index', [
            'carriers'      =>  $CarriersService->getCarriers($session),
            'searchString'  =>  trim($session->get('CarrierSearch')),
            'ListBy'        =>  trim($session->get('CarrierSort'))
        ]);
    }

    /**
     * Update Carrier information
     * @param Carrier $Carrier
     * ---6.2 on design docs---
     */
    public function show(Carrier $Carrier)
    {
        return view('dashboard.carriers.show', [
            'carrier'       =>      $Carrier,
            'contacts'      =>      $Carrier->Contacts()->paginate(15),
            'message'       =>      "Not defined"
        ]);
    }

    /**
     * Create new carrier information
     *
     * ---6.5 on design docs---
     */
    public function create()
    {
        return view(
            'dashboard.carriers.create',
            [
                'carrier'   => [],
                'request'   => [],
                'notes'     => [],
                'Contact'   => [],
                'key'       => '',
                'edit'      => false,
            ]
        );
    }

    /**
     * Create new carrier information
     * @param CarrierRequest $request
     * @param CarriersService $CarriersService
     * ---6.5 on design docs---
     */
    public function store(CarrierRequest $request, CarriersService $CarriersService)
    {
        try {
            $CarriersService->createCarrier($request);
        } catch (QueryException $e) {
            Util::log("Error inserting carrier info.\nError: {$e->getMessage()}");
            \Session::flash('flash_message', "There was an error trying to save the carrier's information.");
        }
        return redirect()->route('dashboard.carriers.index')
            ->with('notification.success', $request->CarrierName . "'s information has been successfully saved.");
    }

    /**
     * Update Carrier information
     * @param Carrier $Carrier
     * ---6.3 on design docs---
     */
    public function edit(Carrier $Carrier)
    {
        return view('dashboard.carriers.edit', [
            'carrier'       =>      $Carrier,
            'contact'       =>      [],
            'edit'          =>      true,
            'key'           =>      '',
        ]);
    }

    /**
     * Update Carrier information
     * @param CarrierRequest $request
     * @param Carrier $Carrier
     * ---6.3 on design docs---
     */
    public function update(CarrierRequest $request, Carrier $Carrier)
    {
        $Carrier->update($request->getData());

        return redirect()->route('dashboard.carriers.show', $Carrier->CarrierID)
            ->with('notification.success', $request->CarrierName . "'s information has been successfully updated.");
    }

    /**
     * Delete Carrier
     * @param Carrier $Carrier
     */
    public function destroy(Carrier $Carrier)
    {

        $Carrier->setAttribute('IsActive', 0);
        $Carrier->save();

        return redirect()->back()
            ->with('notification.success', "Carrier has successfully been deleted.");
    }
}
