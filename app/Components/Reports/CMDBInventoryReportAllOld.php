<?php

namespace App\Components\Reports;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
// use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

/**
 * Class CMDBInventoryReportAllOld
 * @package App\Components\Reports
 */
class CMDBInventoryReportAllOld extends AbstractReport implements WithCustomCsvSettings
{
    /**
     * @var string
     */
    protected $storedProcedure = 'spACOE_RptCMDBInventoryReportAllOld';

    /**
     *
     */
    public function init()
    {
        $this->identify(false, self::CATEGORY_GENERAL, 'CMDB Inventory Report All Old', 108); // change reportID number
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
            'Carrier',
            'Service Type',
            'Description',
            'Telephone # / Circuit ID',
            'Deployed',
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
            'ILEC1',
            'Point to Telephone #',
            'CIR/CAR',
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

    public function getCsvSettings(): array
    {
        return [
            'output_encoding' => 'ISO-8859-1'
        ];
    }

    /**
    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }
        return parent::bindValue($cell, $value);
    }
    */

    /**
     * @return source
     */
    protected function source()
    {
        return DB::select(
            'EXEC :sp',
            [
                'sp' => $this->storedProcedure,
            ]
        );
    }
}
