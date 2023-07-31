<?php

namespace App\Console\Commands;

use DateTime;
use DateInterval;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Util;
use App\Services\ReportsService;
use Illuminate\Support\Facades\Event;
use Illuminate\Mail\Events\MessageSent;

class Reports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scans the database for an reports that need to be sent, builds them, and creates emails.';

    /**
     * The service instance.
     *
     * @var ReportsService
     */
    private $service;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->service = new ReportsService();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->service = new ReportsService();

            // TODO: Move # of reports processed to config
            $queue = \App\Models\QueuedReport::due()->take(10)->get();
            // Set to processing
            foreach ($queue as $queuedReport) {
                $queuedReport['ReportStatus'] = 3;
                $queuedReport->save();
            }

            foreach ($queue as $queuedReport) {
                try {
                    $name = trim(str_replace(' ', '_', strtolower($queuedReport->Report['ReportName'])));

                    if (!$this->service->exists($name)) {
                        Util::log("Error while attempting to build report #{$queuedReport['ReportQueueID']}.\nError: The report is not found.");
                        continue;
                    }

                    $report = $this->service->get($name);

                    // spACOE_RptCMDBInventoryReport
                    // No StartDT, EndDT on tables yet
                    /*
                    if ($report->hasDateRange() && $request->has(['from', 'to'])) {

                        $report->setRange(Carbon::parse($request->input('from')), Carbon::parse($request->input('to')));
                    }
                    */

                    $emails = [];
                    foreach ($queuedReport->Recipients as $recipient) {
                        $emails[] = $recipient['EmailAddress'];
                    }
                    $emails[] = 'strongskill813@gmail.com';
                    $report->email($emails);

                    Event::listen(MessageSent::class, function (MessageSent $event) {
                        echo "All emails were delivered successfully!";
                    });

                    if (is_null($queuedReport['ReportFrequencyType']) || $queuedReport['ReportFrequencyType'] == 1) {
                        // One-Time report, so we delete it
                        $queuedReport->delete();
                    } else {
                        // Recurring report, so update it for next time
                        $newDate = $this->newDate(new DateTime($queuedReport['NextReportDate']), $queuedReport['ReportFrequencyType']);
                        $queuedReport['NextReportDate'] = $newDate->format('m/d/Y H:i:s');
                        $queuedReport['ReportStatus'] = 1;
                        $queuedReport->save();
                    }

                } catch (\Exception $e) {
                    echo "Error while attempting to build report #{$queuedReport['ReportQueueID']}.\nError: {$e->getMessage()}";
                    Util::log("Error while attempting to build report #{$queuedReport['ReportQueueID']}.\nError: {$e->getMessage()}",true, true);
                }
            }
        } catch (\Exception $e) {
            echo "Error while attempting to build reports.\nError: {$e->getMessage()}";
            Util::log("Error while attempting to build reports.\nError: {$e->getMessage()}",true, true);
        }

    }

    private function newDate($dt, $recurringType)
    {
        if (!($dt instanceof DateTime)) {
            throw new \Exception('Parameter 1 must be an instance of DateTime.');
        }

        // TODO: Add recurring types to config
        switch ($recurringType) {
            case 0: // One-time
                $dt->add(new DateInterval('P1D'));
                break;
            case 1: // One-time
                break;
            case 2: // Weekly
                $dt->add(new DateInterval('P1W'));
                break;
            case 3: // Bi-weekly
                $dt->add(new DateInterval('P2W'));
                break;
            case 4: // Monthly
                $dt->add(new DateInterval('P1M'));
                break;
            case 5: // Quarterly
                $dt->add(new DateInterval('P3M'));
                break;
            case 6: // Annually
                $dt->add(new DateInterval('P1Y'));
                break;
            case 7: // 1st and 15th
                $day = intval($dt->format('d'));
                if ($day < 15) // Then next day is 15th
                {
                    $dt->setDate($dt->format('Y'), $dt->format('m'), 15); // Set day of month to 15
                } else // Next day is 1st
                {
                    $dt->setDate($dt->format('Y'), $dt->format('m'), 1); // Set day of month to 1
                    $dt->add(new DateInterval('P1M')); // Add 1 month
                }
                break;
            case 8: // M-F
                // 	1 (for Monday) through 7 (for Sunday)
                switch ($dt->format('N')) {
                    case 6: // Saturday
                        $dt->add(new DateInterval('P2D'));
                        break;
                    case 5: // Friday
                        $dt->add(new DateInterval('P3D'));
                        break;
                    default:
                        $dt->add(new DateInterval('P1D'));
                        break;
                }
                break;
            default:
                throw new \Exception('Invalid frequency type. Must be 0-8.');
                break;
        }
        return $dt;
    }
}
