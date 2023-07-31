<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    [
        'middleware' => ['auth', 'auth.admin', 'logout'],
    ],
    'prefix' => 'collections'
], function (\Illuminate\Routing\Router $router) {

    $router->get('/addresses', function (Request $request) {
        $results = [];

        if ($request->has('phrase') && in_array($request->get('source', 'main'), ['main', 'remittance'])) {

            $phrase = trim(str_replace(',', '', $request->get('phrase')));
            $source = $request->get('source') == 'remittance'
                ? \App\Models\InvoiceRemittanceAddress::class
                : \App\Models\Address::class;

            if ($request->get('source') != 'remittance') { // TODO this should probably be remittance or other.
                $query = $source::search($phrase);

                $addressType = $request->get('addressType');

                switch ($addressType) {
                    case 1: // Service Address
                    case 2: // Location A Address
                    case 3: // Location Z Address
                        switch ($addressType) {
                            case 1: // Service Address
                                $query
                                    ->leftJoin('CircuitsVoice', 'CircuitsVoice.ServiceAddressID', '=', 'Addresses.AddressID')
                                    ->leftJoin('CircuitsData', 'CircuitsData.ServiceAddressID', '=', 'Addresses.AddressID');
                                break;
                            case 2: // Location A Address
                                $query
                                    ->leftJoin('CircuitsVoice', 'CircuitsVoice.LocationAAddressID', '=', 'Addresses.AddressID')
                                    ->leftJoin('CircuitsData', 'CircuitsData.LocationAAddressID', '=', 'Addresses.AddressID');
                                break;
                            case 3: // Location Z Address
                                $query
                                    ->leftJoin('CircuitsVoice', 'CircuitsVoice.LocationZAddressID', '=', 'Addresses.AddressID')
                                    ->leftJoin('CircuitsData', 'CircuitsData.LocationZAddressID', '=', 'Addresses.AddressID');
                                break;
                        }
                        $query
                            ->join('Circuits', function ($join) {
                                $join
                                    ->on('Circuits.CircuitID', '=', 'CircuitsVoice.CircuitID')
                                    ->orOn('Circuits.CircuitID', '=', 'CircuitsData.CircuitID');
                            })
                            ->where('Circuits.Status', '=', 1);
                        break;
                    case 4: // Site Address
                        $query
                            ->leftJoin('BTNAccounts', 'BTNAccounts.SiteAddressID', '=', 'Addresses.AddressID')
                            ->where('BTNAccounts.Status', '=', 1);
                        break;
                    case 5: // Billing Address
                        // I don't think we use these...
                        break;
                    case 6: // Remittance Address
                        // These are really on a different table...
                        break;
                    case 7: // Carrier Address
                        // These aren't used anymore...
                        break;
                }

                $query
                    ->select(
                        DB::raw("nullif(Address1, '') as Address1"),
                        DB::raw("nullif(Address2, '') as Address2"),
                        DB::raw("nullif(City, '') as City"),
                        DB::raw("nullif(State, '') as State"),
                        DB::raw("nullif(Zip, '') as Zip"),
                        DB::raw('max(' . (new $source)->getTable() . '.created_at) as created_at'),
                        DB::raw('max(' . (new $source)->getTable() . '.updated_at) as updated_at')
                    )
                    ->groupBy(
                        DB::raw("nullif(Address1, '')"),
                        DB::raw("nullif(Address2, '')"),
                        DB::raw("nullif(City, '')"),
                        DB::raw("nullif(State, '')"),
                        DB::raw("nullif(Zip, '')")
                    );
            } else {
                $query = $source::search($phrase);

                $query
                    ->leftJoin('InvoicesAccountsPayable', 'InvoicesAccountsPayable.RemittanceAddressID', '=', (new $source)->getTable() . '.RemittanceAddressID')
                    ->leftJoin('BTNAccounts', 'BTNAccounts.BTNAccountID', '=', 'InvoicesAccountsPayable.BTNAccountID')
                    ->where('BTNAccounts.Status', '=', 1);

                $query
                    ->select(
                        DB::raw('max(' . (new $source)->getTable() . '.RemittanceAddressID) as RemittanceAddressID'),
                        DB::raw("nullif(RemittanceName, '') as RemittanceName"),
                        DB::raw("nullif(Address1, '') as Address1"),
                        DB::raw("nullif(Address2, '') as Address2"),
                        DB::raw("nullif(City, '') as City"),
                        DB::raw("nullif(State, '') as State"),
                        DB::raw("nullif(Zip, '') as Zip"),
                        DB::raw('max(' . (new $source)->getTable() . '.created_at) as created_at'),
                        DB::raw('max(' . (new $source)->getTable() . '.updated_at) as updated_at')
                    )
                    ->groupBy(
                        DB::raw("nullif(RemittanceName, '')"),
                        DB::raw("nullif(Address1, '')"),
                        DB::raw("nullif(Address2, '')"),
                        DB::raw("nullif(City, '')"),
                        DB::raw("nullif(State, '')"),
                        DB::raw("nullif(Zip, '')")
                    );
            }

            $query
                ->latest('updated_at')->latest('created_at')
                ->take(min($request->get('limit', 10), 100))
                ->get()
                ->each(function ($result) use (&$results) {
                    $results[] = [
                        'string'  => $result->__toString(),
                        'address' => $result,
                    ];
                });
        }

        return [
            'items' => $results,
            'inputPhrase' => $request->get('phrase'),
        ];
    });

    $router->post('/accounts', function (Request $request) {

        $type   = $request->get('type');
        $search = $request->get('accountID');

        if ($type == 'BTN') {
            $account = \App\Models\BTNAccount::whereRaw("dbo.fnCleanString(BTN) LIKE dbo.fnCleanString(?)", [$search])->get();
        } else if ($type == 'AccountNum') {
            $account = \App\Models\BTNAccount::whereRaw("dbo.fnCleanString(AccountNum) LIKE dbo.fnCleanString(?)", [$search])->get();
        }

        return $account;
    });

    $router->post('/address', function (Request $request) {

        $SiteAddressID   = $request->get('SiteAddressID');

        $address = \App\Models\Address::find($SiteAddressID);

        return $address;
    });

    //Checks if CarrierCircuitID for a Circuit already exists on the same BTNAccount
    $router->post('/isduplicatecircuit', function (Request $request) {

        $carrierCircuitID = DB::selectOne(
            'select dbo.fnCleanString(?) as CleanString',
            [
                $request->get('CarrierCircuitID')
            ]
        )->CleanString;

        $query = App\Models\Circuit::where('CarrierCircuitIDSearch', $carrierCircuitID)
            ->where('BTNAccountID', $request->get('BTNAccountID'));

        if ($request->get('CircuitID') != null) {
            $query->where('CircuitID', '!=', $request->get('CircuitID'));
        }

        $query->whereIn(
            'Status',
            DB::table((new \App\Models\BTNStatusType)->getTable())
                ->select('BTNStatus')
                ->where('IsDisplay', true)
        );
        return $query->get();
    });
});
