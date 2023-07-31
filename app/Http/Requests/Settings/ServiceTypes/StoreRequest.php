<?php

namespace App\Http\Requests\Settings\ServiceTypes;

use App\Models\User;
use App\Http\Requests\FormRequest;

use Illuminate\Validation\Rule;

/**
 * Class CreateRequest
 * @package App\Http\Requests\Settings\ServiceTypes
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
     * Return validation rules for Service Type create.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ServiceTypeName' => ['required', 'unique:ServiceTypes'],
            'Category' => ['required']
        ];
    }
}