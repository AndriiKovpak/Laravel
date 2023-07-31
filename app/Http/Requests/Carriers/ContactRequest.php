<?php

namespace App\Http\Requests\Carriers;

use App\Models\Carriers;
use App\Http\Requests\FormRequest;
use App\Http\Requests\HasPhoneTrait;
use Illuminate\Validation\Rule;


class ContactRequest extends FormRequest
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
            'Name' => 'required',
            'Title' => 'nullable',
            'EmailAddress' => ['nullable' , 'email'],
        ];

        return array_merge($rules,$this->phoneRules('MobilePhoneNumber',false),$this->phoneRules('OfficePhoneNumber', false));
    }

    public function messages()
    {
        return [
            'Name.required' => 'Contact Name is required.',
        ];
    }

    public function getData(){
        return array_merge(
            $this->only(['Name','Title','EmailAddress']),
            [
                'MobilePhoneNumber' => preg_replace('/\D+/', '',$this->MobilePhoneNumber),
                'OfficePhoneNumber' => preg_replace('/\D+/', '', $this->OfficePhoneNumber)
            ]
        );
    }
}
