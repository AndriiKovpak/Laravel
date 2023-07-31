<?php

namespace App\Http\Requests\Inventory;

use App\Http\Requests\FormRequest;
use App\Http\Requests\HasAddressTrait;
use App\Models\BTNStatusType;
use App\Models\Carrier;
use App\Models\DivisionDistrict;
use Illuminate\Validation\Rule;

/**
 * Class UpdateRequest
 * @package App\Http\Requests\Inventory
 */
class UpdateRequest extends FormRequest
{
    use HasAddressTrait;

    /**
     * Authorize this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Return the validation rules.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'Status'        =>  ['required', Rule::exists((new BTNStatusType)->getTable(), 'BTNStatus')],
            'AccountNum'    =>  ['required', 'max:50'],
            'BTN'           =>  ['max:50'],

            'CarrierID'             =>  ['nullable', Rule::exists((new Carrier)->getTable())],
            'DivisionDistrictID'    =>  ['required', Rule::exists((new DivisionDistrict)->getTable())],

            'Note'  =>  ['nullable', 'max:4000']
        ];

        $rules = $this->addressRules('Site', $rules);

        return $rules;
    }

    /**
     * Return only address data
     *
     * @return array
     */
    public function getAddressData()
    {
        return $this->addressData('Site');
    }

    /**
     * Return only account data
     *
     * @return array
     */
    public function accountData()
    {
        return $this->only([
            'Status',
            'AccountNum',
            'BTN',
            'CarrierID',
            'DivisionDistrictID',
        ]);
    }

    public function messages()
    {
        return $this->addressMessages('Site');
    }
}