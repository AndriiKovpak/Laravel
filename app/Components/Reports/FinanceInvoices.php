<?php

namespace App\Components\Reports;
use Illuminate\Support\Facades\DB;

/**
 * Class FinanceInvoices
 * @package App\Components\Reports
 */
class FinanceInvoices extends AbstractReport
{
    /**
     * @var string
     */
    protected $storedProcedure = 'spACOE_RptFinanceInvoices';

    /**
     *
     */
    public function init()
    {
        $this->identify(false, self::CATEGORY_DO_NOT_RUN, 'Finance Invoice Report', 107);
    }

    /**
     * Return the column names for if the report is empty.
     *
     * @return array
     */
    protected function fallbackColumns()
    {
        return [
            'Account Number',
            'Bill Date',
            'Image Date Time',
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
            'EXEC :sp',
            [
                'sp' => $this->storedProcedure,
            ]
        );
    }
}