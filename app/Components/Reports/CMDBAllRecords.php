<?php

namespace App\Components\Reports;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class CMDBAllRecords
 * @package App\Components\Reports
 */
class CMDBAllRecords extends AbstractReport
{
    /**
     * @var string
     */
    protected $storedProcedure = 'spACOE_RptCMDBInventoryReportAll';

    /**
     *
     */
    public function init()
    {
        $this->identify(false, self::CATEGORY_DO_NOT_RUN, 'CMDB All Records', 60);
    }

    /**
     * Return the column names for if the report is empty.
     *
     * sp_describe_first_result_set cannot produce this one because of dynamic SQL.
     *
     * @return array
     */
    protected function fallbackColumns()
    {
        return [
            'RecordID',
            'District',
            'Account Number',
            'CarrierName',
            'Service Type',
            'Description',
            'Telephone # / Circuit ID',
            'Status',
            'Category',
            'Circuit District',
            'Service Address',
            'Service Address 2',
            'City',
            'State',
            'Zip',
            'Notes',
            'SPID/Telephone #1',
            'SPID/Telephone #2',
            'Network IP Address',
            'ILEC ID',
            'Point to Telephone #',
            'CommittedInfoRate',
            'Port',
            'Mileage',
            'Start Date',
            'Contract Exp Date',
            'Installation Date',
            'Carrier Telephone #',

            'LocAAddress',
            'LocAAddress2',
            'LocACity',
            'LocAState',
            'LocAZip',

            'LocZAddress',
            'LocZAddress2',
            'LocZCity',
            'LocZState',
            'LocZZip',

            'Feature1',
            'Feature2',
            'Feature3',
            'Feature4',
            'Feature5',
            'Feature6',
            'Feature7',
            'Feature8',
            'Feature9',
            'Feature10',
        ];
    }

    /**
     * @return source
     */
    protected function source()
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '3600');
        return DB::select(
            'EXEC :sp',
            [
                'sp' => $this->storedProcedure,
            ]
        );
    }
}