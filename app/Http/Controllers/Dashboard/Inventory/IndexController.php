<?php

namespace App\Http\Controllers\Dashboard\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\UpdateRequest;
use App\Models\BTNAccount;
use App\Models\BTNAccountNote;
use App\Models\BTNStatusType;
use App\Models\Carrier;
use App\Models\DivisionDistrict;
use App\Models\InvoiceAP;
use App\Models\StateCode;
use App\Repositories\BTNAccountsRepository;
use App\Repositories\CircuitsRepository;
use App\Services\AddressesService;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class IndexController
 * @package App\Http\Controllers\Dashboard\Inventory
 */
class IndexController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin')->except(['index', 'show']);
        $this->middleware('auth.district')->only(['show']);
        $this->middleware('auth.model');
    }

    /**
     * Display the main inventory table.
     *
     * @param Request $request
     * @param BTNAccountsRepository $repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, BTNAccountsRepository $BTNRepository, CircuitsRepository $CircuitRepository)
    {
        if ($request->get('newSearch')) {
            $request->session()->forget('inventoryIndexRequest');
        }

        if (!empty($request->except(['newSearch']))) {
            $lastRequest = $request->session()->pull('inventoryIndexRequest');
            $request->session()->put('inventoryIndexRequest', $request->except(['newSearch']));
        } else {
            $lastRequest = [];
        }

        $change = ($request->get('act') == 'change-btn') && $request->has(['inventory', 'invoice']);
        $circuit = ($request->get('act') == 'new-circuit');

        if ($request->session()->get('inventoryIndexRequest.circuitInventorySearch') != '') {
            $Circuits = $CircuitRepository->paginate($request->session()->get('inventoryIndexRequest'), $request->session()->get('inventoryIndexRequest.page', 1), []);

            // Redirect if there is only one result.
            if ($Circuits->count() == 1) {
                $request->session()->put('inventoryIndexRequest', $lastRequest);
                $Circuit = $Circuits->first();
                $BTNAccount = $Circuit->BTNAccount;
                return redirect(
                    route('dashboard.inventory.circuits.show', [
                        'inventory' => $BTNAccount,
                        'circuit' => $Circuit,
                        'page' => $CircuitRepository->getPage($Circuit),
                    ])
                );
            }

            $BTNAccounts = null;
        } else {
            $BTNAccounts = $BTNRepository->paginate($request->session()->get('inventoryIndexRequest'), $request->session()->get('inventoryIndexRequest.page', 1), []);

            // Redirect if there is only one result.
            if ($BTNAccounts->count() == 1) {
                $request->session()->put('inventoryIndexRequest', $lastRequest);
                return redirect(
                    route('dashboard.inventory.show', [
                        'inventory' => $BTNAccounts->first(),
                    ])
                );
            }

            $Circuits = null;
        }

        return view('dashboard.inventory.index.index', [
            'BTNAccounts'   =>  $BTNAccounts,
            'Circuits'      =>  $Circuits,
            'InvoiceAP'     =>  $change ? InvoiceAP::find($request->input('invoice')) : null,

            '_change'       => $change,
            '_circuit'      => $circuit,
            '_functional'   => ($circuit || $change),
            '_options'      => [
                'CarrierID'  => Carrier::getOptionsForSelect(),
            ],
        ]);
    }

    /**
     * Display information about single BTN.
     *
     * @param BTNAccount $BTNAccount
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(BTNAccount $BTNAccount)
    {
        return view('dashboard.inventory.index.show', [
            'BTNAccount' => $BTNAccount
        ]);
    }

    /**
     * Edit single BTN Account.
     *
     * @param BTNAccount $BTNAccount
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(BTNAccount $BTNAccount)
    {
        return view('dashboard.inventory.index.edit', [
            'BTNAccount'    =>  $BTNAccount,
            'Addresses'     =>  $BTNAccount->SiteAddress,
            '_options'      =>  [
                'Status'    =>  BTNStatusType::getOptionsForSelect(),
                'CarrierID' =>  Carrier::getOptionsForSelect(),
                'DivisionDistrictID'    =>  DivisionDistrict::getOptionsForSelect(),
                'State'                 =>  StateCode::getStateDropdownOptions()
            ]
        ]);
    }

    /**
     * Update single BTN Account
     *
     * @param BTNAccount $BTNAccount
     * @param UpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BTNAccount $BTNAccount, UpdateRequest $request, AddressesService $addressesService)
    {
        //Stores a disconnect date when a BTN is disconnected
        if ($BTNAccount->Status != 3 && $request->Status == 3) {
            $BTNAccount->update(array_merge($request->data(), ['DisconnectDate' => Carbon::today(), 'UpdatedByUserID' => auth()->id()]));
        } else if ($request->Status != 3) {
            $BTNAccount->update(array_merge($request->data(), ['DisconnectDate' => null, 'UpdatedByUserID' => auth()->id()]));
        } else {
            $BTNAccount->update(array_merge($request->data(), ['UpdatedByUserID' => auth()->id()]));
        }
        $addressesService->updateAddress($BTNAccount, $request);
        if ($request->has('Note')) {
            $noteData = $request->only(['Note']);
            if($noteData['Note']) {
                $noteData['UpdatedByUserID'] = auth()->id();
                $BTNAccount->Notes()->create($noteData);
            }
        }

        return redirect()
            ->route('dashboard.inventory.show', [
                'inventory' =>  $BTNAccount
            ])
            ->with('notification.success', 'General information was successfully updated.');
    }

    /**
     * Display page to create new BTN
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('dashboard.inventory.index.create', [
            'BTNAccount'    =>  [],
            'Addresses'     =>  null,
            '_options'      =>  [
                'Status'    =>  BTNStatusType::getOptionsForSelect(),
                'CarrierID'             =>  Carrier::getOptionsForSelect(),
                'DivisionDistrictID'    =>  DivisionDistrict::getOptionsForSelect(),
                'State'                 =>  StateCode::getStateDropdownOptions()
            ]
        ]);
    }

    /**
     * Create new BTM
     *
     * @param UpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UpdateRequest $request, AddressesService $AddressesService)
    {
        $BTNAccount = BTNAccount::create($request->accountData());
        $AddressesService->updateAddress($BTNAccount, $request);

        if ($request->has('Note')) {

            $BTNAccount->Notes()->create($request->only(['Note']));
        }

        return redirect()
            ->route('dashboard.inventory.show', [
                'inventory' =>  $BTNAccount
            ])
            ->with('notification.success', 'Successfully created new BTN');
    }

    /**
     * Mark single inventory as inactive.
     *
     * @param BTNAccount $account
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(BTNAccount $account, Request $request)
    {
        $account->setAttribute('Status', BTNStatusType::STATUS_DELETE);
        $account->save();

        return redirect()
            ->route('dashboard.inventory.index', ['page' => $request->get('page', 1)])
            ->with('notification.success', 'Successfully deactivated the BTN Account.');
    }

    // delete inventory note
    public function deleteNote(BTNAccount $account, BTNAccountNote $note, Request $request)
    {
        $note->delete();
        return redirect()
            ->route('dashboard.inventory.show', [$account, 'page' => $request->get('page', 1)])
            ->with('notification.success', 'Successfully deleted the BTN Account Note.');
    }

    // set as SAIC
    public function saic(BTNAccount $account, Request $request){

        $account->setAttribute('IsSAIC', !$account->IsSAIC);
        $account->setAttribute('SAICDate', Carbon::now());
        $account->save();
        return redirect()
            ->route('dashboard.inventory.show', [$account, 'page' => $request->get('page', 1)]);
    }
}
