<?php

namespace App\Repositories;

use App\Models\BTNAccount;
use App\Components\Repositories\RepositoryContract;
use App\Models\Util;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class BTNAccountsRepository
 * @package App\Repositories
 */
class BTNAccountsRepository implements RepositoryContract
{
    /**
     * @param $filters
     * @param $page
     * @param $options
     * @return mixed
     */
    public function paginate($filters, $page, $options)
    {
        // Reduce the number of queries by selecting these all at once instead of as needed
        $with = ['BTNStatusType', 'DivisionDistrict', 'Carrier', 'SiteAddress'];

        $query = BTNAccount::with($with)
            ->leftJoin('BTNStatusTypes', 'BTNAccounts.Status', '=', 'BTNStatusTypes.BTNStatus')
            ->where('BTNStatusTypes.IsDisplay', '<>', '0')
            ->where(function ($query) {
                $query->where('BTNAccounts.Status', '=', 1)
                    ->orWhere('BTNAccounts.Updated_at', '>', DB::raw('DATEADD(year, -2, getdate())'));

                if (time() < 1573430400) { // Include empty Updated_at until 11/11/2019 (two years after release)
                    $query->orWhereNull('BTNAccounts.Updated_at');
                }
            });

            if (Auth::user()->cant('edit')) {
                $userId = Auth::id();
                $query->leftJoin('Circuits', 'Circuits.BTNAccountID', '=', 'BTNAccounts.BTNAccountID')
                ->leftJoin('DivisionDistricts', function ($join) {
                    $join->on('Circuits.DivisionDistrictID', '=', 'DivisionDistricts.DivisionDistrictID')
                        ->orWhereColumn('BTNAccounts.DivisionDistrictID', 'DivisionDistricts.DivisionDistrictID');
                })
                ->leftJoin('Users_DivisionDistricts', function ($join) use ($userId) {
                    $join->on('Users_DivisionDistricts.UserID', '=', DB::raw($userId))
                        ->whereColumn('DivisionDistricts.DivisionDistrictID', 'Users_DivisionDistricts.DivisionDistrictID');
                })
                ->leftJoin('Users', function ($join) use ($userId) {
                    $join->on('Users.UserID', '=', DB::raw($userId))
                        ->where('Users.SecurityGroup', '=', 1);
                })
                ->join('DivisionDistricts AS cdd', 'Circuits.DivisionDistrictID', '=', 'cdd.DivisionDistrictID')
                ->where(function ($query) {
                    $query->whereNotNull('Users_DivisionDistricts.DivisionDistrictID')
                        ->orWhereNotNull('Users.UserID');
                });
            }

        // Search disconnects or contract expirations
        if (isset($filters['type'])) {

            // circuitSearch would mean we can do an inner join instead of left join
            if (isset($filters['circuitSearch'])) {
                $query->join('Circuits', 'BTNAccounts.BTNAccountID', '=', 'Circuits.BTNAccountID');
            } else {
                $query->leftJoin('Circuits', 'BTNAccounts.BTNAccountID', '=', 'Circuits.BTNAccountID');
            }

            $query->leftJoin('BTNAccountMACs', 'BTNAccounts.BTNAccountID', '=', 'BTNAccountMACs.BTNAccountID')
                ->leftJoin('CircuitMACs', 'Circuits.CircuitID', '=', 'CircuitMACs.CircuitID');

            if ($filters['type'] == 'disconnect') {

                if (date('j') <= 25) {
                    $disconnectRange = [
                        Carbon::now()->startOfMonth()->subMonth()->day(26), // 00:00:00 on 26th of last month
                        Carbon::now()->endOfMonth()->day(25), // 23:59:59 on 25th of this month
                    ];
                } else {
                    $disconnectRange = [
                        Carbon::now()->startOfMonth()->day(26), // 00:00:00 on 26th of this month
                        Carbon::now()->addMonth()->endOfMonth()->day(25), // 23:59:59 on 25th of next month
                    ];
                }

                $query->where(function ($query) use ($disconnectRange) {
                    $query->whereBetween('BTNAccountMACs.DisconnectDate', $disconnectRange)
                        ->orWhereBetween('CircuitMACs.DisconnectDate', $disconnectRange)
                        ->orWhereBetween('BTNAccounts.DisconnectDate', $disconnectRange);
                });
            } else if ($filters['type'] == 'expiration') {

                $expireRange = [
                    Carbon::now()->startOfMonth(), // 00:00:00 on first of this month
                    Carbon::now()->endOfMonth(), // 23:59:59 on last of this month
                ];

                $query->where(function ($query) use ($expireRange) {
                    $query->whereBetween('BTNAccountMACs.ContractExpDate', $expireRange)
                        ->orWhereBetween('CircuitMACs.ContractExpDate', $expireRange);
                });
            }
        }

        // First search box
        if (isset($filters['accountSearch'])) {
            // It would be faster to clean in PHP, but using SQL makes sure it is cleaned consistently.
            $accountSearch = DB::selectOne('select dbo.fnCleanString(?) as CleanString', [$filters['accountSearch']])->CleanString;
            $accountSearch = '%' . $accountSearch . '%';

            $query->where(function ($query) use ($accountSearch) {
                $query->whereRaw('dbo.fnCleanString(BTNAccounts.AccountNum) LIKE ?', [$accountSearch])
                    ->orWhereRaw('dbo.fnCleanString(BTNAccounts.BTN) LIKE ?', [$accountSearch])
                    ->orWhereRaw('BTNAccounts.BTNAccountID IN (SELECT Circuits.BTNAccountID FROM Circuits WHERE Circuits.BillUnderBTNSearch LIKE ?)', [$accountSearch]);
            });
        }

        // Second search box
        if (isset($filters['circuitSearch'])) {
            // It would be faster to clean in PHP, but using SQL makes sure it is cleaned consistently.
            $circuitSearch = DB::selectOne('select dbo.fnCleanString(?) as CleanString', [$filters['circuitSearch']])->CleanString;
            $circuitSearch = '%' . $circuitSearch . '%';

            // The join would have been done already if type is set.
            if (!isset($filters['type']) && !Auth::user()->cant('edit')) {
                $query->join('Circuits', 'BTNAccounts.BTNAccountID', '=', 'Circuits.BTNAccountID');
            }

            $query->leftJoin('CircuitsVoice', 'Circuits.CircuitID', '=', 'CircuitsVoice.CircuitID')
                ->leftJoin('CircuitsData', 'Circuits.CircuitID', '=', 'CircuitsData.CircuitID')
                //->leftJoin('CircuitsSatellite', 'Circuits.CircuitID', '=', 'CircuitsSatellite.CircuitID') // We're not actually searching any CircuitsSatellite columns... Yet.
                ->leftJoin('CircuitDIDs', 'Circuits.CircuitID', '=', 'CircuitDIDs.CircuitID')
                ->where(function ($query) use ($circuitSearch) {
                    $query->whereRaw('Circuits.CarrierCircuitIDSearch LIKE ?', [$circuitSearch])
                        ->orwhereRaw('Circuits.BillUnderBTNSearch LIKE ?', [$circuitSearch])
                        ->orWhereRaw('CircuitsVoice.SPID_Phone1Search LIKE ?', [$circuitSearch])
                        ->orWhereRaw('CircuitsVoice.SPID_Phone2Search LIKE ?', [$circuitSearch])
                        ->orWhereRaw('CircuitsVoice.ILEC_ID1Search LIKE ?', [$circuitSearch])
                        ->orWhereRaw('CircuitsVoice.ILEC_ID2Search LIKE ?', [$circuitSearch])
                        ->orWhereRaw('CircuitsData.ILEC_ID1Search LIKE ?', [$circuitSearch])
                        ->orWhereRaw('CircuitsData.ILEC_ID2Search LIKE ?', [$circuitSearch])
                        ->orWhereRaw('CircuitDIDs.DIDSearch LIKE ?', [$circuitSearch]);
                });
        }

        if (isset($filters['CarrierID'])) {
            $query->where('CarrierID', $filters['CarrierID']);
        }

        /**
         * Sorting:
         * The order cases need to be in the select because of the distinct
         * The order cases in the select need aliases because of how Laravel/PHP reads them
         * The aliases can't be used in the order by because of how Laravel builds the query with pagination
         * Use fnCleanString to make the special characters not count
         *
         * *If* there were AccountNumSearch and BTNSearch in the database,
         * I would use it for AccountNumSortEmpty and BTNSortEmpty for consistency,
         * but I don't think that fnCleanString is required here, and it slows it down.
         */
        $query
            ->select([
                'BTNAccounts.*',
                DB::raw("CASE WHEN NULLIF(AccountNum, '') IS NULL THEN 1 ELSE 0 END AS AccountNumSortEmpty"),
                DB::raw("NULLIF(dbo.fnCleanString(AccountNum), '') AS AccountNumSort"),
                DB::raw("CASE WHEN NULLIF(BTN, '') IS NULL THEN 1 ELSE 0 END AS BTNSortEmpty"),
                DB::raw("NULLIF(dbo.fnCleanString(BTN), '') AS BTNSort"),
            ])
            ->distinct()
            ->orderByRaw("CASE WHEN NULLIF(AccountNum, '') IS NULL THEN 1 ELSE 0 END") // NULL and '' last
            ->orderByRaw("NULLIF(dbo.fnCleanString(AccountNum), '') asc") // Alphabetical order, grouping NULL and ''
            ->orderByRaw("CASE WHEN NULLIF(BTN, '') IS NULL THEN 1 ELSE 0 END") // NULL and '' last
            ->orderByRaw("NULLIF(dbo.fnCleanString(BTN), '') asc"); // Alphabetical order, grouping NULL and ''

        $query->logQuery()->get();
        return Util::paginateDistinctQuery($query, null, ['BTNAccounts.BTNAccountID'], 'page', $page);
    }
}
