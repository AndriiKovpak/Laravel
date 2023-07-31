<?php

namespace App\Http\Requests\Orders;

use App\Models\Carrier;
use App\Models\BTNAccount;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use App\Models\DivisionDistrict;
use App\Http\Requests\HasAddressTrait;
use App\Http\Requests\Inventory\Circuits\StoreRequest;

/**
 * Class StoreRequest
 * @package App\Http\Requests\Inventory\Orders
 */
class CreateRequest extends StoreRequest
{
    use HasAddressTrait;

    /**
     * Date inputs
     *
     * @var array
     */
    protected $dates = ['OrderDate'];

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
     * Return validation rules
     *
     * @return array
     */
    public function rules()
    {
        if ($this->BTNAccountExists && $this->BTNAccountExists != 'no') {
            $this->inventory = BTNAccount::find($this->BTNAccountExists);
        }

        $rules =  [
            'ACEITOrderNum'         =>  ['required', 'max:50'],
            'CarrierOrderNum'       =>  ['nullable', 'max:50'],
            'AccountNum'            =>  ['max:50'],
            'CarrierID'             =>  ['nullable', Rule::exists((new Carrier)->getTable())],
            'BTN'                   =>  ['max:50'],
            'OrderFiles.*'          =>  ['nullable', 'file', 'mimes:pdf,doc,docx,dotx,xlsm,xls,xlsx,csv,html,htm,txt,st,ods,odt,xps,tif,tiff,jpg,jpeg,jpe,png'],
            'OrderDate'             =>  ['required', 'date']
        ];
        if (!empty($this->input('BTNAccountExists')) && $this->input('BTNAccountExists') != 'no') {
            $rules = array_merge($rules, parent::rules());
        } else {
            $BTN =  [
                //'Status'        =>  ['required', Rule::exists((new BTNStatusType)->getTable(), 'BTNStatus')],
                'AccountNum'    =>  ['max:50'],
                'BTN'           =>  ['max:50'],

                'CarrierID'             =>  ['nullable', Rule::exists((new Carrier)->getTable())],
                'DivisionDistrictID'    =>  ['required', Rule::exists((new DivisionDistrict)->getTable())],

                'Note'  =>  ['nullable', 'max:4000']
            ];
            $rules = array_merge($rules, $BTN);
            $rules = $this->addressRules('Site', $rules);
        }
        return array_merge($rules);
    }

    public function messages()
    {
        $messages = array_merge(parent::messages(), [
            'Category.required' => 'Circuit type is required.',
            'OrderFiles.*.mimes' => 'Invalid file type. File types that are accepted: :values .',
        ]);

        $messages = $this->addressMessages('Site', $messages);

        return $messages;
    }

    /**
     * Return only Order data
     *
     * @return array
     */
    public function dataOrder()
    {
        return Arr::only($this->data(), ['ACEITOrderNum', 'CarrierOrderNum', 'AccountNum', 'BTN', 'OrderDate']);
    }

    public function getAddressData($type = 'Site')
    {
        return parent::getAddressData($type);
    }

    /**
     * Return only BTNAccount data
     *
     * @return array
     */
    public function dataBTNAccount()
    {
        return $this->only([
            'AccountNum',
            'CarrierID',
            'BTN',
            'DivisionDistrictID',
        ]);
    }
}
