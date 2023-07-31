<?php

namespace App\Services\Settings;

use App\Models\FeatureType;

class FeaturesService
{
    public function store($params)
    {
        $params['IsActive'] = true;

        return FeatureType::create($params);
    }
}