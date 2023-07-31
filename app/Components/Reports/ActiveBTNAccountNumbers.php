<?php

namespace App\Components\Reports;

use App\Models\BTNAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class ActiveBTNAccountNumbers
 * @package App\Components\Reports
 */
class ActiveBTNAccountNumbers extends AbstractReport
{
    /**
     * @var string
     */
    protected $storedProcedure = 'spACOE_RptActiveBTNReport';

    /**
     *  Identify the report.
     */
    public function init()
    {
        $this->identify(false, self::CATEGORY_GENERAL, 'Active BTN Account Numbers', 28);
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
            'BillingTelephoneNumber',
            'AccountNumber',
            'CarrierName',
            'SiteName',
            'Address1',
            'Address2',
            'City',
            'State',
            'Zip',
            'PDF',
            'AP',
            'Status',
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