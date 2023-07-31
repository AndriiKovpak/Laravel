<?php

namespace App\Components\Reports;

use App\Models\BTNAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class ActiveAccountsNoAP
 * @package App\Components\Reports
 */
class ActiveAccountsNoAP extends AbstractReport
{
    /**
     * @var string
     */
    protected $storedProcedure = 'spACOE_RptActiveBTNWithNoAP';

    /**
     *  Identify the report.
     */
    public function init()
    {
        $this->identify(false, self::CATEGORY_GENERAL, 'Active Accounts with NO AP', 53 );
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
        ];
    }

    /**
     * Return the data source.
     *
     * @return \Illuminate\Database\Query\Builder
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