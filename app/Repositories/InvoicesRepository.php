<?php

namespace App\Repositories;

use App\Components\Repositories\RepositoryContract;
use App\Models\BTNAccount;
use App\Models\Circuit;

use App\Models\Util;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class InvoicesRepository
 * @package App\Repositories
 */
class InvoicesRepository implements RepositoryContract
{
    /**
     * @param $filters
     * @param $page
     * @param $options
     * @return mixed
     */
    public function paginate($filters, $page, $options)
    {
        // TODO It's kind of confusing that InvoicesRepository returns a bunch of columns from other tables
        $invoices = BTNAccount::select([
            'BTNAccounts.BTNAccountID',
            'DivisionDistricts.DivisionDistrictName',
            'Carriers.CarrierName',
            'BTNAccounts.BTN',
            'BTNAccounts.AccountNum',
            'BTNAccounts.Status',
            'ScannedImages.BillDate',
            'ProcessedTypes.ProcessedTypeName',
            'ProcessCodes.ProcessCodeName',
            'ProcessedMethodTypes.ProcessedMethodName',
            'ProcessedMethodTypes.ProcessedMethod',
            'InvoicesAccountsPayable.InvoiceAPID',
            'InvoicesAccountsPayable.Note',
            'ScannedImages.ScannedImageID',
            'ScannedImages.BatchDate',
        ])
            ->leftJoin('BTNStatusTypes', 'BTNAccounts.Status', '=', 'BTNStatusTypes.BTNStatus')
            ->where('BTNStatusTypes.IsDisplay', '<>', '0')
            ->where(function ($query) {
                $query->where('BTNAccounts.Status', '=', 1)
                    ->orWhere('BTNAccounts.Updated_at', '>', DB::raw('DATEADD(year, -2, getdate())'));

                if (time() < 1573430400) { // Include empty Updated_at until 11/11/2019 (two years after release)
                    $query->orWhereNull('BTNAccounts.Updated_at');
                }
            })
            ->leftJoin('DivisionDistricts', 'BTNAccounts.DivisionDistrictID', '=', 'DivisionDistricts.DivisionDistrictID')
            ->leftJoin('Carriers', 'BTNAccounts.CarrierID', '=', 'Carriers.CarrierID')
            ->join('ScannedImages', 'BTNAccounts.BTNAccountID', '=', 'ScannedImages.BTNAccountID')
            ->leftJoin('ProcessedTypes', 'ScannedImages.ProcessedType', '=', 'ProcessedTypes.ProcessedType')
            ->leftJoin('ProcessCodes', 'ScannedImages.ProcessCode', '=', 'ProcessCodes.ProcessCode')
            ->join('InvoicesAccountsPayable', 'InvoicesAccountsPayable.ScannedImageID', '=', 'ScannedImages.ScannedImageID')
            ->leftJoin('ProcessedMethodTypes', 'InvoicesAccountsPayable.ProcessedMethod', '=', 'ProcessedMethodTypes.ProcessedMethod')
            ->where('ProcessedMethodTypes.IsActive', '=', 1);

        if (Auth::user()->cant('edit')) {
            $invoices->join('Users_DivisionDistricts', 'DivisionDistricts.DivisionDistrictID', '=', 'Users_DivisionDistricts.DivisionDistrictID')
                ->join('Users', 'Users_DivisionDistricts.UserID', '=', 'Users.UserID')
                ->where('Users.UserID', Auth::id());
        }

        if (!empty($filters['search']) && $filters['search'] == 'show') {
            $searchText = $filters['searchText'];
            $searchTextClean = DB::selectOne('select dbo.fnCleanString(?) as CleanString', [$searchText])->CleanString;
            $searchText = '%' . $searchText . '%';
            $searchTextClean = '%' . $searchTextClean . '%';

            $invoices->where(function ($query) use ($searchTextClean, $searchText) {
                $query->whereRaw('dbo.fnCleanString(BTNAccounts.AccountNum) LIKE ?', [$searchTextClean])
                    ->orWhereRaw('dbo.fnCleanString(BTNAccounts.BTN) LIKE ?', [$searchTextClean])
                    ->orWhereRaw('DivisionDistricts.DivisionDistrictName LIKE ?', [$searchText])
                    ->orWhereRaw('Carriers.CarrierName LIKE ?', [$searchText]);
            });
        }

        if (!empty($filters['filter']) && $filters['filter'] == 'show') {
            if (!empty($filters['ProcessedMethod'])) {
                $invoices->where('InvoicesAccountsPayable.ProcessedMethod', $filters['ProcessedMethod']);
            }

            if (!empty($filters['DivisionDistrictID'])) {
                $invoices->where('BTNAccounts.DivisionDistrictID', $filters['DivisionDistrictID']);
            }

            if (!empty($filters['CarrierID'])) {
                $invoices->where('BTNAccounts.CarrierID', $filters['CarrierID']);
            }

            if (!empty($filters['FiscalYearID'])) {
                $invoices->where('ScannedImages.FiscalYearID', $filters['FiscalYearID']);
            }

            if (!empty($filters['ProcessCode'])) {
                $invoices->where('ScannedImages.ProcessCode', '=', $filters['ProcessCode']);
            }
            if ((empty($filters['datecheck']) || $filters['datecheck'] != '1') && !empty($filters['from_date']) && !empty($filters['to_date'])) {
                $invoices->where('ScannedImages.BillDate', '>=', Carbon::parse($filters['from_date'])->format('Y-m-d'));
                $invoices->where('ScannedImages.BillDate', '<=', Carbon::parse($filters['to_date'])->format('Y-m-d'));
            }
            if ((empty($filters['batchcheck']) || $filters['batchcheck'] != '1') && !empty($filters['batch_date'])) {
                $invoices->where('ScannedImages.BatchDate', '=', Carbon::parse($filters['batch_date'])->format('Y-m-d'));
            }

            switch ($filters['sortColumn']) {
                /*
                case 'DivisionDistrictID':
                    $invoices->orderBy('DivisionDistricts.DivisionDistrictName', $filters['sortDirection']);
                    break;
                */
                case 'CarrierID':
                    $invoices->orderBy('Carriers.CarrierName', $filters['sortDirection']);
                    break;
                case 'BTN':
                    $invoices->orderBy('BTNAccounts.BTN', $filters['sortDirection']);
                    break;
                case 'AccountNum':
                    $invoices->orderBy('BTNAccounts.AccountNum', $filters['sortDirection']);
                    break;
                case 'BillDate':
                    $invoices->orderBy('ScannedImages.BillDate', $filters['sortDirection']);
                    break;
                case 'BatchDate':
                    $invoices->orderBy('ScannedImages.BatchDate', $filters['sortDirection']);
                    break;
                case 'default':
                    $invoices->orderBy('ScannedImages.BillDate', $filters['sortDirection']);
                    break;
            }
        } else {
            // This default matches the default state of the form (right now)
            $invoices->orderBy('ScannedImages.BillDate', 'desc');
        }

        $invoices = $invoices->latest('InvoicesAccountsPayable.Created_at')->paginate(12, null, 'page', $page);
        return $invoices;
    }
}