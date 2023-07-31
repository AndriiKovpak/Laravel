<?php

namespace App\Http\Requests\Settings\DivisionDistricts;
use App\Http\Requests\FormRequest;
use App\Models\DivisionDistrict;
use Illuminate\Validation\Rule;

/**
 * Class EditRequest
 * @package App\Http\Requests\Settings\DivisionDistricts
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
     * Return validation rules for Division District edit.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'DivisionDistrictCode' =>  ['required',
                Rule::unique((new DivisionDistrict)->getTable())->ignore($this->divisionDistrict, 'DivisionDistrictCode')]
        ];
    }
}