<?php

namespace App\Components\Reports;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class CarrierBillingDetails
 * @package App\Components\Reports
 */
class CarrierBillingDetails extends AbstractReport
{
    /**
     * @var string
     */
    protected $storedProcedure = 'spACOE_RptCarrierBillingDetails';

    /**
     *
     */
    public function init()
    {
        $this->identify(true, self::CATEGORY_GENERAL, 'Carrier Billing Details', 106, '', false);
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
            'Carrier',
            'Billing URL',
            'Username',
            'Password',
            'Invoice Available Date',
            'Paperless',
            'PIN',
            'Notes'
        ];
    }

    /**
     * @return source
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