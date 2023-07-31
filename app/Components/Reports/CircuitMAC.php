<?php

namespace App\Components\Reports;

use App\Models\CircuitMAC as MACModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class CircuitMAC
 * @package App\Components\Reports
 */
class CircuitMAC extends AbstractReport
{
    /**
     * @var string
     */
    protected $storedProcedure = 'spACOE_RptMACReport';

    /**
     *
     */
    public function init()
    {
        $this->identify(true, self::CATEGORY_GENERAL, 'Circuit MAC Report', 57);
    }

    /**
     * Return the column names for if the report is empty.
     *
     * @return array
     */
    protected function fallbackColumns()
    {
        return [
            'District',
            'Account Number',
            'BTN',
            'CarrierName',
            'Contact Date',
            'Contact Name',
            'Contact Phone',
            'Order Number',
            'Contract Exp Date',
            'Disconnect Date',
            'Description',
            'MACType',
            'CarrierCircuitID',
            'MACNote',
        ];
    }

    /**
     * Return the data source for this report.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function source()
    {
        return DB::select(
            'EXEC :sp @userID = :userID, @StartDT = :StartDT, @EndDT = :EndDT',
            [
                'sp' => $this->storedProcedure,
                'userID' => Auth::id(), // This does not appear to be used by the stored procedure
                'StartDT' => $this->dateRange['from']->format('m/d/Y'),
                'EndDT' => $this->dateRange['to']->format('m/d/Y'),
            ]
        );
    }
}