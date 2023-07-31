<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Http\Requests\FormRequest;

use Illuminate\Validation\Rule;

/**
 * Class ProfileUpdateRequest
 * @package App\Http\Requests\Auth
 */
class ProfileUpdateRequest extends FormRequest
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
     * Return validation rules for profile update.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'FirstName'     =>  ['required', 'max:50'],
            'LastName'      =>  ['required', 'max:50'],
            'EmailAddress'  =>  ['required', 'max:50', 'email',
                Rule::unique((new User)->getTable())->ignore($this->user()->getKey(), 'UserID')->where('UserStatus',1)],

            'PhoneNumber'   =>  ['max:50'],
            'PhoneExt'      =>  ['max:20'],

            'UserName'      =>  ['max:50',
                Rule::unique((new User)->getTable())->ignore($this->user()->getKey(), 'UserID')->where('UserStatus',1)],
            'Password'      =>  ['nullable', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/']
        ];
    }

    public function messages()
    {
        return [
            'Password.regex' => 'Password must have eight characters, at least one capital letter, and at least one number.',
            'Password.min' => 'Password must have at least eight characters, at least one capital letter, and at least one number.',
        ];
    }
}