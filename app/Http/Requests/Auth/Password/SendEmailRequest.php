<?php

namespace App\Http\Requests\Auth\Password;

use App\Http\Requests\FormRequest;
use App\Models\User;
use Illuminate\Validation\Rule;

class SendEmailRequest extends FormRequest
{
    /**
     * Authorize this request only for guests.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->guest();
    }

    /**
     * Return validation rules.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'EmailAddress'  =>  ['required', 'email', Rule::exists((new User)->getTable())]
        ];
    }
}