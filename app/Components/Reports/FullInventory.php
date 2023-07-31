<?php

namespace App\Components\Reports;

use App\Models\BTNAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class FullInventory
 * @package App\Components\Reports
 */
class FullInventory extends AbstractReport
{
    /**
     * @var string
     */
    protected $storedProcedure = 'spACOE_RptInventoryFull';

    /**
     *  Identify the report.
     */
    public function init()
    {
        $this->identify(false, self::CATEGORY_INVENTORY, 'Full Inventory Report', 45);
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
            'CarrierName',
            'SiteName',
            'Address1',
            'Address2',
            'City',
            'State',
            'Zip',
            'CarrierCircuitID',

            'ChargeAmt',
            'TotalCost',
            'ServiceTypeName',
            'CircuitTypeName',
            'LECCircuitID',
            'CircuitDescr',
            'FrameRelayDLCI',
            'CommittedInfoRate',
            'PortSpeed',

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
            'CFN',
            'ILEC1',
            'ILEC2',
            'REF#/Person\'s Name',
            'CellPersonName',
            'MaintenanceCost',
            'SPID_Phone1',
            'SPID_Phone2',
            'Mileage',
            'StatusName',
            'BillingStartDT',
            'BillUnderAcctNum',
            'CircuitSiteName',
            'CircuitAddress1',
            'CircuitAddress2',
            'CircuitCity',
            'CircuitState',
            'CircuitZip',
        ];
    }

    /**
     * Return the data source.
     *
     * @return \Illuminate\Database\Query\Builder
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