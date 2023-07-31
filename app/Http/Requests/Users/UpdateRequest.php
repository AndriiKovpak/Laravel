<?php

namespace App\Http\Requests\Users;

use App\Models\User;
use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;
use App\Http\Requests\HasPhoneTrait;

class UpdateRequest extends FormRequest
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
            'UserStatus' => 'required',
            'FirstName' => 'required',
            'LastName' => 'required',
            'EmailAddress' => [
                'required', 'max:50', 'email',
                Rule::unique((new User)->getTable())->ignore($this->user, 'UserID')->where('UserStatus', 1)
            ],
            'SecurityGroup' => 'required',
            'UserName' => [
                'required', 'max:50',
                Rule::unique((new User)->getTable())->ignore($this->user, 'UserID')->where('UserStatus', 1)
            ],
            'Password' =>  'nullable|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'newPassword' => 'nullable|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ];

        return array_merge($rules, $this->phoneRules('PhoneNumber'));
    }

    public function messages()
    {
        return [
            'FirstName.required' => 'First Name is required.',
            'LastName.required' => 'Last Name is required.',
            'PhoneNumber.required' => 'Phone # is required.',
            'PhoneNumber.regex' => 'Phone # must be 10 digits.',
            'EmailAddress.required' => 'Email is required.',
            'UserStatus.required' => 'Status is required',
            'SecurityGroup.required' => 'Users Group is required',
        ];
    }

    public function data($withoutEmptyValues = true)
    {

        $data = parent::data($withoutEmptyValues);

        if (isset($data['Password'])) {
            $data['Password'] = $data['Password'];
        }

        $data['PhoneNumber'] = preg_replace('/\D+/', '', $data['PhoneNumber']);

        return $data;
    }
}
