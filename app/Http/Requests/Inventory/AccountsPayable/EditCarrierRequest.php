<?php

namespace App\Http\Requests\Inventory\AccountsPayable;

use App\Http\Requests\FormRequest;
use App\Models\FiscalYear;
use Illuminate\Validation\Rule;

class EditCarrierRequest extends FormRequest
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
            'BillingURL' => ['nullable', 'max:100'],
            'InvoiceAvailableDate' => ['nullable', 'max:50'],
            'Username' => ['nullable', 'max:100'],
            'Password' => ['nullable', 'max:250'],
            'IsPaperless' => ['nullable', 'numeric', 'max:1'],
            'PIN' => ['nullable', 'max:100'],
            'BTNAccountCarrierDetailNoteID' => ['nullable', 'numeric'],
            'DetailNotes' => ['nullable', 'max:4000'],
        ];
    }
}