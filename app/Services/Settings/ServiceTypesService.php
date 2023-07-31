<?php

namespace App\Services\Settings;

use App\Models\ServiceType;

class ServiceTypesService
{
    public function store($params)
    {
       return ServiceType::create([
           'ServiceTypeName' => $params['ServiceTypeName'],
           'IsActive' => true,
           'Category' => $params['Category']
       ]);
    }
}