<?php

namespace App\Http\Requests\Inventory\Circuits;

use App\Models\BTNAccountOrder;
use App\Http\Requests\FormRequest;

use App\Models\MACType;
use Illuminate\Validation\Rule;

/**
 * Class MACRequest
 * @package App\Http\Requests\Inventory\Circuits
 */
class MACRequest extends FormRequest
{
    /**
     * Date inputs
     *
     * @var array
     */
    protected $dates = ['ContractDate', 'ContractExpDate', 'DisconnectDate', 'CarrierDueDate', 'DisconnectRequestDate', 'RequestedContractRenewalDate'];

    /**
     * Authorize this request
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Return the validation rules
     *
     * Note: Also see App\Http\Requests\Inventory\MAC\StoreRequest
     *
     * @return array
     */
    public function rules()
    {
        return [
            'OrderNum' => ['max:50'],
            'MACType'  => ['required', Rule::exists((new MACType)->getTable())],

            'CarrierOrder' => ['max:50'],
            'Description'  => ['max:1000'],

            'ContactName'     => ['max:50'],
            'ContactPhone'    => ['max:20'],
            'ContactPhoneExt' => ['max:20'],

            'ContractDate'    => ['nullable', 'date'],
            'ContractExpDate' => ['nullable', 'date'],
            'DisconnectDate'  => ['nullable', 'date'],

            'RequestorName'  => ['max:50'],
            'CarrierDueDate' => ['nullable', 'date'],
            'TelcoOrderNum'  => ['max:50'],

            'DisconnectRequestDate'        => ['nullable', 'date'],
            'RequestedContractRenewalDate' => ['nullable', 'date'],

            'FinalCreditAmount' => ['nullable', 'numeric', 'min:0', 'max:1500000', 'regex:/^\d{0,9}(?:\.\d{2})?$/'],

            'Note' => ['max:4000'],
        ];
    }
}