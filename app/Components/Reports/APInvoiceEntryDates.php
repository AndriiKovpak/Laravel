<?php

namespace App\Components\Reports;

use App\Models\InvoiceAP;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class APInvoiceEntryDates
 * @package App\Components\Reports
 */
class APInvoiceEntryDates extends AbstractReport
{
    /**
     * @var string
     */
    protected $storedProcedure = 'spACOE_RptInvoicesAPEntryDate';

    /**
     *  Identify this report
     */
    public function init()
    {
        $this->identify(true, self::CATEGORY_GENERAL, 'AP Invoice Entry Dates',26);
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
            'InvoiceNum',
            'BillDT',
            'DueDT',
            'ServiceBeginDT',
            'ServiceEndDT',
            'CurrentCharges',
            'PastDueAmt',
            'CreditAmt',
            'TotalPaidAmt',
            'SentDT',
            'CheckNum',
            'IsImageAttached',
            'IsFinalBill',
            'CarrierName',
            'Address1',
            'Address2',
            'City',
            'State',
            'Zip',
            'Attention',
            'UserName',
            'EntryDate',
            'UserID',
            'DistrictID',
        ];
    }

    /**
     * Return the data source.
     *
     * @return mixed
     */
    protected function source()
    {
        return DB::select(
            'EXEC :sp @UserID = :UserID, @StartDT = :StartDT, @EndDT = :EndDT',
            [
                'sp' => $this->storedProcedure,
                'UserID' => Auth::id(),
                'StartDT' => $this->dateRange['from']->format('m/d/Y'),
                'EndDT' => $this->dateRange['to']->format('m/d/Y'),
            ]
        );
    }
}