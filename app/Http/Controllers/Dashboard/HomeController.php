<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Carrier;
use App\Models\BTNAccount;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Services\ReportsService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * Class HomeController
 * @package App\Http\Controllers\Dashboard
 */
class HomeController extends Controller
{
    /**
     * Display the home page of dashboard
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, ReportsService $reportsService)
    {
        if ($request->field) {
            return redirect()
                ->route('dashboard.inventory.index', ['search' => $request->search, 'field' => $request->field]);
        }

        $favoriteReports = $request->user()->favoriteReports()->orderBy('SortOrder', 'ASC')->get();
        $reports = [];
        foreach ($favoriteReports as $favoriteReport) {
            $report = $reportsService->get($favoriteReport->ReportID);
            if ($report) {
                $info = $report->info();
                $reports[Arr::get($info, 'name')] = array_merge(
                    $info,
                    ['class' => get_class($report)]
                );
            }
        }
        // dd($reports);

        return view('dashboard.home.index', [
            'favoriteReports'   => collect($reports),
            'TotalDisconnect'   => BTNAccount::getDisconnectsThisMonth(),
            'TotalExpire'       => BTNAccount::getExpirationsThisMonth(),
            'Reports'           => [
                'AccountsPayable'   =>      json_decode(json_encode(DB::select('EXEC spACOE_LastAPReport_Dashboard @userID = ?', [Auth::id()])), true),
                'Credits'           =>      json_decode(json_encode(DB::select('EXEC spACOE_RptInvoicesAPCredits_Dashboard @userID = ?', [Auth::id()])), true),
                'Invoice'           =>      json_decode(json_encode(DB::select('EXEC spACOE_RptInvoicesAPEntryDate_Dashboard @userID = ?', [Auth::id()])), true),
                'NewAccounts'       =>      json_decode(json_encode(DB::select('EXEC spACOE_RptNewAccounts_Dashboard')), true),
            ],
            '_options'  => [
                'CarrierID' => Carrier::getOptionsForSelect(),
                'field'     => [
                    'AccountNum'    => 'Account #\'s',
                    'BTN'           => 'BTN #\'s',
                    'CircuitID'     => 'Circuit ID #\'s'
                ]
            ]
        ]);
    }
}
