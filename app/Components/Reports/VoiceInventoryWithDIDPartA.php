<?php

namespace App\Components\Reports;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class VoiceInventoryWithDID
 * @package App\Components\Reports
 */
class VoiceInventoryWithDIDPartA extends AbstractReport
{
    /**
     * @var string
     */
    protected $storedProcedure = 'spACOE_RptInventoryLocalWithDID_Part_A';

    /**
     *
     */
    public function init()
    {
        $this->identify(false, self::CATEGORY_INVENTORY, 'Voice Inventory with DID Part A', 71);
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
            'District',
            'BillingTelephoneNumber',
            'AccountNumber',
            'BillUnderAccount',
            'CarrierName',
            'District',
            'ServiceTypeName',
            'Description',
            'CircuitID/Phone',
            'StatusName',
            'LECCircuitID',
            'SPID_Phone1',
            'SPID_Phone2',
            'ChargeAmt',
            'TotalCost',
            'EmailAddress',
            'PointTo#',
            'LD PIC',

            'Notes',

            'ServiceAddress',
            'ServiceAddress2',
            'ServiceAddressCity',
            'ServiceAddressState',
            'ServiceAddressZip',

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

            'BillingStartDT',
            'CreateDT',
            'DID',
        ];
    }

    /**
     * @return source
     */
    protected function source()
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '600');
        return DB::select(
            'EXEC :sp @UserID = :UserID',
            [
                'sp' => $this->storedProcedure,
                'UserID' => Auth::id(),
            ]
        );
    }
}
