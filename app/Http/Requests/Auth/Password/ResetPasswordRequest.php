<?php

namespace App\Http\Requests\Auth\Password;

use App\Http\Requests\FormRequest;

/**
 * Class ResetPasswordRequest
 * @package App\Http\Requests\Auth\Password
 */
class ResetPasswordRequest extends FormRequest
{
    /**
     * Authorize this request only for guests
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->guest();
    }

    /**
     * Get the validation rules to update password
     *
     * @return array
     */
    public function rules()
    {
        return [
            'Password'              =>  ['required', 'min:6'],
            'PasswordConfirmation'  =>  ['required', 'same:Password']
        ];
    }
}