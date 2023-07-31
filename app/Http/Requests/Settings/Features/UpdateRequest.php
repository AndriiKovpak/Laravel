<?php

namespace App\Http\Requests\Settings\Features;

use App\Http\Requests\FormRequest;
use App\Models\FeatureType;
use Illuminate\Validation\Rule;

/**
 * Class EditRequest
 * @package App\Http\Requests\Settings\Features
 */
class UpdateRequest extends FormRequest
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
     * Return validation rules for Feature Type edit.
     *
     * @return array
     */
    public function rules()
    {
        // I won't use this rule bc not work as expected
        return [
            'FeatureName' =>  ['required', Rule::unique((new FeatureType())->getTable())->where('IsActive', true)->ignore($this->route('featureType')['FeatureType'], 'FeatureType')],
            'CategoryID' => ['required'],
            'FeatureCode' => ['required', Rule::unique((new FeatureType())->getTable())->where('IsActive', true)->ignore($this->route('featureType')['FeatureType'], 'FeatureType')]
        ];
    }
}
