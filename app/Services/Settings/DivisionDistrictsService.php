<?php

namespace App\Services\Settings;

use App\Models\DivisionDistrict;

class DivisionDistrictsService
{
    public function store($params, $user)
    {
        $divisionDistrict = DivisionDistrict::create([
           'DivisionDistrictCode' => $params['DivisionDistrictCode'],
           'DivisionDistrictName' => $params['DivisionDistrictCode'],
           'IsActive' => true
       ]);

        $user->DivisionDistricts()->attach($divisionDistrict);

       return $divisionDistrict;
    }
}