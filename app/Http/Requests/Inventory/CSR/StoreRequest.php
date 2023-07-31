<?php

namespace App\Http\Requests\Inventory\CSR;

use App\Http\Requests\FormRequest;

/**
 * Class StoreRequest
 * @package App\Http\Requests\Inventory\CSR
 */
class StoreRequest extends FormRequest
{
    /**
     * Authorize this request
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Date inputs
     *
     * @var array
     */
    protected $dates = ['PrintedDate'];

    /**
     * Return the validation rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            'AccountNum'    =>  ['required','max:50'],
            'PrintedDate'   =>  ['nullable', 'date'],
            'File'          =>  ['nullable','file', 'mimes:pdf,doc,docx,dotx,xlsm,xls,xlsx,csv,html,htm,txt,st,ods,odt,xps,tif,tiff,jpg,jpeg,jpe,png']
        ];
    }

    public function messages()
    {
        return [
            'File.mimes' => 'Invalid file type. File types that are accepted: :values .',
        ];
    }
}