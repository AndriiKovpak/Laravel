<?php

namespace App\Components\Reports;

use App\Models\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
/**
 * Class LoginHistory
 * @package App\Components\Reports
 */
class LoginHistory extends AbstractReport
{
    /**
     * @var string
     */
    protected $storedProcedure = 'spRptSessionHistory';

    /**
     *  Initialize the report.
     */
    protected function init()
    {
        $this->identify(false, self::CATEGORY_GENERAL, 'Login History', 14);
    }

    /**
     * Return the column names for if the report is empty.
     *
     * @return array
     */
    protected function fallbackColumns()
    {
        return [
            'FirstName',
            'LastName',
            'SessionStartDate',
            'SessionEndDate',
            'IP_Address',
            'Country',
            'State',
            'City',
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