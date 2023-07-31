<?php

namespace App\Components\Reports;

use Carbon\Carbon;
use App\Models\Util;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Mail\Reports\Exported as ReportExportedEmail;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;

abstract class AbstractReport extends StringValueBinder implements
    FromCollection,
    WithTitle,
    ShouldAutoSize,
    WithStyles,
    WithCustomValueBinder
{
    const CATEGORY_GENERAL = 1;
    const CATEGORY_INVENTORY = 2;
    const CATEGORY_DO_NOT_RUN = 3;

    protected $name;
    protected $category;

    protected $reportID;

    /*
     * Turn off ability to email report in UI
     */
    protected $canEmail;

    protected $title;
    protected $description;
    protected $CMDBAllRecords;
    protected $maxRows = 5000;

    /**
     * The array of report's columns.
     *
     * @var array
     */
    private $columns;

    /**
     * The array of data.
     *
     * This is really big, so I don't know if storing it will cause memory problems.
     * However, storing it will make it so it does not have to be retrieved multiple times if data() is called multiple times.
     *
     * @var array
     */
    private $data;

    /**
     * If the value is type of ARRAY - a report has date range,
     * otherwise if the value is NULL - a report does not have date range.
     *
     * @var array|null
     */
    protected $dateRange;

    /**
     * AbstractReport constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Initialize a report.
     *
     * @return void
     */
    abstract protected function init();

    /**
     * Return the column names for if the report is empty.
     *
     * There does not appear to be a consistent way to retrieve the column names from a stored procedure when the result set is empty.
     *
     * sp_describe_first_result_set only works on about half of the reports.
     *
     * @return \Illuminate\Database\Query\Builder|
     */
    abstract protected function fallbackColumns();

    /**
     * Return the data source for a report.
     *
     * @return \Illuminate\Database\Query\Builder|
     */
    abstract protected function source();

    /**
     * Specify the date range (from -> to);
     *
     * @param Carbon|null $from
     * @param Carbon|null $to
     * @throws \Exception
     * @return $this
     */
    public function setRange(Carbon $from = null, Carbon $to = null)
    {
        if (is_null($this->dateRange)) {

            throw new \Exception(sprintf('The "%s" report does not support date range', $this->name));
        }

        if (is_a($from, Carbon::class) && is_a($from, Carbon::class)) {

            $this->dateRange = [
                'from' => $from,
                'to' => $to
            ];
        }

        return $this;
    }

    /**
     * Identify single report. Give name for it.
     *
     * @param $hasDateRange
     * @param $category
     * @param $title
     * @param string $description
     */
    protected function identify($hasDateRange, $category, $title, $reportID, $description = '', $canEmail = true)
    {
        $this->name = Str::slug($title, '_'); // Should be unique within reports
        $this->category = $category;

        $this->reportID = $reportID;

        $this->canEmail = $canEmail;

        $this->title = $title;
        $this->description = $description;

        $this->dateRange = $hasDateRange ? [] : null;
    }

    /**
     * Get category identifier
     *
     * @return integer|null
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set array of columns for a report.
     *
     * @param array $columns
     * @return $this
     */
    public function setColumns(array $columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Check if a report is valid.
     * Only valid reports could be rendered / exported.
     *
     * @return bool
     */
    public function isValid()
    {
        return (in_array($this->category, array_keys(self::getAvailableCategories())) && !is_null($this->name));
    }

    /**
     * Check if a report has date range.
     *
     * @return bool
     */
    public function hasDateRange()
    {
        return (is_array($this->dateRange));
    }

    /**
     * Check if a report has valid date range.
     *
     * @return bool
     */
    public function hasValidDateRange()
    {
        return ($this->hasDateRange() && isset($this->dateRange['from']) && isset($this->dateRange['to']));
    }

    public function getDateRangeForFilename()
    {
        if ($this->hasValidDateRange()) {
            return '_' . $this->dateRange['from']->format('Y-m-d') . '_to_' . $this->dateRange['to']->format('Y-m-d');
        }

        return '';
    }

    public function getDateTimeForFilename()
    {
        $now = Carbon::now();
        return $now->format('Y-m-d_Hi') . Util::getTimezoneACP121I($now);
    }

    /**
     * Return basic info about a report.
     *
     * @return array
     */
    public function info()
    {
        return [
            'name'              => $this->name,
            'title'             => $this->title,
            'has_date_range'    => $this->hasDateRange(),
            'reportID'          => $this->reportID,
            'canEmail'          => $this->canEmail,
        ];
    }

    /**
     * Return array of columns.
     *
     * This function loads the column names if they have not been loaded, and returns them.
     *
     * @return array
     */
    public function columns()
    {
        if (empty($this->columns)) {
            if (count($this->data()) == 1 && count($this->data()[0]) == 1 && reset($this->data()[0]) == 'No results') { // If there was no data
                $this->columns = $this->fallbackColumns(); // Unfortunately, we can't get the column names from the database without any data.
            } else {
                $this->columns = array_keys($this->data()[0]);
            }
        }
        return $this->columns;
    }

    /**
     * Return array of data.
     *
     * This function loads the data if it has not been loaded, and returns it.
     *
     * @return array
     */
    public function data()
    {
        if (empty($this->data)) {
            $this->data = [];
            foreach ($this->source() as $row) {
                $this->data[] = (array)$row; // Convert each row from object to array
            }
            if (empty($this->data)) {
                $this->data[] = ['No results'];
            }
        }
        return $this->data;
    }

    /**
     * Generate XLS document.
     *
     * @return LaravelExcelWriter
     */
    public function generate()
    {
        $info = $this->info();
        $rows = array_merge([$this->columns()], $this->data());

        /*
        //      NOTE: Enable this code when the system will be running with queues
                $document = app('excel')->create($this->name . '_' . time());
                $document->setTitle($info['title']);

                $sheet = $document->sheet($info['title'])->getSheet();

                $sheet->fromArray($rows, null, 'A1', false, false);
                $sheet->freezeFirstRow();

                (new CellWriter('A1:' . $sheet->getHighestColumn() . '1', $sheet))->setFontWeight('bold');

                return $document;
             */

        /*
        * Not used bc it works on v2.x, but now use v3.1
        return app('excel')->create($this->name . $this->getDateRangeForFilename() . '_' . $this->getDateTimeForFilename(), function (LaravelExcelWriter $excel) use ($info, $rows) {

            $excel->setTitle(substr($info['title'], 0, 31));

            $excel->sheet(substr($info['title'], 0, 31), function (LaravelExcelWorksheet $sheet) use ($rows) {

                $sheet->fromArray($rows, null, 'A1', false, false);

                $sheet->freezeFirstRow();
                $sheet->row(1, function (CellWriter $writer) {

                    $writer->setFontWeight('bold');
                });
            });
        });
        */
    }

    /** Not used on v3 */
    public function generateCSV($filepath = null)
    {

        setcookie("downloadStarted", 1, time() + 20, '/', "", false, false);
        $rows = $this->data();
        //$rows = array_slice($rows, 0, 1000);

        if (!isset($filepath)) {
            // output headers so that the file is downloaded
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=' . $this->name . $this->getDateRangeForFilename() . '_' . $this->getDateTimeForFilename() . '.csv');
        }

        // create a file pointer connected to the output stream
        if (isset($filepath))
            $report = fopen($filepath, 'w');
        else
            $report = fopen('php://output', 'w');

        if (!$report) {
            print_r(error_get_last());
            throw new \Exception('Error generating report, unable to open file/stream for writing.');
        }

        // column headings
        fputcsv($report, $this->columns());

        // loop over rows from report
        foreach ($rows as $row) {
            $array = [];
            foreach ($row as $key => $value) {
                array_push($array, $value);
            }
            fputcsv($report, $array);
        }

        if (isset($filepath))
            fclose($report);
        else
            exit;
    }

    /**
     * Download a report as .xls file
     * Not used on v3
     * @return void
     */
    public function download()
    {
        //For really big reports, simply use csv instead. This greatly boosts performance.
        if (count($this->data()) > $this->maxRows) {

            /////////////////// will not use ////////////////////
            setcookie("downloadStarted", 1, time() + 20, '/', "", false, false);
            $this->generateCSV();
            /////////////////// will not use ////////////////////

        } else {
            $generated = $this->generate();
            //This is for the "building report" screen
            setcookie("downloadStarted", 1, time() + 20, '/', "", false, false);
            $generated->export();
        }
    }

    /**
     * Email a report for array of emails.
     *
     * @param $emails
     */
    public function email($emails)
    {
        app('mailer')->to($emails)->send(new ReportExportedEmail($this));
    }

    /**
     * Protect real data (values) if a report is getting
     * not from production.
     *
     * @return bool
     */
    private function protectData()
    {
        return (app()->environment() != 'production');
    }

    /**
     * Return array of available categories
     *
     * @return array
     */
    public static function getAvailableCategories()
    {
        return [
            self::CATEGORY_GENERAL => 'general',
            self::CATEGORY_INVENTORY => 'inventory',
            self::CATEGORY_DO_NOT_RUN => 'do_not_run'
        ];
    }

    private function getCMDBAllRecordsReport()
    {
        return $this->CMDBAllRecords;
    }

    public function getMaxRows()
    {
        return $this->maxRows;
    }


    /**
     * will download data using laravel-excel v3.1
     * @return Collection
     */
    public function collection()
    {
        $rows = array_merge([$this->columns()], $this->data());
        setcookie("downloadStarted", 1, time() + 20, '/', "", false, false);
        return collect($rows);
    }

    public function getNameToExport()
    {
        return $this->name . $this->getDateRangeForFilename() . '_' . $this->getDateTimeForFilename();
    }

    /**
     * compare rows is bigger than max size in set
     */
    public function isBigerThanMax()
    {
        return count($this->data()) > $this->maxRows;
    }

    /**
     * specify the sheet name
     * @return string
     */
    public function title(): string
    {
        return ucwords(implode(' ', explode('_', $this->name)));
    }

    /**
     * specifed row or cell's styling
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            if ($this->isfloat($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
                return true;
            }
        }
        return parent::bindValue($cell, $value);
    }

    function isfloat($value)
    {
        // PHP automagically tries to coerce $value to a number
        return is_float($value + 0);

        // isfloat("5.0" + 0);  // true
        // isfloat("5.0");  // false
        // isfloat(5 + 0);  // false
        // isfloat(5.0 + 0);  // false
        // isfloat('a' + 0);  // false
    }
}
