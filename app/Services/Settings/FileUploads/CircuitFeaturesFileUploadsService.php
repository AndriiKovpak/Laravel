<?php

namespace App\Services\Settings\FileUploads;

use App\Models\BTNAccount;
use App\Models\BTNStatusType;
use App\Models\Circuit;
use App\Models\CircuitFeature;
use App\Models\FeatureType;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CircuitFeaturesFileUploadsService extends BaseFileUploadsService
{
    protected $fileType = 'Circuit Features';

    protected $columnNames = [
        'AccountNum',           // BTNAccounts.AccountNum
        'CarrierCircuitID',     // Circuits.CarrierCircuitID
        'FeatureCode',          // FeatureTypes.FeatureCode
        'FeatureCost',          // CircuitFeatures.FeatureCost
        'Action',               //
    ];

    /**
     * Validate a Circuit Features row in three steps
     *
     * 1. Make sure each Circuit Feature only appears once in the file (skipping this would complicate step #3).
     * 2. Validate the row attributes, including BTNAccount, Circuit, and FeatureType.
     * 3. Make sure only nonexistent Circuit Features are added, and only existent ones are updated or deleted.
     *
     * @param array $fileUploadRow This array holds the information being validated.
     *
     * @return void An exception is thrown if validation fails.
     */
    protected function validateRow(array $fileUploadRow)
    {
        $fileUploadRow = $this->makeRowAssociativeArray($fileUploadRow);

        // 1. Make sure each Circuit Feature only appears once in the file (skipping this would complicate step #3).
        // It wouldn't make sense to add, update, and/or delete the same Circuit Feature multiple times in one batch,
        //     and it would make it harder to validate.
        // However, this check could cause problems with very large files.
        $rowKey = $fileUploadRow['AccountNum'] . $fileUploadRow['CarrierCircuitID'] . $fileUploadRow['FeatureCode'];
        $this->validateArray(['Circuit Feature' => $rowKey], [
            'Circuit Feature' => [Rule::notIn(array_keys($this->rowKeys))],
        ], [
            'not_in' => 'The specified :attribute has already appeared on an earlier row.',
        ], [
            'Circuit Feature' => 'Circuit Feature',
        ]);
        $this->rowKeys[$rowKey] = true;

        // 2. Validate the row attributes, including BTNAccount, Circuit, and FeatureType.
        // Standard validation for the row
        $this->validateArray($fileUploadRow, [
            'AccountNum'        => ['bail', 'required', 'max:50', Rule::exists((new BTNAccount)->getTable(), 'AccountNum')],
            'CarrierCircuitID'  => ['bail', 'required', 'max:50',
                                    Rule::exists((new Circuit)->getTable(), 'CarrierCircuitID')// Check that Circuit exists and belongs to this BTNAccount.
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
            'FeatureCode'       => ['bail', 'required', 'max:50', Rule::exists((new FeatureType)->getTable(), 'FeatureCode')->where('IsActive', true)],
            'FeatureCost'       => ['nullable', 'required_if:Action,ADD,UPDATE', 'numeric', 'min:0'],
            'Action'            => ['required', 'in:ADD,UPDATE,DELETE'],
        ], [
            'exists'        => 'The specified :attribute does not exist.',
            'in'            => 'The :attribute must be one of the following: :values',
            'max'           => 'The :attribute may not be greater than :max characters.',
            'min'           => 'The :attribute must be at least :min.',
            'numeric'       => 'The :attribute must be a number.',
            'required'      => 'The :attribute column is required.',
            'required_if'   => 'The :attribute column is required when :other is :value.',
        ], [
            'AccountNum'        => 'Account #',
            'CarrierCircuitID'  => 'Carrier Circuit ID',
            'FeatureCode'       => 'Feature Code',
            'FeatureCost'       => 'Feature Cost',
            'Action'            => 'Action',
        ]);

        // 3. Make sure only nonexistent Circuit Features are added, and only existent ones are updated or deleted.
        // This should work if the previous validation passed.
        if (
            CircuitFeature::whereHas(
                'Circuit',
                function ($query) use ($fileUploadRow) {
                    /** @var \Illuminate\Database\Eloquent\Builder $query */
                    $query->where(
                        'CarrierCircuitID',
                        $fileUploadRow['CarrierCircuitID']
                    )->whereHas(
                        'BTNAccount',
                        function ($query) use ($fileUploadRow) {
                            /** @var \Illuminate\Database\Eloquent\Builder $query */
                            $query->where(
                                'AccountNum',
                                $fileUploadRow['AccountNum']
                            );
                        }
                    );
                }
            )->whereHas(
                'Feature', // The relationship name is unusual because of the database columns.
                function ($query) use ($fileUploadRow) {
                    /** @var \Illuminate\Database\Eloquent\Builder $query */
                    $query->where(
                        'FeatureCode',
                        $fileUploadRow['FeatureCode']
                    )->where('IsActive', true);
                }
            )->exists()
        ) {
            $validActions = ['UPDATE', 'DELETE'];
        } else {
            $validActions = ['ADD'];
        }
        $this->validateArray($fileUploadRow, [
            'Action' => [Rule::in($validActions)],
        ], [
            'Action.in' => 'The :attribute for the specified Circuit Feature must be one of the following: :values',
        ], [
            'Action' => 'Action',
        ]);

        // 4. Make sure FeatureType's CategoryID matches the Circuit's CategoryID.
        // Only necessary for ADD or UPDATE
        if ($fileUploadRow['Action'] == 'ADD' || $fileUploadRow['Action'] == 'UPDATE') {
            $this->validateArray($fileUploadRow, [
                'FeatureCode'   => [Rule::exists((new FeatureType)->getTable(), 'FeatureCode')
                                    ->using(function ($query) use ($fileUploadRow) {
                                        /** @var \Illuminate\Database\Query\Builder $query */
                                        $query->whereIn( // Using a subquery because joins don't work in this context.
                                            'CategoryID',
                                            DB::table((new Circuit)->getTable())
                                                ->select('CategoryID')
                                                ->where('CarrierCircuitID', $fileUploadRow['CarrierCircuitID'])
                                        );
                                    })->where('IsActive', true)],
            ], [
                'FeatureCode.exists' => 'The specified Feature does not match the Category of the specified Circuit.',
            ], [
                'FeatureCode' => 'Feature Code',
            ]);
        }
    }


    /**
     * Process a Circuit Feature row
     *
     * Add, update, or delete a CircuitFeature based on the attributes provided in $fileUploadRow.
     *
     * @param array $fileUploadRow This array holds the information about the CircuitFeature and the action being performed.
     *
     * @return void
     */
    protected function processRow(array $fileUploadRow)
    {
        $fileUploadRow = $this->makeRowAssociativeArray($fileUploadRow);

        if ($fileUploadRow['Action'] == 'ADD') {
            $CircuitFeature = new CircuitFeature;
            $CircuitFeature->Circuit()->associate(
                Circuit::where(
                    'CarrierCircuitID',
                    $fileUploadRow['CarrierCircuitID']
                )->whereHas(
                    'BTNAccount',
                    function ($query) use ($fileUploadRow) {
                        /** @var \Illuminate\Database\Eloquent\Builder $query */
                        $query->where(
                            'AccountNum', $fileUploadRow['AccountNum']
                        );
                    }
                )->whereHas(
                    'StatusType',
                    function ($query) {
                        /** @var \Illuminate\Database\Eloquent\Builder $query */
                        $query->display();
                    }
                )->firstOrFail()
            );
            $CircuitFeature->Feature()->associate( // The relationship name is unusual because of the database columns.
                FeatureType::where(
                    'FeatureCode',
                    $fileUploadRow['FeatureCode']
                )->where('IsActive', true)->firstOrFail()
            );
            $CircuitFeature->FeatureCost = $fileUploadRow['FeatureCost'];
            $CircuitFeature->save();
        } else if ($fileUploadRow['Action'] == 'UPDATE' || $fileUploadRow['Action'] == 'DELETE') {
            $CircuitFeature = CircuitFeature::whereHas(
                'Circuit',
                function ($query) use ($fileUploadRow) {
                    /** @var \Illuminate\Database\Eloquent\Builder $query */
                    $query->where(
                        'CarrierCircuitID',
                        $fileUploadRow['CarrierCircuitID']
                    )->whereHas(
                        'BTNAccount',
                        function ($query) use ($fileUploadRow) {
                            /** @var \Illuminate\Database\Eloquent\Builder $query */
                            $query->where(
                                'AccountNum',
                                $fileUploadRow['AccountNum']
                            );
                        }
                    );
                }
            )->whereHas(
                'Feature', // The relationship name is unusual because of the database columns.
                function ($query) use ($fileUploadRow) {
                    $query->where(
                        /** @var \Illuminate\Database\Eloquent\Builder $query */
                        'FeatureCode',
                        $fileUploadRow['FeatureCode']
                    )->where('IsActive', true);
                }
            )->firstOrFail();

            if ($fileUploadRow['Action'] == 'UPDATE') {
                $CircuitFeature->FeatureCost = $fileUploadRow['FeatureCost'];
                $CircuitFeature->save();
            } else if ($fileUploadRow['Action'] == 'DELETE') {
                $CircuitFeature->delete();
            }
        }
    }
}