<?php

namespace App\Http\Requests\Inventory\Circuits;

use App\Http\Requests\FormRequest;
use App\Models\CircuitDID;
use Illuminate\Validation\Rule;

/**
 * Class DIDRequest
 * @package App\Http\Requests\Inventory\Circuits\DIDs
 */
class DIDRequest extends FormRequest
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
     * Return the validation rules
     *
     * @return array
     */
    public function rules()
    {
        $unique = is_null($this->route('did'))
            ? Rule::unique((new CircuitDID)->getTable())->where('CircuitID', $this->route('circuit')->getKey())
            : Rule::unique((new CircuitDID)->getTable())->where('CircuitID', $this->route('circuit')->getKey())->ignore($this->route('did')->getKey(), 'CircuitDIDID');

        $rules = [
            'Type'      =>  ['in:single,range'],
            'DID'       =>  ['required_if:Type,single', $unique],
            'DIDPrefix' =>  ['required_if:Type,range'],
            'DIDFrom'   =>  ['required_if:Type,range'],
            'DIDTo'     =>  ['required_if:Type,range'],
            'DIDNote'   =>  ['max:500'],
        ];

        if ('single' == $this->input('Type')) {
            $rules['DID'][]         = 'regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/';
        } else if ('range' == $this->input('Type')) {
            $rules['DIDPrefix'][]   = 'regex:/^[0-9]{3}-[0-9]{3}$/';
            $rules['DIDFrom'][]     = 'regex:/^[0-9]{4}$/';
            $rules['DIDTo'][]       = 'regex:/^[0-9]{4}$/';
        }

        return $rules;
    }

    /**
     * Return custom message
     *
     * @return array
     */
    public function messages()
    {
        return [
            'DID.required'  =>  'The DID field is required.',
            'DID.unique'    =>  'The DID has already been taken.',
            'DID.regex'     =>  'The :attribute must be a ten-digit number in the format XXX-XXX-XXXX.',
            'DIDPrefix.regex'   =>  'The :attribute must be a six-digit number in the format XXX-XXX.',
            'DIDFrom.regex' =>  'The :attribute must be a four-digit number.',
            'DIDTo.regex'   =>  'The :attribute must be a four-digit number.',
        ];
    }
}