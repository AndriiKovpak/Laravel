<?php

namespace App\Http\Requests\Settings\Features;

use App\Http\Requests\FormRequest;
use App\Models\FeatureType;
use Illuminate\Validation\Rule;


/**
 * Class CreateRequest
 * @package App\Http\Requests\Settings\Features
 */
class StoreRequest extends FormRequest
{
    /**
     * Allow this request only for
     * authorized users.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Return validation rules for Feature Type create.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'FeatureName' => ['required', Rule::unique((new FeatureType())->getTable())->where('IsActive', true)],
            'CategoryID' => ['required'],
            'FeatureCode' => ['required', Rule::unique((new FeatureType())->getTable())->where('IsActive', true)],
        ];
    }
}