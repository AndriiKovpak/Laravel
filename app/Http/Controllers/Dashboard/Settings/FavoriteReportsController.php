<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;


class FavoriteReportsController extends Controller
{
    public function __construct() {
        $this->middleware('auth.admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('dashboard.settings.favorite-reports.index', [
            'favoriteReports' => $request->user()->favoriteReports()->orderBy('SortOrder', 'ASC')->get()
        ]);
    }

    /**
     * Remove Favorite report.
     *
     * @param  Report $report
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Report $report, Request $request)
    {
        $request->user()->favoriteReports()->detach($report);

        return redirect()->back()
            ->with('notification.success', 'Successfully removed Favorite Report.');
    }

    /**
     * Order Favorite report.
     *
     * @param  Request $request
     * @return array
     */
    public function order(Request $request)
    {
        foreach ($request->orderList as $row) {
            $request->user()->favoriteReports()->updateExistingPivot($row['id'], ['SortOrder' => $row['order']]);
        }

        return ['success' => true];
    }

}
