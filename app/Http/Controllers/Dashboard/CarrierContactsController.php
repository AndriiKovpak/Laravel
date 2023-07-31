<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Carrier;
use App\Models\CarrierContact;
use App\Http\Requests\Carriers\ContactRequest;

class CarrierContactsController extends Controller
{
    public function __construct() {
        $this->middleware('auth.admin');
        $this->middleware('auth.model');
    }

    /**
     * Create new carrier contact for carrier
     * @param Carrier $Carrier
     */
    public function create(Carrier $Carrier)
    {
        return view('dashboard.carriers.contact.create',
            [
                'Carrier'   => $Carrier,
                'create'    => true,
                'Contact'   => [],
            ]
        );
    }


    /**
     * Create new carrier contact for carrier
     * @param ContactRequest $request
     * @param Carrier $Carrier
     */
    public function store(ContactRequest $request, Carrier $Carrier)
    {
        $Carrier->Contacts()->create($request->getData());

        return redirect()->route('dashboard.carriers.show', $Carrier)
            ->with('notification.success', "Carrier Contact has been successfully created.");
    }


    /**
     * Update Carrier information
     * @param $contactID
     */
    public function edit(Carrier $Carrier, CarrierContact $CarrierContact)
    {
        return view('dashboard.carriers.contact.edit', [
            'Carrier'       =>  $Carrier,
            'Contact'       =>  $CarrierContact,
        ]);
    }

    /**
     * Update Carrier information
     * @param ContactRequest $request
     * @param $id
     * ---6.3 on design docs---
     */
    public function update(ContactRequest $request, Carrier $Carrier, CarrierContact $CarrierContact)
    {
        $CarrierContact->update($request->getData());

        return redirect()->route('dashboard.carriers.show', $Carrier)
            ->with('notification.success', $request->Name . "'s information has been successfully updated.");
    }

    /**
     * Delete Carrier Contact
     * @param $id
     */
    public function destroy(Carrier $Carrier, CarrierContact $CarrierContact){
        $CarrierContact->delete();

        return redirect()->route('dashboard.carriers.show', $Carrier)
            ->with('notification.success', "Carrier Contact has successfully been deleted.");
    }
}
