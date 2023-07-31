<?php

namespace App\Http\Requests\Settings\ServiceTypes;
use App\Http\Requests\FormRequest;
use App\Models\ServiceType;
use Illuminate\Validation\Rule;

/**
 * Class EditRequest
 * @package App\Http\Requests\Settings\ServiceTypes
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
     * Return validation rules for Service Types edit.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ServiceTypeName' =>  ['required',
                Rule::unique((new ServiceType())->getTable())->ignore($this->serviceType, 'ServiceTypeName')],
            'Category' => ['required']
        ];
    }
}