<?php

namespace App\Components\Reports;

use App\Models\CarrierContact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class CarriersWithContactNumber
 * @package App\Components\Reports
 */
class CarriersWithContactNumber extends AbstractReport
{
    /**
     * @var string
     */
    protected $storedProcedure = 'spACOE_RptCarriers';

    /**
     *  Initialize the report.
     */
    protected function init()
    {
        $this->identify(false, self::CATEGORY_GENERAL, 'Carriers with Contact Number', 1);
    }

    /**
     * Return the column names for if the report is empty.
     *
     * @return array
     */
    protected function fallbackColumns()
    {
        return [
            'CarrierName',
            'PhoneNumber',
            'ContactName',
            'ContactTitle',
            'ContactPhoneNumber',
            'ContactEmailAddress',
            'ContactPhoneExt',
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
            'EXEC :sp @userid = :userid, @StartDT = :StartDT, @EndDT = :EndDT, @UserLoginID = :UserLoginID',
            [
                'sp' => $this->storedProcedure,
                'userid' => Auth::id(), // This does not appear to be used by the stored procedure
                'StartDT' => null,
                'EndDT' => null,
                'UserLoginID' => 0,
            ]
        );
    }
}