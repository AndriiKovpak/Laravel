<?php

namespace App\Http\Requests\Inventory\AccountsPayable;

use App\Http\Requests\FormRequest;
use App\Models\FiscalYear;
use Illuminate\Validation\Rule;

class IndexRequest extends FormRequest
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
     * Return validation rules.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'FiscalYearID'  =>  ['nullable', Rule::exists((new FiscalYear)->getTable())]
        ];
    }
}