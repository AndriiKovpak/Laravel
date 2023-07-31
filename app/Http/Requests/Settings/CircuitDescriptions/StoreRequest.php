<?php

namespace App\Http\Requests\Settings\CircuitDescriptions;

use App\Http\Requests\FormRequest;
use App\Models\CircuitDescription;
use Illuminate\Validation\Rule;

/**
 * Class CreateRequest
 * @package App\Http\Requests\Settings\CircuitDescriptions
 */
class StoreRequest extends FormRequest
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
     * Return validation rules for Division District create.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'Description' => ['required', Rule::unique((new CircuitDescription())->getTable())]
        ];
    }
}