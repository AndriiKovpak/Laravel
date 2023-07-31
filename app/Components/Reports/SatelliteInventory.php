<?php

namespace App\Components\Reports;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class SatelliteInventory
 * @package App\Components\Reports
 */
class SatelliteInventory extends AbstractReport
{
    /**
     * @var string
     */
    protected $storedProcedure = 'spACOE_RptInventorySatellite';

    /**
     *
     */
    public function init()
    {
        $this->identify(false, self::CATEGORY_INVENTORY, 'Satellite Inventory', 49);
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
            'REF#/Person\'sName',
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
     * @return source
     */
    protected function source()
    {
        return DB::select(
            'EXEC :sp @UserID = :UserID',
            [
                'sp' => $this->storedProcedure,
                'UserID' => Auth::id(),
            ]
        );
    }
}