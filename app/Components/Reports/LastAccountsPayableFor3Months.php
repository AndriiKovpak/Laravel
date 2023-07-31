<?php

namespace App\Components\Reports;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class LastAccountsPayableFor3Months
 * @package App\Components\Reports
 */
class LastAccountsPayableFor3Months extends LastAccountsPayable
{
    /**
     * @var string
     */
    protected $storedProcedure = 'spACOE_LastAPReport';

    /**
     *
     */
    public function init()
    {
        $this->identify(false, self::CATEGORY_GENERAL, 'Last AP Report (3 Months)', 50);
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