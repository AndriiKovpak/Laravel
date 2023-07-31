<?php

namespace App\Http\Requests\Reports;

use App\Http\Requests\FormRequest;

/**
 * Class DateRangeRequest
 * @package App\Http\Requests\Reports
 */
class DateRangeRequest extends FormRequest
{
    /**
     * Allow this request only for authorized users.
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
        return [
            'send'          =>  ['in:0,1'],
            'destination'   =>  ['required_if:send,1', 'in:default,other'],
            'from'          =>  ['date', 'required_with:to'],
            'to'            =>  ['date', 'required_with:from'],
            'Email.*'       =>  ['required_if:destination,other', 'nullable', 'email']
        ];
    }
}