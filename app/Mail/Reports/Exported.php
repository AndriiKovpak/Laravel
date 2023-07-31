<?php

namespace App\Mail\Reports;

use Illuminate\Support\Arr;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use League\Flysystem\Filesystem;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use App\Components\Reports\AbstractReport;
use League\Flysystem\Local\LocalFilesystemAdapter;

class Exported extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The instance of report to send.
     *
     * @var AbstractReport
     */
    private $report;

    /**
     * @param AbstractReport $report
     * Create a new message instance.
     */
    public function __construct(AbstractReport $report)
    {
        $this->report = $report;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $info = $this->report->info();
        $this->subject(config('app.name') . ' - ' . Arr::get($info, 'title'));

        if (count($this->report->data()) > $this->report->getMaxRows()) {
            $this->markdown('emails.reports.ftp', [
                'title' => Arr::get($info, 'title')
            ]);

            // Too large for excel/email.. generate CSV and upload to FTP site
            $filename = Arr::get($info, 'name') . '_' . time() . ".csv";


            // this is set to false in env
            if(env('FTP_LARGE_REPORTS')) {
                // $stream = fopen($filepath, 'r');
                // Flysystem::connection('Reports')->writeStream($filename, $stream);

                // if (is_resource($stream)) {
                //     fclose($stream);
                // }
            }
            else
            {
                $this->markdown('emails.reports.send', [
                    'title' => Arr::get($info, 'title')
                ]);
                $csvFile = Excel::raw($this->report, \Maatwebsite\Excel\Excel::CSV);
                $this->attachData($csvFile, $filename, [
                    'mime' => 'application/vnd.ms-excel',
                ]);
            }

        } else {
            $this->markdown('emails.reports.send', [
                'title' => Arr::get($info, 'title')
            ]);

            $excelFile = Excel::raw($this->report, \Maatwebsite\Excel\Excel::XLSX);
            $this->attachData($excelFile, Arr::get($info, 'name') . $this->report->getDateRangeForFilename() . '_' . time() . '.xlsx', [
                'mime' => 'application/vnd.ms-excel',
            ]);
        }
        return $this;
    }
}
