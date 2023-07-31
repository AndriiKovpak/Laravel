<?php

namespace App\Components\Reports;

use App\Models\BTNAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class AllBTNsAccountNumbers
 * @package App\Components\Reports
 */
class AllBTNsAccountNumbers extends AbstractReport
{
    /**
     * @var string
     */
    protected $storedProcedure = 'spACOE_RptBTNsWithDivisionDistrict';

    /**
     *  Initialize the report.
     */
    public function init()
    {
        $this->identify(false, self::CATEGORY_GENERAL, 'All BTN\'s Account Numbers', 10);
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
            'Voice',
            'Data',
            'Satellite',
            'Status',
            'Processed',
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
            'EXEC :sp @UserID = :UserID',
            [
                'sp' => $this->storedProcedure,
                'UserID' => Auth::id(),
            ]
        );
    }
}
