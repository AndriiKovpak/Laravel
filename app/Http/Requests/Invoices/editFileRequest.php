<?php

namespace App\Http\Requests\Invoices;

use App\Http\Requests\FormRequest;

/**
 * Class UpdateRequest
 * @package App\Http\Requests\Inventory
 */
class editFileRequest extends FormRequest
{
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
        return   ['FileName'    =>  ['required']];
    }

}