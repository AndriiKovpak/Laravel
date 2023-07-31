<?php

namespace App\Http\Requests\Settings\DivisionDistricts;

use App\Models\User;
use App\Http\Requests\FormRequest;

use Illuminate\Validation\Rule;

/**
 * Class CreateRequest
 * @package App\Http\Requests\Settings\DivisionDistricts
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
     * Return validation rules for Division District create.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'DivisionDistrictCode' => ['required', 'unique:DivisionDistricts']
        ];
    }
}