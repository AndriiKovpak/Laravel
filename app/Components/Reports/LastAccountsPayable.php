<?php

namespace App\Components\Reports;

use App\Models\InvoiceAP;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class LastAccountsPayable
 * @package App\Components\Reports
 */
class LastAccountsPayable extends AbstractReport
{
    /**
     * @var string
     */
    protected $storedProcedure = 'spACOE_LastAPReport_OneMonth';

    /**
     *  Identify the report.
     */
    public function init()
    {
        $this->identify(false, self::CATEGORY_GENERAL, 'Last AP Report', 52);
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
            'BillingNumber',
            'AccountNumber',
            'CarrierName',
            'BillDate',
            'CurrentCharges',
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