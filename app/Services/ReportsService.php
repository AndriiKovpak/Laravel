<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use App\Components\Reports\AbstractReport;

/**
 * Class ReportsService
 * @package App\Services
 */
class ReportsService
{
    /**
     * The final map to render of reports page.
     *
     * @var
     */
    protected $map;

    /**
     * The list of available reports.
     *
     * @var array
     */
    protected $reports;


    /**
     * ReportsService constructor.
     */
    public function __construct()
    {
        // This service is also used with artisan commands, where there is no user.
        // If it is used the wrong way, it could expose reports to all users.
        // An example is when the service is assigned to a variable in a controller constructor
        // instead of injected as an parameter in a controller action.
        if (Auth::user() && Auth::user()->cant('edit')) {
            $this->reports = [
                //GENERAL REPORTS
                \App\Components\Reports\CarriersWithContactNumber::class,
                \App\Components\Reports\APInvoiceEntryDates::class,
                \App\Components\Reports\AllBTNsAccountNumbers::class,
                //INVENTORY REPORTS
                \App\Components\Reports\DataInventory::class,
                \App\Components\Reports\VoiceInventory::class,
                \App\Components\Reports\SatelliteInventory::class,
            ];
        } else {
            $this->reports = [
                //GENERAL REPORTS
                \App\Components\Reports\CarriersWithContactNumber::class,
                \App\Components\Reports\AllBTNsAccountNumbers::class,
                \App\Components\Reports\ActiveBTNAccountNumbers::class,
                \App\Components\Reports\ActiveAccountsNoAP::class,
                \App\Components\Reports\ActiveAccounts::class,
                \App\Components\Reports\APInvoiceEntryDates::class,
                \App\Components\Reports\APInvoiceDelta::class,
                \App\Components\Reports\LastAccountsPayable::class,
                \App\Components\Reports\LastAccountsPayableFor3Months::class,
                \App\Components\Reports\MissingInvoices::class,
                \App\Components\Reports\LoginHistory::class,
                \App\Components\Reports\CircuitMAC::class,
                \App\Components\Reports\CMDBInventory::class,
                \App\Components\Reports\CMDBInventoryReportLastReportRun::class,
                \App\Components\Reports\CMDBAllRecords::class,
                \App\Components\Reports\ReceivingReport::class,
                \App\Components\Reports\OnlineBillingReport::class,
                \App\Components\Reports\CarrierBillingDetails::class,
                \App\Components\Reports\FinanceInvoices::class,
                \App\Components\Reports\CMDBInventoryReportAllOld::class,
                //INVENTORY REPORTS
                \App\Components\Reports\FullInventory::class,
                \App\Components\Reports\DataInventory::class,
                \App\Components\Reports\SatelliteInventory::class,
                \App\Components\Reports\VoiceInventory::class,
                \App\Components\Reports\VoiceInventoryWithDIDPartA::class,
                \App\Components\Reports\VoiceInventoryWithDIDPartB::class,
            ];
        }

        $this->createMap();
    }

    /**
     * Create base array of categories
     * of reports.
     *
     * @return array
     */
    private function registerCategories()
    {
        return [
            AbstractReport::CATEGORY_GENERAL    =>  [
                'title'     =>  'General Reports',
                'reports'   =>  []
            ],
            AbstractReport::CATEGORY_INVENTORY  =>  [
                'title'     =>  'Inventory Reports',
                'reports'   =>  []
            ]
        ];
    }

    /**
     *  Create a map of available & valid reports
     */
    private function createMap()
    {
        $this->map = $this->registerCategories();

        foreach ($this->reports as $reportClassName) {

            $report = new $reportClassName;

            if (($report->isValid())) {

                $info = $report->info();

                $this->map[$report->getCategory()]['reports'][Arr::get($info, 'name')] = array_merge(
                    $info,
                    ['class' => $reportClassName]
                );
            }
        }
    }

    /**
     * Check if desired report exists
     *
     * @param $name
     * @return bool
     */
    public function exists($name)
    {

        foreach ($this->map as $category) {

            if (is_numeric($name)) {

                foreach ($category['reports'] as $report) {
                    if ($report['reportID'] == $name) {
                        return true;
                    }
                }
            } else if (isset($category['reports'][$name])) {

                return true;
            }
        }

        return false;
    }

    /**
     * Get instance of report by name.
     * Before use this method, please check for existence
     * of required report.
     *
     * @param $name
     * @return AbstractReport|null
     */
    public function get($name)
    {
        foreach ($this->map as $category) {
            if (is_numeric($name)) {
                foreach ($category['reports'] as $report) {
                    if ($report['reportID'] == $name) {
                        return new $report['class'];
                    }
                }
            } else if (isset($category['reports'][$name])) {

                return new $category['reports'][$name]['class'];
            }
        }

        return null;
    }

    /**
     * Return the reports map.
     *
     * @return array
     */
    public function getMap()
    {
        return $this->map;
    }

    public function search($search)
    {
        $reports = [];
        foreach ($this->map as $category) {
            foreach ($category['reports'] as $report) {
                if (strpos(strtolower($report['title']), strtolower($search)) !== false) {
                    array_push($reports, $report['class']);
                } else if (intval($report['reportID']) == intval($search)) {
                    array_push($reports, $report['class']);
                }
            }
        }

        $searchMap = $this->registerCategories();

        foreach ($reports as $reportClassName) {

            $report = new $reportClassName;

            if (($report->isValid())) {

                $info = $report->info();

                $searchMap[$report->getCategory()]['reports'][Arr::get($info, 'name')] = array_merge(
                    $info,
                    ['class' => $reportClassName]
                );
            }
        }
        return $searchMap;
    }
}
