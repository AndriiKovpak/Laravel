<?php

/**
 * Created by PhpStorm.
 * User: bcooper
 * Date: 2/16/2017
 * Time: 12:04 PM
 */

namespace App\Services;

use App\Models\Carrier;
use App\Models\CarrierContact;
use App\Models\CarrierNote;


class CarriersService
{
    public function getCarriers($session)
    {
        $carriers = Carrier::select('Carriers.CarrierName', 'Carriers.CarrierID', 'Carriers.CarrierPhoneNum', 'Carriers.CarrierURL')->orderBy('Carriers.CarrierName', 'asc')->where('IsActive', 1);
        if (trim($session->get('CarrierSearch'))) {
            $carriers->where('Carriers.CarrierName', 'like', '%' . trim($session->get('CarrierSearch')) . '%');
        } else if (trim($session->get('CarrierSort')) && trim($session->get('CarrierSort')) != 'all') {
            $carriers->where('Carriers.CarrierName', 'like', trim($session->get('CarrierSort')) . '%')->distinct();
        }
        return $carriers->paginate(20);
    }

    public function carrierContacts($id)
    {
        $contacts = CarrierContact::select('CarrierContacts.Name', 'CarrierContacts.PhoneNumber', 'CarrierContacts.EmailAddress', 'CarrierContacts.CarrierContactID')
            ->where('CarrierContacts.CarrierID', '=', $id);
        return $contacts->paginate(15);
    }

    public function carrierNotes($id)
    {
        return CarrierNote::select()
            ->where('CarrierNotes.CarrierID', '=', $id)->get();
    }

    public function createCarrier($request)
    {
        $carrier = Carrier::create($request->getData());

        if (count($request->Name)) {
            for ($i = 0; $i < count($request->Name); $i++) {
                if ($request->Name[$i]) {
                    $carrierContact = new CarrierContact;
                    $carrierContact['CarrierID'] = $carrier->CarrierID;
                    $carrierContact['Name'] = $request->Name[$i];
                    $carrierContact['Title'] = $request->Title[$i];
                    $carrierContact['MobilePhoneNumber'] = preg_replace('/\D+/', '', $request->MobilePhoneNumber[$i]);
                    $carrierContact['OfficePhoneNumber'] = preg_replace('/\D+/', '', $request->OfficePhoneNumber[$i]);
                    $carrierContact['EmailAddress'] = $request->EmailAddress[$i];
                    $carrierContact['UpdatedByUserID'] =  auth()->user()->UserID;
                    $carrierContact->save();
                }
            }
        }
    }

    public function carriersSort($request, $session)
    {
        //Save search and List by values in session
        if ($request->input('ListBy')) {
            $session->put(['CarrierSort' => $request->input('ListBy')]);
            $session->put(['CarrierSearch' => null]);
            return;
        }
        $searchQuery = $request->input('CarrierSearch');
        if ($searchQuery) {
            $session->put(['CarrierSearch' => $searchQuery]);
            $session->put(['CarrierSort' => null]);
        } else {
            $session->put(['CarrierSearch' => null]);
        }
    }

    public function formatPhoneNumber($phone_number)
    {
        if ($phone_number) {
            $cleaned = preg_replace('/[^[:digit:]]/', '', $phone_number);
            if (strlen($cleaned) == 10) {
                preg_match('/(\d{3})(\d{3})(\d{4})/', $cleaned, $matches);
                return "({$matches[1]}) {$matches[2]}-{$matches[3]}";
            } else {
                return $cleaned;
            }
        } else {
            return '';
        }
    }
}
