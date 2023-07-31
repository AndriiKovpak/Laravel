<?php

namespace App\Services\Settings\FileUploads;

use App\Models\Address;
use App\Models\AddressType;
use App\Models\BTNAccount;
use App\Models\BTNStatusType;
use App\Models\Category;
use App\Models\Circuit;
use App\Models\CircuitDescription;
use App\Models\DivisionDistrict;
use App\Models\ServiceType;
use App\Models\StateCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class NewCircuitsFileUploadsService extends BaseFileUploadsService
{
    protected $fileType = 'New Circuits';

    protected $columnNames = [
        'AccountNum',                   // BTNAccounts.AccountNum
        'CarrierCircuitID',             // Circuits.CarrierCircuitID
        'CategoryName',                 // Categories.CategoryName
        'ServiceTypeName',              // ServiceTypes.ServiceTypeName
        'Description',                  // CircuitDescriptions.Description (Only for Voice or Data)
        'StatusName',                   // BTNStatusTypes.BTNStatusName (Circuit.Status, not BTNAccounts.Status)
        'BillUnderBTN',                 // Circuits.BillUnderBTN
        'Cost',                         // Circuits.Cost
        'ServiceAddress-Address1',      // Addresses.Address1 (Only for Voice or Data)
        'ServiceAddress-Address2',      // Addresses.Address2 (Only for Voice or Data)
        'ServiceAddress-City',          // Addresses.City (Only for Voice or Data)
        'ServiceAddress-State',         // Addresses.State (Only for Voice or Data)
        'ServiceAddress-Zip',           // Addresses.Zip (Only for Voice or Data)
        'Email',                        // CircuitsVoice.Email or CircuitsData.Email (Only for Voice or Data)
        'Note',                         // CircuitNotes.Note
        'LocationAAddress-Address1',    // Addresses.Address1 (Only for Voice or Data)
        'LocationAAddress-Address2',    // Addresses.Address2 (Only for Voice or Data)
        'LocationAAddress-City',        // Addresses.City (Only for Voice or Data)
        'LocationAAddress-State',       // Addresses.State (Only for Voice or Data)
        'LocationAAddress-Zip',         // Addresses.Zip (Only for Voice or Data)
        'LocationZAddress-Address1',    // Addresses.Address1 (Only for Voice or Data)
        'LocationZAddress-Address2',    // Addresses.Address2 (Only for Voice or Data)
        'LocationZAddress-City',        // Addresses.City (Only for Voice or Data)
        'LocationZAddress-State',       // Addresses.State (Only for Voice or Data)
        'LocationZAddress-Zip',         // Addresses.Zip (Only for Voice or Data)
        'QoS_CIR',                      // CircuitsData.QoS_CIR (Only for Data)
        'PortSpeed',                    // CircuitsData.PortSpeed (Only for Data)
        'Mileage',                      // CircuitsData.Mileage (Only for Data)
        'NetworkIPAddress',             // CircuitsData.NetworkIPAddress (Only for Data)
        'PointToNumber',                // CircuitsVoice.PointToNumber (Only for Voice)
        'SPID_Phone1',                  // CircuitsVoice.SPID_Phone1 (Only for Voice)
        'SPID_Phone2',                  // CircuitsVoice.SPID_Phone2 (Only for Voice)
        'AssignedToName',               // CircuitsSatellite.AssignedToName (Only for Satellite)
        'DivisionDistrictCode',         // DivisionDistricts.DivisionDistrictCode (Check that it matches BTNAccounts.DivisionDistrictID and add to Circuits.DivisionDistrictID?)
        'DeviceType',                   // CircuitsSatellite.DeviceType (Only for Satellite)
        'DeviceMake',                   // CircuitsSatellite.DeviceMake (Only for Satellite)
        'DeviceModel',                  // CircuitsSatellite.DeviceModel (Only for Satellite)
        'IMEI',                         // CircuitsSatellite.IMEI (Only for Satellite)
        'SIM',                          // CircuitsSatellite.SIM (Only for Satellite)
        'LD_PIC',                       // CircuitsVoice.LD_PIC (Only for Voice)
        'InstallationDT',               // Circuits.InstallationDT
    ];

    /**
     * Validate a New Circuit row in two steps
     *
     * 1. Make sure each New Circuit only appears once in the file.
     * 2. Validate the row attributes.
     *
     * @param array $fileUploadRow This array holds the information being validated.
     *
     * @return void An exception is thrown if validation fails.
     */
    protected function validateRow(array $fileUploadRow)
    {
        $fileUploadRow = $this->makeRowAssociativeArray($fileUploadRow);

        // 1. Make sure each New Circuit only appears once in the file.
        // However, this check could cause problems with very large files.
        $rowKey = $fileUploadRow['AccountNum'] . $fileUploadRow['CarrierCircuitID'];
        $this->validateArray(['New Circuit' => $rowKey], [
            'New Circuit' => [Rule::notIn(array_keys($this->rowKeys))],
        ], [
            'not_in' => 'The specified :attribute has already appeared on an earlier row.',
        ], [
            'New Circuit' => 'New Circuit',
        ]);
        $this->rowKeys[$rowKey] = true;

        // 2. Validate the row attributes.
        // Standard validation for the row
        $this->validateArray($fileUploadRow, [
            'AccountNum'                => ['bail', 'required', 'max:50', Rule::exists((new BTNAccount)->getTable(), 'AccountNum')],
            'CarrierCircuitID'          => ['bail', 'required', 'max:50',
                                            Rule::unique((new Circuit)->getTable(), 'CarrierCircuitID') // Check that Circuit does not exist belonging to this BTNAccount.
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
            'CategoryName'              => ['bail', 'required', 'max:50', Rule::exists((new Category)->getTable(), 'CategoryName')->where('IsActive', true)], // Required by view
            'ServiceTypeName'           => ['bail', 'nullable', 'max:50',
                                            Rule::exists((new ServiceType)->getTable(), 'ServiceTypeName') // Check that ServiceType exists and belongs to this Category.
                                                ->using(function ($query) use ($fileUploadRow) {
                                                    /** @var \Illuminate\Database\Query\Builder $query */
                                                    $query->whereIn( // Using a subquery because joins don't work in this context.
                                                        'Category',
                                                        DB::table((new Category)->getTable())
                                                            ->select('CategoryID')
                                                            ->where('CategoryName', $fileUploadRow['CategoryName'])
                                                            ->where('IsActive', true)
                                                    );
                                                })
                                                ->where('IsActive', true),
                                        ],
            'Description'               => ['nullable', 'max:50'],
            'StatusName'                => ['bail', 'required', 'max:50', Rule::exists((new BTNStatusType())->getTable(), 'BTNStatusName')], // Required by view
            'BillUnderBTN'              => ['nullable', 'max:50'],
            'Cost'                      => ['nullable', 'numeric', 'min:0', 'max:1500000'],
            'ServiceAddress-Address1'   => ['nullable', 'max:50'],
            'ServiceAddress-Address2'   => ['nullable', 'max:50'],
            'ServiceAddress-City'       => ['nullable', 'max:50'],
            'ServiceAddress-State'      => ['bail', 'nullable', 'max:2', Rule::exists((new StateCode())->getTable(), 'State')],
            'ServiceAddress-Zip'        => ['nullable', 'max:20'],
            'Email'                     => ['nullable', 'email', 'max:50'],
            'Note'                      => ['nullable', 'max:1000'],
            'LocationAAddress-Address1' => ['nullable', 'max:50'],
            'LocationAAddress-Address2' => ['nullable', 'max:50'],
            'LocationAAddress-City'     => ['nullable', 'max:50'],
            'LocationAAddress-State'    => ['bail', 'nullable', 'max:2', Rule::exists((new StateCode())->getTable(), 'State')],
            'LocationAAddress-Zip'      => ['nullable', 'max:20'],
            'LocationZAddress-Address1' => ['nullable', 'max:50'],
            'LocationZAddress-Address2' => ['nullable', 'max:50'],
            'LocationZAddress-City'     => ['nullable', 'max:50'],
            'LocationZAddress-State'    => ['bail', 'nullable', 'max:2', Rule::exists((new StateCode())->getTable(), 'State')],
            'LocationZAddress-Zip'      => ['nullable', 'max:20'],
            'QoS_CIR'                   => ['nullable', 'max:50'],
            'PortSpeed'                 => ['nullable', 'max:50'],
            'Mileage'                   => ['nullable', 'max:50'],
            'NetworkIPAddress'          => ['nullable', 'max:50'],
            'PointToNumber'             => ['nullable', 'max:50'],
            'SPID_Phone1'               => ['nullable', 'max:50'],
            'SPID_Phone2'               => ['nullable', 'max:50'],
            'AssignedToName'            => ['nullable', 'max:50'],
            'DivisionDistrictCode'      => ['bail', 'nullable', 'max:50',
                                            Rule::exists((new DivisionDistrict)->getTable(), 'DivisionDistrictCode') // Check that DivisionDistrict exists and this BTNAccount belongs to it.
                                                ->using(function ($query) use ($fileUploadRow) {
                                                    /** @var \Illuminate\Database\Query\Builder $query */
                                                    $query->whereIn( // Using a subquery because joins don't work in this context.
                                                        'DivisionDistrictID',
                                                        DB::table((new BTNAccount)->getTable())
                                                            ->select('DivisionDistrictID')
                                                            ->where('AccountNum', $fileUploadRow['AccountNum'])
                                                    );
                                                })
                                                ->where('IsActive', true), // If this is matching what is on the BTNAccount, does this need to check if IsActive is true?
                                        ],
            'DeviceType'                => ['nullable', 'max:50'],
            'DeviceMake'                => ['nullable', 'max:50'],
            'DeviceModel'               => ['nullable', 'max:50'],
            'IMEI'                      => ['nullable', 'max:50'],
            'SIM'                       => ['nullable', 'max:50'],
            'LD_PIC'                    => ['nullable', 'max:50'],
            'InstallationDT'            => ['nullable', 'date'],
        ], [
            'date'          => 'The :attribute must be a valid date.',
            'email'         => 'The :attribute must be a valid email address.',
            'exists'        => 'The specified :attribute does not exist.',
            'ServiceTypeName.exists'        => 'The specified :attribute does not exist for the specified Category.',
            'DivisionDistrictCode.exists'   => 'The specified :attribute does not match the :attribute for the Account.',
            'in'            => 'The :attribute must be one of the following: :values',
            'max'           => 'The :attribute may not be greater than :max characters.',
            'Cost.max'                      => 'The :attribute may not be greater than :max.',
            'min'           => 'The :attribute must be at least :min.',
            'numeric'       => 'The :attribute must be a number.',
            'required'      => 'The :attribute column is required.',
            'required_if'   => 'The :attribute column is required when :other is :value.',
            'unique'        => 'The specified :attribute already exists.',
        ], [
            'AccountNum'                => 'Account #',
            'CarrierCircuitID'          => 'Carrier Circuit ID',
            'CategoryName'              => 'Category Name',
            'ServiceTypeName'           => 'Service Type Name',
            'Description'               => 'Description',
            'StatusName'                => 'Status Name',
            'BillUnderBTN'              => 'Bill Under BTN',
            'Cost'                      => 'Cost',
            'ServiceAddress-Address1'   => 'Service Address Line 1',
            'ServiceAddress-Address2'   => 'Service Address Line 2',
            'ServiceAddress-City'       => 'Service Address City',
            'ServiceAddress-State'      => 'Service Address State',
            'ServiceAddress-Zip'        => 'Service Address Zip',
            'Email'                     => 'Email',
            'Note'                      => 'Note',
            'LocationAAddress-Address1' => 'Location A Address Line 1',
            'LocationAAddress-Address2' => 'Location A Address Line 2',
            'LocationAAddress-City'     => 'Location A Address City',
            'LocationAAddress-State'    => 'Location A Address State',
            'LocationAAddress-Zip'      => 'Location A Address Zip',
            'LocationZAddress-Address1' => 'Location Z Address Line 1',
            'LocationZAddress-Address2' => 'Location Z Address Line 2',
            'LocationZAddress-City'     => 'Location Z Address City',
            'LocationZAddress-State'    => 'Location Z Address State',
            'LocationZAddress-Zip'      => 'Location Z Address Zip',
            'QoS_CIR'                   => 'QoS/CIR',
            'PortSpeed'                 => 'Port Speed',
            'Mileage'                   => 'Mileage',
            'NetworkIPAddress'          => 'Network IP Address',
            'PointToNumber'             => 'Point To #',
            'SPID_Phone1'               => 'SPID/Phone 1',
            'SPID_Phone2'               => 'SPID/Phone 2',
            'AssignedToName'            => 'Assigned To Name',
            'DivisionDistrictCode'      => 'District Code',
            'DeviceType'                => 'Device Type',
            'DeviceMake'                => 'Device Make',
            'DeviceModel'               => 'Device Model',
            'IMEI'                      => 'IMEI',
            'SIM'                       => 'SIM',
            'LD_PIC'                    => 'LD PIC',
            'InstallationDT'            => 'Installation Date',
        ]);
    }

    /**
     * Process a New Circuit row
     *
     * Add a New Circuit based on the attributes provided in $fileUploadRow.
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
        $CircuitUpdateQueueID = $BTNAccount->Circuits()->create([
            'CarrierCircuitID' => $fileUploadRow['CarrierCircuitID'],
            'BillUnderBTN' => $fileUploadRow['BillUnderBTN'],
            'Cost' => $fileUploadRow['Cost'],
            'InstallationDT' => Carbon::parse($fileUploadRow['InstallationDT']),
        ])->CircuitID;
        // Creation returns the Circuit with the CircuitID set to CircuitsUpdateQueue.CircuitUpdateQueueID instead of Circuits.CircuitID.
        $CircuitID = DB::table('CircuitUpdateQueue')
            ->select('CircuitID')
            ->where('CircuitUpdateQueueID', $CircuitUpdateQueueID)
            ->first()
            ->CircuitID;
        /** @var Circuit $Circuit */
        $Circuit = Circuit::find($CircuitID);

        $Circuit->Category()->associate(
            Category::where(
                'CategoryName',
                $fileUploadRow['CategoryName']
            )->where('IsActive', true)->firstOrFail()
        );

        if ($fileUploadRow['ServiceTypeName']) {
            $Circuit->Service()->associate(
                $Circuit->Category->ServiceTypes()->where(
                    'ServiceTypeName',
                    $fileUploadRow['ServiceTypeName']
                )->where('IsActive', true)->firstOrFail()
            );
        }

        $Circuit->StatusType()->associate(
            BTNStatusType::where(
                'BTNStatusName',
                $fileUploadRow['StatusName']
            )->firstOrFail()
        );

        if ($fileUploadRow['Note']) {
            $Circuit->Notes()->create([
                'Note' => $fileUploadRow['Note']
            ]);
        }

        if ($fileUploadRow['DivisionDistrictCode']) {
            $Circuit->DivisionDistrict()->associate(
                $BTNAccount->DivisionDistrict // Why bother querying the database by code and BTNAccount when it has to be the one already associated with BTNAccount?
            );
        }

        if ($Circuit->Category->isVoice() || $Circuit->Category->isData()) {
            if ($Circuit->Category->isVoice()) {
                $Circuit->CircuitVoice()->create([
                    'PointToNumber' => $fileUploadRow['PointToNumber'],
                    'SPID_Phone1'   => $fileUploadRow['SPID_Phone1'],
                    'SPID_Phone2'   => $fileUploadRow['SPID_Phone2'],
                    'LD_PIC'        => $fileUploadRow['LD_PIC'],
                ]);
            } else if ($Circuit->Category->isData()) {
                $Circuit->CircuitData()->create([
                    'QoS_CIR'           => $fileUploadRow['QoS_CIR'],
                    'PortSpeed'         => $fileUploadRow['PortSpeed'],
                    'Mileage'           => $fileUploadRow['Mileage'],
                    'NetworkIPAddress'  => $fileUploadRow['NetworkIPAddress'],
                ]);
            }

            if ($fileUploadRow['Description']) {
                $Circuit->CategoryData->Description()->associate(
                    CircuitDescription::firstOrCreate([
                        'Description'   => $fileUploadRow['Description']
                    ])
                );
            }

            if (
                $fileUploadRow['ServiceAddress-Address1'] ||
                $fileUploadRow['ServiceAddress-Address2'] ||
                $fileUploadRow['ServiceAddress-City'] ||
                $fileUploadRow['ServiceAddress-State'] ||
                $fileUploadRow['ServiceAddress-Zip']
            ) {
                $Circuit->CategoryData->ServiceAddress()->associate(
                    AddressType::where(
                        'AddressTypeName',
                        'Service Address'
                    )->firstOrFail()->Addresses()->create([
                        'Address1'  => $fileUploadRow['ServiceAddress-Address1'],
                        'Address2'  => $fileUploadRow['ServiceAddress-Address2'],
                        'City'      => $fileUploadRow['ServiceAddress-City'],
                        'State'     => $fileUploadRow['ServiceAddress-State'],
                        'Zip'       => $fileUploadRow['ServiceAddress-Zip'],
                    ])
                );
            }

            $Circuit->CategoryData->Email = $fileUploadRow['Email'];

            if (
                $fileUploadRow['LocationAAddress-Address1'] ||
                $fileUploadRow['LocationAAddress-Address2'] ||
                $fileUploadRow['LocationAAddress-City'] ||
                $fileUploadRow['LocationAAddress-State'] ||
                $fileUploadRow['LocationAAddress-Zip']
            ) {
                $Circuit->CategoryData->LocationAAddress()->associate(
                    AddressType::where(
                        'AddressTypeName',
                        'Location A Address'
                    )->firstOrFail()->Addresses()->create([
                        'Address1'  => $fileUploadRow['LocationAAddress-Address1'],
                        'Address2'  => $fileUploadRow['LocationAAddress-Address2'],
                        'City'      => $fileUploadRow['LocationAAddress-City'],
                        'State'     => $fileUploadRow['LocationAAddress-State'],
                        'Zip'       => $fileUploadRow['LocationAAddress-Zip'],
                    ])
                );
            }

            if (
                $fileUploadRow['LocationZAddress-Address1'] ||
                $fileUploadRow['LocationZAddress-Address2'] ||
                $fileUploadRow['LocationZAddress-City'] ||
                $fileUploadRow['LocationZAddress-State'] ||
                $fileUploadRow['LocationZAddress-Zip']
            ) {
                $Circuit->CategoryData->LocationZAddress()->associate(
                    AddressType::where(
                        'AddressTypeName',
                        'Location Z Address'
                    )->firstOrFail()->Addresses()->create([
                        'Address1'  => $fileUploadRow['LocationZAddress-Address1'],
                        'Address2'  => $fileUploadRow['LocationZAddress-Address2'],
                        'City'      => $fileUploadRow['LocationZAddress-City'],
                        'State'     => $fileUploadRow['LocationZAddress-State'],
                        'Zip'       => $fileUploadRow['LocationZAddress-Zip'],
                    ])
                );
            }

            $Circuit->CategoryData->save();
        } else if ($Circuit->Category->isSatellite()) {
            $Circuit->CircuitSatellite()->create([
                'AssignedToName'    => $fileUploadRow['AssignedToName'],
                'DeviceType'        => $fileUploadRow['DeviceType'],
                'DeviceMake'        => $fileUploadRow['DeviceMake'],
                'DeviceModel'       => $fileUploadRow['DeviceModel'],
                'IMEI'              => $fileUploadRow['IMEI'],
                'SIM'               => $fileUploadRow['SIM'],
            ]);
        }

        $Circuit->save();
    }
}
