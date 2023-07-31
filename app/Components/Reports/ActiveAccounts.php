<?php

namespace App\Components\Reports;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class ActiveAccounts
 * @package App\Components\Reports
 */
class ActiveAccounts extends AbstractReport
{
    /**
     * @var string
     */
    protected $storedProcedure = 'spACOE_RptActiveAccounts';

    /**
     *
     */
    public function init()
    {
        $this->identify(false, self::CATEGORY_GENERAL, 'Active Account Report', 70);
    }

    /**
     * Return the column names for if the report is empty.
     *
     * @return array
     */
    protected function fallbackColumns()
    {
        return [
            'AccountNumber',
            'Carrier',
            'Status',
            'DateEntered',
        ];
    }

    /**
     * @return source
     */
    protected function source()
    {
        return DB::select(
            'EXEC :sp',
            [
                'sp' => $this->storedProcedure,
            ]
        );
    }
}