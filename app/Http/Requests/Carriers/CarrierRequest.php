<?php

namespace App\Http\Requests\Carriers;

use App\Models\Carriers;
use App\Http\Requests\FormRequest;
use App\Http\Requests\HasPhoneTrait;
use Illuminate\Validation\Rule;


class CarrierRequest extends FormRequest
{
    use HasPhoneTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'CarrierName'           => 'required',
            'Name.*'                => ['nullable'],
            'Title.*'               => ['nullable'],
            'EmailAddress.*'        => ['nullable' , 'email'],
        ];

        return array_merge(
            $rules,
            $this->phoneRules('CarrierPhoneNum'),
            $this->phoneRules('CarrierSupportPhoneNum'),
            $this->phoneRules('MobilePhoneNumber.*', false),
            $this->phoneRules('OfficePhoneNumber.*', false)
        );
    }

    public function messages()
    {
        return [
            'CarrierPhoneNum.required' => 'Phone # is required.',
            'CarrierPhoneNum.regex' => 'Phone # must be 10 digits.',
            'CarrierSupportPhoneNum.required' => 'Phone # is required.',
            'CarrierPhoneNum.regex' => 'Phone # must be 10 digits.',
            'CarrierSupportPhoneNum.regex' => 'Phone # must be 10 digits.',
        ];
    }

    public function getData(){
        return array_merge(
            $this->only(['CarrierName']),
            [
            'CarrierPhoneNum'           => preg_replace('/\D+/', '', $this->CarrierPhoneNum),
            'CarrierSupportPhoneNum'    => preg_replace('/\D+/', '', $this->CarrierSupportPhoneNum)
            ]
        );
    }
}
