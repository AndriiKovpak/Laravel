<?php

namespace App\Components\Reports;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class MissingInvoices
 * @package App\Components\Reports
 */
class MissingInvoices extends AbstractReport
{
    /**
     * @var string
     */
    protected $storedProcedure = 'spACOE_RptMissingInvoiceReport';

    /**
     *
     */
    public function init()
    {
        $this->identify(false, self::CATEGORY_GENERAL, 'Missing Invoice', 100);
    }

    /**
     * Return the column names for if the report is empty.
     *
     * @return array
     */
    protected function fallbackColumns()
    {
        return [
            'id',
            'BTNAccountID',
            'BTNAccountNum',
            'CarrierAccountNum',
            'CarrierName',
            'LastBillDate',
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