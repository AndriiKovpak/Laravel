<?php

namespace App\Http\Requests;


use App\Models\StateCode;
use Illuminate\Validation\Rule;

trait HasAddressTrait
{
    private function addressRules($type, $rules = [])
    {
        $isRemittance = $type == 'Remittance';
        $SiteName = $isRemittance ? 'RemittanceName' : 'SiteName';

        $rules = array_merge($rules, [
            $SiteName . $type  => ['max:100'],
            $type . 'AddressType' => ['required', 'in:existing,new'], // Add update to enable update
        ]);

        switch ($this->input($type . 'AddressType')) {
            case 'existing':
                $rules = array_merge($rules, [
                    $type . 'AddressSearch' => ['nullable', 'same:' . $type . 'AddressString'],
                ]);
                break;
            // case 'update': // Uncomment to enable update
            case 'new':
                $rules = array_merge($rules, [
                    'Address1' . $type => ['max:50'],
                    'Address2' . $type => ['max:50'],
                    'City' . $type     => ['max:50'],
                    'State' . $type    => ['nullable', Rule::exists((new StateCode)->getTable(), 'State')],
                    'Zip' . $type      => ['max:50'],
                ]);
                break;
        }

        return $rules;
    }

    private function addressMessages($type, $messages = [])
    {
        $messages = array_merge($messages, [
            $type . 'AddressSearch.same' => 'That address does not exist. Please click new address below to create a new address.',
        ]);

        return $messages;
    }


    private function addressData($type)
    {
        $isRemittance = $type == 'Remittance';
        $SiteName = $isRemittance ? 'RemittanceName' : 'SiteName';

        $data = [
            $SiteName  => $this->input($SiteName . $type),
            'Address1' => $this->input('Address1' . $type),
            'Address2' => $this->input('Address2' . $type),
            'City'     => $this->input('City' . $type),
            'State'    => $this->input('State' . $type),
            'Zip'      => $this->input('Zip' . $type),
        ];

        if (!$isRemittance) {
            $data['AddressType'] = $this->input('AddressType' . $type);
        }

        return $data;
    }
}
