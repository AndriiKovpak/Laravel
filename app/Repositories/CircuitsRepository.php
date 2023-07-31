<?php

namespace App\Repositories;

use App\Components\Repositories\RepositoryContract;
use App\Models\Circuit;

use App\Models\Util;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class CircuitsRepository
 * @package App\Repositories
 */
class CircuitsRepository implements RepositoryContract
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
        $with = ['Category', 'StatusType', 'BTNAccount'];

        $query = Circuit::with($with)
            ->leftJoin(DB::raw('BTNStatusTypes BTNStatusTypesCircuit'), 'Circuits.Status', '=', 'BTNStatusTypesCircuit.BTNStatus')
            ->where('BTNStatusTypesCircuit.IsDisplay', '=', '1');

        if (isset($filters['BTNAccount'])) {
            $query->where('Circuits.BTNAccountID', '=', $filters['BTNAccount']->BTNAccountID);
        }

        if (Auth::user()->cant('edit')) {
            $userId = Auth::id();
            $query->leftJoin('BTNAccounts', 'Circuits.BTNAccountID', '=', 'BTNAccounts.BTNAccountID')
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

        // Third search box
        if (isset($filters['circuitInventorySearch'])) {
            // Limit to displayable and recent BTNAccounts, but only for third box search.
            if (Auth::user()->can('edit')) {
                // Join with BTNAccounts if we didn't do so above.
                $query->leftJoin('BTNAccounts', 'Circuits.BTNAccountID', '=', 'BTNAccounts.BTNAccountID');
            }
            $query
                ->leftJoin('BTNStatusTypes', 'BTNAccounts.Status', '=', 'BTNStatusTypes.BTNStatus')
                ->where('BTNStatusTypes.IsDisplay', '<>', '0')
                ->where(function ($query) {
                    $query->where('BTNAccounts.Status', '=', 1)
                        ->orWhere('BTNAccounts.Updated_at', '>', DB::raw('DATEADD(year, -2, getdate())'));

                    if (time() < 1573430400) { // Include empty Updated_at until 11/11/2019 (two years after release)
                        $query->orWhereNull('BTNAccounts.Updated_at');
                    }
                });

            // It would be faster to clean in PHP, but using SQL makes sure it is cleaned consistently.
            $circuitInventorySearch = DB::selectOne('select dbo.fnCleanString(?) as CleanString', [$filters['circuitInventorySearch']])->CleanString;
            $circuitInventorySearch = '%' . $circuitInventorySearch . '%';

            $query->leftJoin('CircuitsVoice', 'Circuits.CircuitID', '=', 'CircuitsVoice.CircuitID')
                ->leftJoin('CircuitsData', 'Circuits.CircuitID', '=', 'CircuitsData.CircuitID')
                //->leftJoin('CircuitsSatellite', 'Circuits.CircuitID', '=', 'CircuitsSatellite.CircuitID') // We're not actually searching any CircuitsSatellite columns... Yet.
                ->leftJoin('CircuitDIDs', 'Circuits.CircuitID', '=', 'CircuitDIDs.CircuitID')
                ->where(function ($query) use ($circuitInventorySearch) {
                    $query->whereRaw('Circuits.CarrierCircuitIDSearch LIKE ?', [$circuitInventorySearch])
                        ->orWhereRaw('Circuits.BillUnderBTNSearch LIKE ?', [$circuitInventorySearch])
                        ->orWhereRaw('CircuitsVoice.SPID_Phone1Search LIKE ?', [$circuitInventorySearch])
                        ->orWhereRaw('CircuitsVoice.SPID_Phone2Search LIKE ?', [$circuitInventorySearch])
                        ->orWhereRaw('CircuitsVoice.ILEC_ID1Search LIKE ?', [$circuitInventorySearch])
                        ->orWhereRaw('CircuitsVoice.ILEC_ID2Search LIKE ?', [$circuitInventorySearch])
                        ->orWhereRaw('CircuitsData.ILEC_ID1Search LIKE ?', [$circuitInventorySearch])
                        ->orWhereRaw('CircuitsData.ILEC_ID2Search LIKE ?', [$circuitInventorySearch])
                        ->orWhereRaw('CircuitDIDs.DIDSearch LIKE ?', [$circuitInventorySearch]);
                })
                ->where('Circuits.Status', '=', 1); // Only return active

            $query->select('Circuits.*')->distinct() // We only want the columns from Circuits; any joins were just for searching.
                ->latest('Circuits.created_at')
                ->orderBy('Circuits.CircuitID', 'desc');

            return Util::paginateDistinctQuery($query, 1, ['Circuits.CircuitID'], 'page', $page);
        }

        // Circuit search inside inventory
        if (!empty($filters['search'])) {
            // It would be faster to clean in PHP, but using SQL makes sure it is cleaned consistently.
            $search = DB::selectOne('select dbo.fnCleanString(?) as CleanString', [$filters['search']])->CleanString;
            $search = '%' . $search . '%';

            $query->leftJoin('CircuitsVoice', 'Circuits.CircuitID', '=', 'CircuitsVoice.CircuitID')
                ->leftJoin('CircuitsData', 'Circuits.CircuitID', '=', 'CircuitsData.CircuitID')
                //->leftJoin('CircuitsSatellite', 'Circuits.CircuitID', '=', 'CircuitsSatellite.CircuitID') // We're not actually searching any CircuitsSatellite columns... Yet.
                ->leftJoin('CircuitDIDs', 'Circuits.CircuitID', '=', 'CircuitDIDs.CircuitID')
                ->where(function ($query) use ($search) {
                    $query->whereRaw('Circuits.CarrierCircuitIDSearch LIKE ?', [$search])
                        ->orWhereRaw('Circuits.BillUnderBTNSearch LIKE ?', [$search])
                        ->orWhereRaw('CircuitsVoice.SPID_Phone1Search LIKE ?', [$search])
                        ->orWhereRaw('CircuitsVoice.SPID_Phone2Search LIKE ?', [$search])
                        ->orWhereRaw('CircuitsVoice.ILEC_ID1Search LIKE ?', [$search])
                        ->orWhereRaw('CircuitsVoice.ILEC_ID2Search LIKE ?', [$search])
                        ->orWhereRaw('CircuitsData.ILEC_ID1Search LIKE ?', [$search])
                        ->orWhereRaw('CircuitsData.ILEC_ID2Search LIKE ?', [$search])
                        ->orWhereRaw('CircuitDIDs.DIDSearch LIKE ?', [$search]);
                });
        }

        /**
         * Sorting:
         * The order cases need to be in the select because of the distinct
         * The order cases in the select need aliases because of how Laravel/PHP reads them
         * The aliases can't be used in the order by because of how Laravel builds the query with pagination
         * Use fnCleanString and CarrierCircuitIDSearch to make the special characters not count
         */
        $query
            ->select([
                'Circuits.*',
                DB::raw("CASE WHEN NULLIF(CarrierCircuitIDSearch, '') IS NULL THEN 1 ELSE 0 END AS CarrierCircuitIDSortEmpty"),
                DB::raw("NULLIF(CarrierCircuitIDSearch, '') AS CarrierCircuitIDSort"),
                DB::raw("CASE WHEN NULLIF(BillUnderBTNSearch, '') IS NULL THEN 1 ELSE 0 END AS BillUnderBTNSortEmpty"),
                DB::raw("NULLIF(BillUnderBTNSearch, '') AS BillUnderBTNSort"),
            ])
            ->distinct()
            ->orderByRaw("CASE WHEN NULLIF(CarrierCircuitIDSearch, '') IS NULL THEN 1 ELSE 0 END") // NULL and '' last
            ->orderByRaw("NULLIF(CarrierCircuitIDSearch, '') asc") // Alphabetical order, grouping NULL and ''
            ->orderByRaw("CASE WHEN NULLIF(BillUnderBTNSearch, '') IS NULL THEN 1 ELSE 0 END") // NULL and '' last
            ->orderByRaw("NULLIF(BillUnderBTNSearch, '') asc") // Alphabetical order, grouping NULL and ''
            ->orderBy('Circuits.CircuitID');

        return Util::paginateDistinctQuery($query, null, ['Circuits.CircuitID'], 'page', $page);
    }

    /**
     * Determine what page the Circuit is on
     *
     * It works by seeing how many Circuits are before it (according to the ORDER BY columns in paginate() above)
     *
     * @param Circuit $Circuit
     * @return float
     */
    public function getPage(Circuit $Circuit)
    {
        $query = $Circuit
            ->BTNAccount
            ->Circuits()
            ->leftJoin('BTNStatusTypes', 'Status', '=', 'BTNStatus')
            ->where('BTNStatusTypes.IsDisplay', '=', '1')// Only count ones we display

            ->where(function ($query) use ($Circuit) { // Use where with a function so the ORs will not mess with the query by BTNAccountID, etc.

                // This is split into 4 conditions because < comparisons don't work with NULL
                // Also, we need to put NULLs and empty strings last

                $emptyCarrierCircuitID = empty($Circuit->CarrierCircuitIDSearch);
                $emptyBillUnderBTN = empty($Circuit->BillUnderBTNSearch);

                if ($emptyCarrierCircuitID && $emptyBillUnderBTN) {
                    /* Pseudocode:
                        !empty(CarrierCircuitID)
                        || !empty(BillUnderBTN)
                        || CircuitID <= $Circuit->CircuitID
                    */
                    $query
                        ->orWhereRaw("NULLIF(CarrierCircuitIDSearch, '') IS NOT NULL")
                        ->orWhereRaw("NULLIF(BillUnderBTNSearch, '') IS NOT NULL")
                        ->orWhere('CircuitID', '<=', $Circuit->CircuitID);
                } elseif ($emptyCarrierCircuitID && !$emptyBillUnderBTN) {
                    /* Pseudocode:
                        !empty(CarrierCircuitID)
                        || BillUnderBTN < $Circuit->BillUnderBTN
                        || (
                            BillUnderBTN == $Circuit->BillUnderBTN
                            && CircuitID <= $Circuit->CircuitID
                        )
                    */
                    $query
                        ->orWhereRaw("NULLIF(CarrierCircuitIDSearch, '') IS NOT NULL")
                        ->orWhere('BillUnderBTNSearch', '<', $Circuit->BillUnderBTNSearch)
                        ->orWhere(function ($query) use ($Circuit) {
                            $query
                                ->where('BillUnderBTNSearch', '=', $Circuit->BillUnderBTNSearch)
                                ->where('CircuitID', '<=', $Circuit->CircuitID);
                        });
                } elseif (!$emptyCarrierCircuitID && $emptyBillUnderBTN) {
                    /* Pseudocode:
                        CarrierCircuitID < $Circuit->CarrierCircuitID
                        || (
                            CarrierCircuitID == $Circuit->CarrierCircuitID
                            && (
                                !empty(BillUnderBTNSearch)
                                || CircuitID <= $Circuit->CircuitID
                            )
                        )
                    */
                    $query
                        ->orWhere('CarrierCircuitIDSearch', '<', $Circuit->CarrierCircuitIDSearch)
                        ->orWhere(function ($query) use ($Circuit) {
                            $query
                                ->where('CarrierCircuitIDSearch', '=', $Circuit->CarrierCircuitIDSearch)
                                ->where(function ($query) use ($Circuit) {
                                    $query
                                        ->orWhereRaw("NULLIF(BillUnderBTNSearch, '') IS NOT NULL")
                                        ->orWhere('CircuitID', '<=', $Circuit->CircuitID);
                                });
                        });
                } elseif (!$emptyCarrierCircuitID && !$emptyBillUnderBTN) {
                    /* Pseudocode:
                        CarrierCircuitID < $Circuit->CarrierCircuitID
                        || (
                            CarrierCircuitID == $Circuit->CarrierCircuitID
                            && (
                                BillUnderBTN < $Circuit->BillUnderBTN
                                || (
                                    BillUnderBTN == $Circuit->BillUnderBTN
                                    && CircuitID <= $Circuit->CircuitID
                                )
                            )
                        )
                    */
                    $query
                        ->orWhere('CarrierCircuitIDSearch', '<', $Circuit->CarrierCircuitIDSearch)
                        ->orWhere(function ($query) use ($Circuit) {
                            $query
                                ->where('CarrierCircuitIDSearch', '=', $Circuit->CarrierCircuitIDSearch)
                                ->where(function ($query) use ($Circuit) {
                                    $query
                                        ->orWhere('BillUnderBTNSearch', '<', $Circuit->BillUnderBTNSearch)
                                        ->orWhere(function ($query) use ($Circuit) {
                                            $query
                                                ->where('BillUnderBTNSearch', '=', $Circuit->BillUnderBTNSearch)
                                                ->where('CircuitID', '<=', $Circuit->CircuitID);
                                        });
                                });
                        });
                }
            });
            
        return ceil($query->count() / $Circuit->getPerPage());
    }
}
