<?php

namespace App\Http\Controllers\Dashboard\Inventory;

use App\Models\Circuit;
use App\Models\Category;
use App\Models\StateCode;
use App\Models\BTNAccount;
use App\Models\FeatureType;
use App\Models\ServiceType;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\BTNStatusType;
use App\Models\DivisionDistrict;
use App\Models\CircuitDescription;
use App\Models\CircuitUpdateQueue;
use App\Models\HandoffType;
use App\Services\AddressesService;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Repositories\CircuitsRepository;
use App\Http\Requests\Inventory\Circuits\StoreRequest;

/**
 * Class CircuitsController
 * @package App\Http\Controllers\Dashboard\Inventory
 */
class CircuitsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin')->except(['index', 'show']);
        $this->middleware('auth.district')->only(['index', 'show']);
        $this->middleware('auth.model');
    }

    /**
     * @param BTNAccount $BTNAccount
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(BTNAccount $BTNAccount, Request $request, CircuitsRepository $repository)
    {
        $filters = array_merge($request->all(), ['BTNAccount' => $BTNAccount]);
        $Circuits = $repository->paginate($filters, $request->get('page', 1), []);
        // Go straight to the circuit if there is only one.
        if (!empty($filters['search']) && $Circuits->count() == 1 && $request->get('page') == 1) {
            $Circuit = $Circuits->first();
            return redirect()
                ->route('dashboard.inventory.circuits.show', [
                    $BTNAccount,
                    $Circuit,
                    'page'      =>  $request->get('page')
                ])
                ->withInput();
        }
        $request->flash();
        return view('dashboard.inventory.circuits.index', [
            'BTNAccount'    =>  $BTNAccount,
            'Circuits'      =>  $Circuits,
            'page'          =>  $request->get('page', 1),
            'search'        =>  $request->get('search', '')
        ]);
    }

    /**
     * Show General Information of a Circuit
     *
     * @param BTNAccount $BTNAccount
     * @param Circuit $Circuit
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(BTNAccount $BTNAccount, Circuit $Circuit, Request $request, CircuitsRepository $repository)
    {
        $filters = array_merge($request->all(), ['BTNAccount' => $BTNAccount]);
        // Not using $request->get('page', $repository->getPage($Circuit)); because the function for the second argument would still be run
        $page = $request->get('page') ?: $repository->getPage($Circuit);
        $Circuits = $repository->paginate($filters, $page, []);

        // fetch DID data to display
        $DIDs = $Circuit->DIDs()->orderBy('DID', 'asc');

        if ($request->has('DID')) {

            $search = DB::selectOne('select dbo.fnCleanString(?) as CleanString', [$request->get('DID')])->CleanString;

            $DIDs->whereRaw("CircuitDIDs.DIDSearch LIKE ?", ['%' . $search . '%']);
        }

        $DIDsPaginate = $DIDs->paginate(null, ['*'], 'did-page'); //// null, null, 'did-page'
        // Show last page if trying to show page after last page (like when it was deleted)
        if ($DIDsPaginate->lastPage() < request('did-page')) {
            $DIDsPaginate = $DIDs->paginate(null, ['*'], 'did-page', $DIDsPaginate->lastPage() ?: 1);
        }

        // fetch MAC Notes to display
        $CircuitMACs = $Circuit->MACs()->latest();
        $CircuitMACsPaginate = $CircuitMACs->paginate(null, ['*'], 'mac-page'); // null, null, 'mac-page'
        // dd(request('mac-page'), $CircuitMACsPaginate->lastPage());
        // Show last page if trying to show page after last page (like when it was deleted)
        // May not really apply to MACs
        if ($CircuitMACsPaginate->lastPage() < request('mac-page')) {
            $CircuitMACsPaginate = $CircuitMACs->paginate(null, null, 'mac-page', $CircuitMACsPaginate->lastPage() ?: 1);
        }


        return view('dashboard.inventory.circuits.general-information.show', [
            'BTNAccount'    =>  $BTNAccount,
            'Circuit'       =>  $Circuit,
            'Circuits'      =>  $Circuits,
            'DIDs'          =>  $DIDsPaginate,
            'CircuitMACs'   =>  $CircuitMACsPaginate,
            'page'          =>  $page,
            'search'        =>  $request->get('search', ''),
            'DID'           =>  $request->get('DID', ''),
        ]);
    }

    /**
     * Display page to edit Circuit
     *
     * @param BTNAccount $BTNAccount
     * @param Circuit $Circuit
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(BTNAccount $BTNAccount, Circuit $Circuit, Request $request, CircuitsRepository $repository)
    {
        $filters = array_merge($request->all(), ['BTNAccount' => $BTNAccount]);
        $page = $request->get('page') ?: $repository->getPage($Circuit);
        $Circuits = $repository->paginate($filters, $page, []);
        $data = [
            'BTNAccount'    =>  $BTNAccount,
            'Circuit'       =>  $Circuit,
            'Circuits'      =>  $Circuits,
            'Category'      =>  $Circuit['Category'],
            '_options'      =>  [
                'Category'      =>  Category::getOptionsForSelect(),
                'Status'        =>  BTNStatusType::getOptionsForSelect(),
                'ServiceType'   =>  ServiceType::getOptionsForSelect($Circuit['CategoryID']),
                'DescriptionID'     =>  CircuitDescription::getOptionsForSelect(),
                'DivisionDistrict'  =>  DivisionDistrict::getOptionsForSelect(),
                'State'         =>  StateCode::getStateDropdownOptions(),
                'FeatureType'   =>  FeatureType::getOptionsForSelect($Circuit['CategoryID']),
                'HandoffType'   =>  HandoffType::getOptionsForSelect(),
            ],
            'page'          =>  $page,
        ];
        // Make sure the currently selected option is in the array if it would normally be inactive, etc.
        // Note: array_merge creates new indexes if the originals were numeric
        if ($Circuit['Category'] && !$Circuit['Category']['IsActive']) {
            $data['_options']['Category'][$Circuit['Category']['CategoryID']] = $Circuit['Category']['CategoryName'];
        }
        if ($Circuit['StatusType'] && !$Circuit['StatusType']['IsDisplay']) {
            $data['_options']['Status'][$Circuit['StatusType']['BTNStatus']] = $Circuit['StatusType']['BTNStatusName'];
        }
        if ($Circuit['Service'] && !$Circuit['Service']['IsActive']) {
            $data['_options']['ServiceType'][$Circuit['Service']['ServiceType']] = $Circuit['Service']['ServiceTypeName'];
        }
        if ($Circuit['DivisionDistrict'] && !$Circuit['DivisionDistrict']['IsActive']) {
            $data['_options']['DivisionDistrict'][$Circuit['DivisionDistrict']['DivisionDistrictID']] = $Circuit['DivisionDistrict']['DivisionDistrictName'];
        }
        foreach ($Circuit['Features'] as $CircuitFeature) {
            if (!$CircuitFeature['Feature']['IsActive']) {
                $data['_options']['FeatureType'][$CircuitFeature['Feature']['FeatureType']] = $CircuitFeature['Feature']['FeatureName'];
            }
        }
        return view('dashboard.inventory.circuits.general-information.edit', $data);
    }

    /**
     * Update single Circuit.
     *
     * @param BTNAccount $BTNAccount
     * @param Circuit $Circuit
     * @param StoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BTNAccount $BTNAccount, Circuit $Circuit, StoreRequest $request, AddressesService $AddressesService)
    {
        $Circuit->update($request->getCircuitData());
        $Circuit->touch();

        //Makes sure a CircuitCategory has already been created. If not it creates one.
        // dd($Circuit->CategoryData, $request->getCategoryData());
        if ($Circuit->CategoryData) {
            $Circuit->CategoryData()->update($request->getCategoryData());
        } else {
            $Circuit->CategoryData()->create($request->getCategoryData());
        }

        $AddressesService->updateCircuitAddresses($Circuit, $request);
        $newFeatureIDs = [];
        if ($request->has('CircuitFeatures')) {
            foreach ($request->get('CircuitFeatures') as $CircuitFeature) {
                if (Arr::has($CircuitFeature, 'CircuitFeatureID')) {
                    $Circuit->Features()->firstOrNew([
                        'CircuitFeatureID' => $CircuitFeature['CircuitFeatureID'],
                    ])->setAttribute('FeatureCost', $CircuitFeature['FeatureCost'])->setAttribute('FeatureType', $CircuitFeature['FeatureType'])->save();
                } else {
                    $newCircuitFeature = $Circuit->Features()->create($CircuitFeature);
                    $CircuitFeature['CircuitFeatureID'] = (string) $newCircuitFeature->getAttribute('CircuitFeatureID');
                    $newFeatureIDs[] = $CircuitFeature['CircuitFeatureID'];
                }
            }
            // Delete those that don't exist. Doing this instead of deleting all then recreating ones that exists maintains Created_at and Updated_At history.
            $FeatureIDs = Arr::pluck($request->get('CircuitFeatures'), 'CircuitFeatureID');
            foreach ($Circuit->Features as $CircuitFeature) {
                if (!in_array($CircuitFeature['CircuitFeatureID'], $FeatureIDs) && !in_array($CircuitFeature['CircuitFeatureID'], $newFeatureIDs)) {
                    $CircuitFeature->delete();
                }
            }
        } else {
            // Delete all features CircuitFeatures wasn't in the request.
            $Circuit->Features()->delete();
        }

        if ($request->has('Notes')) { // If there is a note in the request
            if (
                $Circuit->Notes()->count() == 0 // And there is not a note saved
                || $Circuit->Notes()->latest()->first()['Note'] != $request->get('Notes') // Or the submitted note doesn't match the last note
            ) {
                $Circuit->Notes()->create([
                    'Note'  =>  $request->get('Notes'), // Create a note
                ]);
            }
        } elseif ($Circuit->Notes()->count()) { // If there is no note in the request, but there is one saved
            $Circuit->Notes()->create([
                'Note'  =>  '', // Create an empty note
            ]);
        }

        return redirect()
            ->route('dashboard.inventory.circuits.show', [
                $BTNAccount,
                $Circuit,
                'page'      =>  $request->get('page')
            ])
            ->with('notification.success', 'Successfully updated information about Circuit.');
    }

    /**
     * Display page to create new Circuit
     *
     * @param BTNAccount $BTNAccount
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(BTNAccount $BTNAccount, Request $request, CircuitsRepository $repository)
    {
        $filters = array_merge($request->all(), ['BTNAccount' => $BTNAccount]);
        $page = $request->get('page', 1);
        $Circuits = $repository->paginate($filters, $page, []);
        return view('dashboard.inventory.circuits.general-information.create', [
            'Addresses'     =>  ['AddressID' => null, 'StateID' => null],
            'BTNAccount'    =>  $BTNAccount,
            'Circuit'       =>  [],
            'Circuits'      =>  $Circuits,
            'CategoryID'    =>  $request->get('category'),
            'Category'      =>  Category::active()->where('CategoryID', $request->get('category'))->firstOrFail(),
            '_options'      =>  [
                'Category'      =>  Category::getOptionsForSelect(),
                'Status'        =>  BTNStatusType::getOptionsForSelect(),
                'ServiceType'   =>  ServiceType::getOptionsForSelect($request->get('category')),
                'DescriptionID'     =>  CircuitDescription::getOptionsForSelect(),
                'DivisionDistrict'  =>  DivisionDistrict::getOptionsForSelect(),
                'State'                 =>  StateCode::getStateDropdownOptions(),
                'FeatureType'   =>  FeatureType::getOptionsForSelect($request->get('category')),
                'HandoffType'   =>  HandoffType::getOptionsForSelect(),
            ],
            'page'          =>  $page,
        ]);
    }

    /**
     * Create new Circuit
     *
     * @param BTNAccount $BTNAccount
     * @param StoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BTNAccount $BTNAccount, StoreRequest $request, AddressesService $AddressesService)
    {
        $Circuit = $BTNAccount->Circuits()->create($request->getCircuitData());
        $CircuitQueue = CircuitUpdateQueue::find($Circuit->CircuitID);
        $Circuit = Circuit::find($CircuitQueue->CircuitID);
        $Circuit->CategoryData()->create($request->getCategoryData());
        $AddressesService->updateCircuitAddresses($Circuit, $request);

        if ($request->has('CircuitFeatures')) {
            foreach ($request->get('CircuitFeatures') as $CircuitFeature) {
                if (Arr::has($CircuitFeature, 'FeatureType') && Arr::has($CircuitFeature, 'FeatureCost')) {
                    $Circuit->Features()->create($CircuitFeature);
                }
            }
        }

        if ($request->has('Notes')) {
            $Circuit->Notes()->create([
                'Note'  =>  $request->get('Notes'),
            ]);
        }

        return redirect()
            ->route('dashboard.inventory.circuits.show', [
                $BTNAccount,
                $Circuit,
                'page'      =>  $request->get('page')
            ])
            ->with('notification.success', 'Successfully created new Circuit');
    }

    /**
     * Mark single Circuit as deleted one.
     *
     * @param BTNAccount $BTNAccount
     * @param Circuit $Circuit
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(BTNAccount $BTNAccount, Circuit $Circuit, Request $request)
    {
        $Circuit->setAttribute('Status', BTNStatusType::STATUS_DELETE);
        $Circuit->save();

        if ($request->loc == 'Main') {
            return redirect()
                ->route('dashboard.inventory.index', [
                    'inventory' => $BTNAccount,
                    'page'      => $request->get('page', 1),
                    'field'     => 'CircuitID'
                ])
                ->with('notification.success', 'The Circuit was successfully marked as deleted.');
        } else if ($request->loc == 'Circuit') {
            return redirect()
                ->route('dashboard.inventory.circuits.index', [
                    'inventory' => $BTNAccount,
                    'page'      => $request->get('page', 1)
                ])
                ->with('notification.success', 'The Circuit was successfully marked as deleted.');
        }
    }
}
