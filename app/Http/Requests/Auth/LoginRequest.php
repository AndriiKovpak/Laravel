<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\FormRequest;

/**
 * Class LoginRequest
 * @package App\Http\Requests\Auth
 */
class LoginRequest extends FormRequest
{
    /**
     * Authorized this request only for guests
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->guest();
    }

    /**
     * Return login rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            'UserName'  =>  ['required'],
            'Password'  =>  ['required', 'min:6'],
            'Remember'  =>  ['in:on']
        ];
    }
}