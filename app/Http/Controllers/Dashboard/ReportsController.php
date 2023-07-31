<?php

namespace App\Http\Controllers\Dashboard;

ini_set('memory_limit', '1024M');
ini_set('max_execution_time', 300);

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\ReportsService;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\Reports\DateRangeRequest;

/**
 * Class ReportsController
 * @package App\Http\Controllers\Dashboard
 */
class ReportsController extends Controller
{

    /**
     * The service instance.
     *
     * @var ReportsService
     */
    private $reports;

    /**
     * ReportsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth.admin')->except(['index', 'download', 'email']);
    }

    /**
     * Display the page with list of available reports
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, ReportsService $reportsService)
    {
        if ($request->report_search) {
            $reports = $reportsService->search($request->report_search);
            $ViewAll = true;
        } else {
            $reports = $reportsService->getMap();
            $ViewAll = false;
        }
        return view('dashboard.reports.index', [
            'reports'   =>  $reports,
            'ViewAll'   =>  $ViewAll,
        ]);
    }

    /**
     * Render requested report into XLS
     *
     * @param DateRangeRequest $request
     * @param $name
     */
    public function download(DateRangeRequest $request, $name, ReportsService $reportsService)
    {
        abort_if(!$reportsService->exists($name), 404, 'The report is not found');

        $report = $reportsService->get($name);

        if ($report->hasDateRange() && $request->has(['from', 'to'])) {

            $report->setRange(Carbon::parse($request->input('from')), Carbon::parse($request->input('to')));
        }

        $nameToExport = $report->getNameToExport();

        if ($report->isBigerThanMax()) {
            return Excel::download($report, $nameToExport . '.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        return Excel::download($report, $nameToExport . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * Email required report.
     *
     * @param DateRangeRequest $request
     * @param $name
     * @return \Illuminate\Http\RedirectResponse
     */
    public function email(DateRangeRequest $request, $name, ReportsService $reportsService)
    {
        abort_if(!$reportsService->exists($name), 404, 'The report is not found');

        $report = $reportsService->get($name);

        if ($report->hasDateRange() && $request->has(['from', 'to'])) {

            $report->setRange(Carbon::parse($request->input('from')), Carbon::parse($request->input('to')));
        }

        $emails = ($request->get('destination') == 'default')
            ? [auth()->user()->getAttribute('EmailAddress')]
            : $request->input(['Email'], []);

        $report->email($emails);

        return redirect()
            ->route('dashboard.reports.index');
    }

    /**
     * Add report to favorites.
     *
     * @param DateRangeRequest $request
     * @param $name
     * @return \Illuminate\Http\RedirectResponse
     */
    public function favorite(DateRangeRequest $request, $name, $reportID, ReportsService $reportsService)
    {
        abort_if(!$reportsService->exists($name), 404, 'The report is not found');
        $success = $request->user()->favoriteReports()->sync([$reportID], false);
        if ($success['attached']) {
            return redirect()
                ->route('dashboard.reports.index')
                ->with('notification.success', 'Report has been added to your favorites.');
        } else {
            return redirect()
                ->route('dashboard.reports.index')
                ->with('notification.success', 'Report was already added to your favorites.');
        }
    }
}
