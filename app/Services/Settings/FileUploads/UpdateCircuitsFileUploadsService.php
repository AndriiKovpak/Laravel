<?php

namespace App\Services\Settings\FileUploads;

use App\Models\Address;
use App\Models\AddressType;
use App\Models\BTNAccount;
use App\Models\BTNStatusType;
use App\Models\Circuit;
use App\Models\CircuitDescription;
use App\Models\DivisionDistrict;
use App\Models\ServiceType;
use App\Models\StateCode;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UpdateCircuitsFileUploadsService extends BaseFileUploadsService
{
    protected $fileType = 'Update Circuits';

    protected $columnNames = [
        'AccountNum',           // BTNAccounts.AccountNum
        'CarrierCircuitID',     // Circuits.CarrierCircuitID
        'OldValue',             //
        'NewValue-Mixed',       // (Varies depending on OldValue)
        'NewValue-Address2',    // Addresses.Address2
        'NewValue-City',        // Addresses.City
        'NewValue-State',       // Addresses.State
        'NewValue-Zip',         // Addresses.Zip
    ];

    /**
     * Validate an Update Circuits row in four steps.
     *
     * 1. Make sure each Circuit Update only appears once in the file.
     * 2. Validate the common row attributes, including BTNAccount, Circuit, and OldValue.
     * 3. Do not allow certain Updates for Satellite Circuits.
     * 4. Validate NewValue(s) depending on OldValue.
     *
     * @param array $fileUploadRow This array holds the information being validated.
     *
     * @return void An exception is thrown if validation fails.
     */
    protected function validateRow(array $fileUploadRow)
    {
        $fileUploadRow = $this->makeRowAssociativeArray($fileUploadRow);

        // 1. Make sure each Circuit Update only appears once in the file.
        // A duplicate may indicate an error in the original file and lead to unexpected results.
        // However, this check could cause problems with very large files.
        $rowKey = $fileUploadRow['AccountNum'] . $fileUploadRow['CarrierCircuitID'] . $fileUploadRow['OldValue'];
        $this->validateArray(['Circuit Update' => $rowKey], [
            'Circuit Update' => [Rule::notIn(array_keys($this->rowKeys))],
        ], [
            'not_in' => 'The specified :attribute has already appeared on an earlier row.',
        ], [
            'Circuit Update' => 'Circuit Update',
        ]);
        $this->rowKeys[$rowKey] = true;

        // 2. Validate the common row attributes, including BTNAccount, Circuit, and OldValue.
        // Standard validation for the row
        $this->validateArray($fileUploadRow, [
            'AccountNum'        => ['bail', 'required', 'max:50', Rule::exists((new BTNAccount)->getTable(), 'AccountNum')],
            'CarrierCircuitID'  => ['bail', 'required', 'max:50',
                                    Rule::exists((new Circuit)->getTable(), 'CarrierCircuitID') // Check that Circuit exists and belongs to this BTNAccount.
                                        ->using(function ($query) use ($fileUploadRow) {
                                            /** @var \Illuminate\Database\Query\Builder $query */
                                            $query->whereIn( // Using a subquery because joins don't work in this context.
                                                'BTNAccountID',
                                                DB::table((new BTNAccount)->getTable())
                                                    ->select('BTNAccountID')
                                                    ->where('AccountNum', $fileUploadRow['AccountNum'])
                                            )->whereIn( // Using a subquery because joins don't work in this context.
                                                'Status',
                                                DB::table((new BTNStatusType)->getTable())
                                                    ->select('BTNStatus')
                                                    ->where('IsDisplay', true)
                                            );
                                        }),
                                ],
            'OldValue'          => ['required', 'in:Cost,StatusName,DivisionDistrict,Description,BillingStartDate,InstallationDT,ServiceType,EmailAddress,LocationA,LocationZ,ServiceAddress'],
        ], [
            'exists'    => 'The specified :attribute does not exist.',
            'in'        => 'The :attribute must be one of the following: :values',
            'max'       => 'The :attribute may not be greater than :max characters.',
            'required'  => 'The :attribute column is required.',
        ], [
            'AccountNum'        => 'Account #',
            'CarrierCircuitID'  => 'Carrier Circuit ID',
            'OldValue'          => 'Old Value',
        ]);

        // This is needed for enough validation that I thought it would be better here.
        /** @var BTNAccount $BTNAccount */
        $BTNAccount = BTNAccount::where('AccountNum', $fileUploadRow['AccountNum'])->firstOrFail();
        /** @var Circuit $Circuit */
        $Circuit = $BTNAccount->Circuits()->where('CarrierCircuitID', $fileUploadRow['CarrierCircuitID'])->firstOrFail();

        // 3. Do not allow certain Updates for Satellite Circuits.
        switch ($fileUploadRow['OldValue']) {
            case 'Description':
            case 'EmailAddress':
            case 'LocationA':
            case 'LocationZ':
            case 'ServiceAddress':
                if ($Circuit->Category->isSatellite()) {
                    $this->validateArray($fileUploadRow, [
                        'OldValue'  => ['in:Cost,StatusName,DivisionDistrict,BillingStartDate,InstallationDT,ServiceType'],
                    ], [
                        'OldValue.in'   => 'The :attribute for a Satellite Circuit must be one of the following: :values',
                    ], [
                        'OldValue'  => 'Old Value',
                    ]);
                }
                break;
        }

        // 4. Validate NewValue(s) depending on OldValue.
        switch ($fileUploadRow['OldValue']) {
            case 'Cost':
                $this->validateArray($fileUploadRow, [
                    'NewValue-Mixed'    => ['nullable', 'numeric', 'min:0', 'max:1500000'],
                ], [
                    'max'       => 'The :attribute may not be greater than :max.',
                    'min'       => 'The :attribute must be at least :min.',
                    'numeric'   => 'The :attribute must be a number.',
                ], [
                    'NewValue-Mixed'    => 'New Value (' . $fileUploadRow['OldValue'] . ')',
                ]);
                break;

            case 'StatusName':
                $this->validateArray($fileUploadRow, [
                    'NewValue-Mixed'    => ['bail', 'required', 'max:50', Rule::exists((new BTNStatusType())->getTable(), 'BTNStatusName')], // Required by view
                ], [
                    'required'  => 'The :attribute column is required.',
                    'max'       => 'The :attribute may not be greater than :max characters.',
                    'exists'    => 'The specified :attribute does not exist.',
                ], [
                    'NewValue-Mixed'    => 'New Value (' . $fileUploadRow['OldValue'] . ')',
                ]);
                break;

            case 'DivisionDistrict':
                $this->validateArray($fileUploadRow, [
                    'NewValue-Mixed'    => ['bail', 'nullable', 'max:50', Rule::exists((new DivisionDistrict)->getTable(), 'DivisionDistrictCode')],
                ], [
                    'max'       => 'The :attribute may not be greater than :max characters.',
                    'in'   => 'The specified :attribute does not match the :attribute for the Account.',
                ], [
                    'NewValue-Mixed'    => 'New Value (' . $fileUploadRow['OldValue'] . ')',
                ]);
                break;

            case 'Description':
                $this->validateArray($fileUploadRow, [
                    'NewValue-Mixed'    => ['nullable', 'max:50'],
                ], [
                    'max'   => 'The :attribute may not be greater than :max characters.',
                ], [
                    'NewValue-Mixed'    => 'New Value (' . $fileUploadRow['OldValue'] . ')',
                ]);
                break;

            case 'ServiceType':
                $this->validateArray($fileUploadRow, [
                    'NewValue-Mixed'    => ['bail', 'nullable', 'max:50',
                                            Rule::exists((new ServiceType)->getTable(), 'ServiceTypeName') // Check that ServiceType exists and belongs to this Category.
                                                ->using(function ($query) use ($Circuit) {
                                                    /** @var \Illuminate\Database\Query\Builder $query */
                                                    $query->where(
                                                        'Category',
                                                        $Circuit->Category->CategoryID // Don't need to check if Category IsActive here because it is using ID of the one in use... Right?
                                                    );
                                                })
                                                ->where('IsActive', true),
                                        ],
                ], [
                    'max'       => 'The :attribute may not be greater than :max characters.',
                    'exists'    => 'The specified :attribute does not exist for a ' . $Circuit->Category->CategoryName . ' Circuit.',
                ], [
                    'NewValue-Mixed'    => 'New Value (' . $fileUploadRow['OldValue'] . ')',
                ]);
                break;

            case 'BillingStartDate':
            case 'InstallationDT':
                $this->validateArray($fileUploadRow, [
                    'NewValue-Mixed'    => ['nullable', 'date']
                ], [
                    'date'  => 'The :attribute must be a valid date.',
                ], [
                    'NewValue-Mixed'    => 'New Value (' . $fileUploadRow['OldValue'] . ')',
                ]);
                break;
            case 'EmailAddress':
                $this->validateArray($fileUploadRow, [
                    'NewValue-Mixed'    => ['nullable', 'email', 'max:50'],
                ], [
                    'email' => 'The :attribute must be a valid email address.',
                    'max'   => 'The :attribute may not be greater than :max characters.',
                ], [
                    'NewValue-Mixed'    => 'New Value (' . $fileUploadRow['OldValue'] . ')',
                ]);
                break;

            case 'LocationA':
            case 'LocationZ':
            case 'ServiceAddress':
                $this->validateArray($fileUploadRow, [
                    'NewValue-Mixed'    => ['nullable', 'max:50'],
                    'NewValue-Address2' => ['nullable', 'max:50'],
                    'NewValue-City'     => ['nullable', 'max:50'],
                    'NewValue-State'    => ['bail', 'nullable', 'max:2', Rule::exists((new StateCode())->getTable(), 'State')],
                    'NewValue-Zip'      => ['nullable', 'max:20'],
                ], [
                    'exists'    => 'The specified :attribute does not exist.',
                    'max'       => 'The :attribute may not be greater than :max characters.',
                ], [
                    'NewValue-Mixed'    => 'New Value (' . $fileUploadRow['OldValue'] . ' Line 1)',
                    'NewValue-Address2' => 'New Value (' . $fileUploadRow['OldValue'] . ' Line 2)',
                    'NewValue-City'     => 'New Value (' . $fileUploadRow['OldValue'] . ' City)',
                    'NewValue-State'    => 'New Value (' . $fileUploadRow['OldValue'] . ' State)',
                    'NewValue-Zip'      => 'New Value (' . $fileUploadRow['OldValue'] . ' Zip)',
                ]);
                break;
        }
    }

    /**
     * Process a Update Circuit row
     *
     * Update a Circuit based on the attributes provided in $fileUploadRow.
     *
     * @param array $fileUploadRow This array holds the information about the CircuitFeature and the action being performed.
     *
     * @return void
     */
    protected function processRow(array $fileUploadRow)
    {
        $fileUploadRow = $this->makeRowAssociativeArray($fileUploadRow);

        /** @var BTNAccount $BTNAccount */
        $BTNAccount = BTNAccount::where('AccountNum', $fileUploadRow['AccountNum'])->firstOrFail();
        /** @var Circuit $Circuit */
        $Circuit = $BTNAccount->Circuits()->where(
            'CarrierCircuitID',
            $fileUploadRow['CarrierCircuitID']
        )->whereHas(
            'StatusType',
            function ($query) {
                /** @var \Illuminate\Database\Eloquent\Builder $query */
                $query->display();
            }
        )->firstOrFail();

        switch ($fileUploadRow['OldValue']) {
            case 'Cost':
                $Circuit->Cost = $fileUploadRow['NewValue-Mixed'];
                break;

            case 'StatusName':
                $Circuit->StatusType()->associate(
                    BTNStatusType::where(
                        'BTNStatusName',
                        $fileUploadRow['NewValue-Mixed']
                    )->firstOrFail()
                );
                break;

            case 'DivisionDistrict':
                if ($fileUploadRow['NewValue-Mixed']) {
                    $Circuit->DivisionDistrict()->associate(
                        DivisionDistrict::where(
                            'DivisionDistrictCode',
                            $fileUploadRow['NewValue-Mixed']
                        )->firstOrFail()
                    );
                } else {
                    // We usually dissociate in this case, but the 4/18/2022 request is to do nothing.
                    // $Circuit->DivisionDistrict()->dissociate();
                }
                break;

            case 'Description':
                if ($fileUploadRow['NewValue-Mixed']) {
                    $Circuit->CategoryData->Description()->associate(
                        CircuitDescription::firstOrCreate([
                            'Description' => $fileUploadRow['NewValue-Mixed']
                        ])
                    );
                } else {
                    $Circuit->CategoryData->Description()->dissociate();
                }
                break;

            case 'ServiceType':
                if ($fileUploadRow['NewValue-Mixed']) {
                    $Circuit->Service()->associate(
                        $Circuit->Category->ServiceTypes()->where(
                            'ServiceTypeName',
                            $fileUploadRow['NewValue-Mixed']
                        )->where('IsActive', true)->firstOrFail()
                    );
                } else {
                    $Circuit->Service()->dissociate();
                }
                break;

            case 'BillingStartDate':
                $Circuit->BillingStartDate = Carbon::parse($fileUploadRow['NewValue-Mixed']);
                break;

            case 'InstallationDT':
                $Circuit->InstallationDT = Carbon::parse($fileUploadRow['NewValue-Mixed']);
                break;

            case 'EmailAddress':
                $Circuit->CategoryData->Email = $fileUploadRow['NewValue-Mixed'];
                break;

            case 'LocationA':
            case 'LocationZ':
            case 'ServiceAddress':
                switch ($fileUploadRow['OldValue']) {
                    case 'LocationA':
                        $Address = 'LocationAAddress';
                        $AddressTypeName = 'Location A Address';
                        break;
                    case 'LocationZ':
                        $Address = 'LocationZAddress';
                        $AddressTypeName = 'Location Z Address';
                        break;
                    case 'ServiceAddress':
                        $Address = 'ServiceAddress';
                        $AddressTypeName = 'Service Address';
                        break;
                    default:
                        throw new \Exception('Could not set $Address and $AddressTypeName.'); // This should be impossible.
                }
                if (
                    $fileUploadRow['NewValue-Mixed'] ||
                    $fileUploadRow['NewValue-Address2'] ||
                    $fileUploadRow['NewValue-City'] ||
                    $fileUploadRow['NewValue-State'] ||
                    $fileUploadRow['NewValue-Zip']
                ) {
                    if ($Circuit->CategoryData->$Address()->count()) {
                        $Circuit->CategoryData->$Address->Address1  = $fileUploadRow['NewValue-Mixed'];
                        $Circuit->CategoryData->$Address->Address2  = $fileUploadRow['NewValue-Address2'];
                        $Circuit->CategoryData->$Address->City      = $fileUploadRow['NewValue-City'];
                        $Circuit->CategoryData->$Address->State     = $fileUploadRow['NewValue-State'];
                        $Circuit->CategoryData->$Address->Zip       = $fileUploadRow['NewValue-Zip'];

                        $Circuit->CategoryData->$Address->save();
                    } else {
                        $Circuit->CategoryData->$Address()->associate(
                            AddressType::where(
                                'AddressTypeName',
                                $AddressTypeName
                            )->firstOrFail()->Addresses()->create([
                                'Address1'  => $fileUploadRow['NewValue-Mixed'],
                                'Address2'  => $fileUploadRow['NewValue-Address2'],
                                'City'      => $fileUploadRow['NewValue-City'],
                                'State'     => $fileUploadRow['NewValue-State'],
                                'Zip'       => $fileUploadRow['NewValue-Zip'],
                            ])
                        );
                    }
                } else {
                    $Circuit->CategoryData->$Address()->dissociate();
                }
                break;
        }

        $Circuit->CategoryData->save();

        $Circuit->save();
    }
}