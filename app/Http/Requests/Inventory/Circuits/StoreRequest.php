<?php

namespace App\Http\Requests\Inventory\Circuits;

use Carbon\Carbon;
use App\Models\Circuit;
use App\Models\Category;
use App\Models\CircuitData;
use App\Models\FeatureType;
use App\Models\ServiceType;
use Illuminate\Support\Arr;
use App\Models\CircuitVoice;
use App\Models\BTNStatusType;
use Illuminate\Validation\Rule;
use App\Models\CircuitSatellite;

use App\Models\DivisionDistrict;
use App\Http\Requests\FormRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\HasAddressTrait;

/**
 * Class StoreRequest
 * @package App\Http\Requests\Inventory\Circuits
 */
class StoreRequest extends FormRequest
{
    use HasAddressTrait;

    private $categoryClasses = [
        Category::VOICE     =>  CircuitVoice::class,
        Category::DATA      =>  CircuitData::class,
        Category::SATELLITE =>  CircuitSatellite::class
    ];

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
        $rules = [
            'Status'            =>  ['nullable', Rule::exists((new BTNStatusType())->getTable(), 'BTNStatus')],
            'ServiceType'       =>  ['required', Rule::exists((new ServiceType)->getTable(), 'ServiceType')],
            'BillingStartDate'  =>  ['nullable', 'date'],
            'InstallationDT'    =>  ['nullable', 'date'],
            'BillUnderBTN'      =>  ['nullable', 'max:50'],
            'DescriptionID'     =>  ['nullable', 'max:50'],
            'Cost'              =>  ['nullable', 'numeric', 'min:0', 'max:1500000', 'regex:/^\d{0,9}(?:\.\d{2})?$/'],
            'Notes'             =>  ['nullable', 'max:1000'],
            'CarrierCircuitID'      =>  ['nullable', 'max:50'],
            'DivisionDistrictID'    =>  ['nullable', Rule::exists((new DivisionDistrict)->getTable(), 'DivisionDistrictID')]
        ];

        switch ($this->getCategoryID()) {

            case Category::VOICE:

                $rules = array_merge($rules, [
                    'SPID_Phone1'   =>  ['nullable', 'max:50'],
                    'SPID_Phone2'   =>  ['nullable', 'max:50'],
                    'LD_PIC'        =>  ['nullable', 'max:50'],
                ]);

                break;

            case Category::DATA:

                $rules =  array_merge($rules, [
                    'QoS_CIR'   =>  ['nullable', 'max:50'],
                    'PortSpeed' =>  ['nullable', 'max:50'],
                    'Mileage'   =>  ['nullable', 'max:50'],
                    'NetworkIPAddress'  =>  ['nullable', 'max:50']
                ]);

                break;

            case Category::SATELLITE:

                return array_merge($rules, [
                    'AssignedToName'    =>  ['nullable', 'max:50'],

                    'DeviceType'    =>  ['nullable', 'max:50'],
                    'DeviceMake'    =>  ['nullable', 'max:50'],
                    'DeviceModel'   =>  ['nullable', 'max:50'],
                    'IMEI'          =>  ['nullable', 'max:50'],
                    'SIM'           =>  ['nullable', 'max:50'],
                ]);
        }

        $rules = array_merge($rules, [
            'Email'         =>  ['nullable', 'max:50', 'email'],
            'ILEC_ID1'      =>  ['nullable', 'max:50'],
            'ILEC_ID2'      =>  ['nullable', 'max:50'],
            'CircuitFeatures.*.FeatureType' => [
                'required_with:CircuitFeatures.*.FeatureCost', 'nullable',
                Rule::exists((new FeatureType)->getTable(), 'FeatureType')
                    ->where('CategoryID', $this->input('CategoryID')) // This prevents Features with the wrong CategoryID from being added or updated.
                    ->where('IsActive', true)
            ],
            'CircuitFeatures.*.FeatureCost' => ['required_with:CircuitFeatures.*.FeatureType', 'nullable', 'numeric', 'min:0', 'max:1500000', 'regex:/^\d{0,9}(?:\.\d{2})?$/'],
        ]);

        $rules = $this->addressRules('Service', $rules);
        $rules = $this->addressRules('LocationA', $rules);
        $rules = $this->addressRules('LocationZ', $rules);

        return $rules;
    }

    /**
     * Get custom messages for validation.
     *
     * @return array
     */
    public function messages()
    {
        $messages = [
            'Cost.regex' => 'The Cost must be a valid amount with no commas.',
            'CircuitFeatures.*.FeatureType.required_with' => 'The Feature field is required when Cost is present.',
            'CircuitFeatures.*.FeatureCost.required_with' => 'The Cost field is required when Feature is present.',
            'CircuitFeatures.*.FeatureCost.regex' => 'The Cost must be a valid amount with no commas.',
            'CircuitFeatures.*.FeatureType.exists' => 'This Feature does not exist for this Category',
        ];

        $messages = $this->addressMessages('Service', $messages);
        $messages = $this->addressMessages('LocationA', $messages);
        $messages = $this->addressMessages('LocationZ', $messages);

        return $messages;
    }

    public function attributes()
    {
        return [
            'CircuitFeatures.*.FeatureCost' => 'Cost',
        ];
    }

    /**
     * Return only Circuit's data
     *
     * @return array
     */
    public function getCircuitData()
    {
        $data = Arr::except(
            $this->only((new Circuit)->getFillable()),
            ['UpdatedDate', 'UpdatedByUserID', 'BTNAccountID', 'CircuitID']
        );

        if ($this->method() == 'PUT') {

            unset($data['CategoryID']);
        }

        foreach (['BillingStartDate', 'InstallationDT'] as $column) {

            if (!empty($data[$column])) {

                $data[$column] = Carbon::parse($data[$column]);
            }
        }
        return $data;
    }

    /**
     * Get only Category Data
     *
     * @return array
     */
    public function getCategoryData()
    {
        // return Arr::except(
        //     $this->only((new $this->categoryClasses[$this->getCategoryID()])->getFillable()),
        //     ['CircuitID', 'ServiceAddressID', 'LocationAAddressID', 'LocationZAddressID', 'UpdatedByUserID']
        // );

        // Ensure 'categoryClasses' property exists and is an array
        if (!isset($this->categoryClasses) || !is_array($this->categoryClasses)) {
            throw new Exception('categoryClasses property is missing or not an array');
        }

        // Ensure method 'getCategoryID' exists and returns valid index
        if (!method_exists($this, 'getCategoryID') || !array_key_exists($this->getCategoryID(), $this->categoryClasses)) {
            throw new Exception('getCategoryID method is missing or returns invalid index');
        }

        // Get category class name and ensure class exists
        $className = $this->categoryClasses[$this->getCategoryID()];
        if (!class_exists($className)) {
            throw new Exception('Category class ' . $className . ' does not exist');
        }

        // Create new instance of the class and ensure 'getFillable' method exists
        $classInstance = new $className;
        if (!method_exists($classInstance, 'getFillable')) {
            throw new Exception('getFillable method does not exist in class ' . $className);
        }

        // Get only fillable attributes
        $fillableData = $this->only($classInstance->getFillable());

        // Remove unwanted keys and return data
        $keysToRemove = ['CircuitID', 'ServiceAddressID', 'LocationAAddressID', 'LocationZAddressID', 'UpdatedByUserID'];
        return Arr::except($fillableData, $keysToRemove);
    }

    /**
     * Return Category ID of a Circuit
     *
     * @return int
     */
    private function getCategoryID()
    {
        if ($this->route('circuit')) {
            return intval($this->has('CategoryID')
                ? $this->get('CategoryID')
                : $this->route('circuit')->getAttribute('CategoryID'));
        } else {
            return $_REQUEST['CategoryID'];
        }
    }

    public function getAddressData($type)
    {
        return $this->addressData($type);
    }
}
