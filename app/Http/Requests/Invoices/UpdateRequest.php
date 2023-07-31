<?php

namespace App\Http\Requests\Invoices;

use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use App\Http\Requests\FormRequest;
use App\Models\ProcessedMethodType;
use App\Http\Requests\HasAddressTrait;

/**
 * Class UpdateRequest
 * @package App\Http\Requests\Inventory
 */
class UpdateRequest extends FormRequest
{
    use HasAddressTrait;

    /**
     * Date inputs
     *
     * @var array
     */
    protected $dates = ['DueDate', 'BillDate', 'ServiceFromDate', 'ServiceToDate'];

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
            'ProcessedMethod' => ['required', Rule::in(array_keys(ProcessedMethodType::getOptionsForSelect()))],

            'InvoiceNum' => ['nullable', 'max:50'],

            'BillDate'        => ['nullable', 'date'],
            'DueDate'         => ['nullable', 'date', 'after:BillDate'],
            'ServiceFromDate' => ['nullable', 'date'],
            'ServiceToDate'   => ['nullable', 'date', 'after:ServiceFromDate'],

            'IsFinalBill' => ['required', Rule::in([0, 1])],

            'PastDueAmount'       => ['nullable', 'numeric', 'min:0', 'max:1500000', 'regex:/^\d{0,9}(?:\.\d{2})?$/'],
            'CreditAmount'        => ['nullable', 'numeric', 'min:0', 'max:1500000', 'regex:/^\d{0,9}(?:\.\d{2})?$/'],
            'CurrentChargeAmount' => ['nullable', 'numeric', 'min:0', 'max:10000000', 'regex:/^\d{0,9}(?:\.\d{2})?$/'],

            'Note' => ['nullable', 'max:500'],
        ];

        $rules = $this->addressRules('Remittance', $rules);

        return $rules;
    }

    /**
     * Return only validated data.
     *
     * @param boolean $withoutEmptyValues
     * @return array
     */
    public function data($withoutEmptyValues = true)
    {
        $data = parent::data($withoutEmptyValues);

        $data['IsFinalBill'] = (Arr::get($data, 'IsFinalBill') == 1);

        return $data;
    }

    public function messages()
    {
        $messages = [
            'PastDueAmount.regex' => 'The Past Due Amount must be a valid amount with no commas.',
            'CreditAmount.regex' => 'The Credit Amount must be a valid amount with no commas.',
            'CurrentChargeAmount.regex' => 'The Current Charge Amount must be a valid amount with no commas.',
        ];

        $messages = $this->addressMessages('Remittance', $messages);

        return $messages;
    }

    /**
     * Return only address data
     *
     * @return array
     */
    public function getAddressData()
    {
        return $this->addressData('Remittance');
    }
}
