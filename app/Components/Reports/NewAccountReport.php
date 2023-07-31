<?php

namespace App\Components\Reports;

/**
 * Class NewAccountReport
 * @package App\Components\Reports
 */
class NewAccountReport extends AbstractReport
{
    /**
     *
     */
    public function init()
    {
        $this->identify(false, self::CATEGORY_GENERAL, 'New Account Report', 100 );
    }

    /**
     * Return the column names for if the report is empty.
     *
     * @return array
     */
    protected function fallbackColumns()
    {

    }

    /**
     * Return the data source for this report.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function source()
    {

    }
}