<?php
/**
 * Created by PhpStorm.
 * User: bcooper
 * Date: 5/26/2017
 * Time: 4:47 PM
 */

namespace App\Services;
use App\Models\Address;
use App\Models\BTNAccount;
use App\Models\Circuit;
use App\Models\InvoiceAP;
use App\Models\InvoiceRemittanceAddress;

class AddressesService
{
    /*
     * Either creates a new address entry for the Circuit, or
     * updates the currently existing address entry
     */
    public function updateCircuitAddresses(Circuit $Circuit, $request)
    {
        $addressTypes = [
            'Service',
            'LocationA',
            'LocationZ',
        ];

        foreach ($addressTypes as $type) {
            $Address = $type . 'Address';

            // Skip address types that are not present (for Satellite)
            if ($request->has($type . 'AddressType')) {
                if ($Circuit->CategoryData->$Address()->count()) {
                    $Circuit->CategoryData->$Address->update($request->getAddressData($type));
                } else {
                    $Circuit->CategoryData->$Address()->associate(Address::create($request->getAddressData($type)));
                    $Circuit->CategoryData->save();
                }
            }
        }
    }

    public function updateRemittanceAddress(InvoiceAP $AccountPayable, $request)
    {
        if ($request['RemittanceAddressType'] == 'new') {
            $request['RemittanceAddressID'] = InvoiceRemittanceAddress::create($request->getAddressData())['RemittanceAddressID'];
        } else if ($request['RemittanceAddressType'] == 'update') {
            InvoiceRemittanceAddress::findOrFail($request['RemittanceAddressID'])->update($request->getAddressData());
        }

        $AccountPayable['RemittanceAddressID'] = $request['RemittanceAddressID'];
        $AccountPayable->save();
    }

    public function updateAddress(BTNAccount $BTNAccount, $request)
    {
        if ($BTNAccount->SiteAddress()->count()) {
            $BTNAccount->SiteAddress->update($request->getAddressData());
        } else {
            $BTNAccount->SiteAddress()->associate(Address::create($request->getAddressData()));
            $BTNAccount->save();
        }
    }
}