<?php

namespace App\Components\Reports;

use App\Models\Carrier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class APInvoiceDelta
 * @package App\Components\Reports
 */
class APInvoiceDelta extends AbstractReport
{
    /**
     * @var string
     */
    protected $storedProcedure = 'sp_ACOE_InvoiceAP_HighMonthToMonthDelta';

    /**
     *  Identify the report.
     */
    public function init()
    {
        $this->identify(false, self::CATEGORY_GENERAL, 'AP Invoice Delta',61);
    }

    /**
     * Return the column names for if the report is empty.
     *
     * @return array
     */
    protected function fallbackColumns()
    {
        return [
            'CarrierAccountNum',
            'CarrierName',
            'BillDT_1',
            'Amount_1',
            'BillDT_2',
            'Amount_2',
            'BillDT_3',
            'Amount_3',
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
            'EXEC :sp @userid = :userid, @StartDT = :StartDT, @EndDT = :EndDT',
            [
                'sp' => $this->storedProcedure,
                'userid' => Auth::id(), // This does not appear to be used by the stored procedure, maybe
                'StartDT' => null,
                'EndDT' => null,
            ]
        );
    }
}