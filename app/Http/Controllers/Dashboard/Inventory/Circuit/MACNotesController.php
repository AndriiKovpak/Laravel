<?php

namespace App\Http\Controllers\Dashboard\Inventory\Circuit;

use App\Models\Circuit;
use App\Models\MACType;
use App\Models\BTNAccount;
use App\Models\CircuitMAC;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\CircuitsRepository;
use App\Http\Requests\Inventory\Circuits\MACRequest;
use App\Models\Util;

/**
 * Class MACNotesController
 * @package App\Http\Controllers\Dashboard\Inventory\Circuit
 */
class MACNotesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin')->except(['index', 'show']);
        $this->middleware('auth.district')->only(['index', 'show']);
        $this->middleware('auth.model');
    }

    /**
     * Display all the Circuit MAC Note
     *
     * @param BTNAccount $BTNAccount
     * @param Circuit $Circuit
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(BTNAccount $BTNAccount, Circuit $Circuit, Request $request, CircuitsRepository $repository)
    {
        $filters = array_merge($request->all(), ['BTNAccount' => $BTNAccount]);
        $page = $request->get('page') ?: $repository->getPage($Circuit);
        $Circuits = $repository->paginate($filters, $page, []);
        $CircuitMACs = $Circuit->MACs()->latest();

        $CircuitMACsPaginate = $CircuitMACs->paginate(15);
        // Show last page if trying to show page after last page (like when it was deleted)
        // May not really apply to MACs
        if ($CircuitMACsPaginate->lastPage() < request('mac-page')) {
            $CircuitMACsPaginate = $CircuitMACs->paginate(null, null, 'mac-page', $CircuitMACsPaginate->lastPage() ?: 1);
        }

        // return redirect to circuit.show. will remove index function in the future.
        Util::log("mac.index was called. please review why it is called", true, true);
        return redirect()
        ->route('dashboard.inventory.circuits.show', [
            $BTNAccount,
            $Circuit,
            'page'          =>  $page,
        ]);

        return view('dashboard.inventory.circuits.mac.index', [
            'BTNAccount'    =>  $BTNAccount,
            'Circuit'       =>  $Circuit,
            'Circuits'      =>  $Circuits,
            'CircuitMACs'   =>  $CircuitMACsPaginate,
            'page'          =>  $page,
        ]);
    }

    /**
     * Show single Circuit MAC Note
     *
     * @param BTNAccount $BTNAccount
     * @param Circuit $Circuit
     * @param CircuitMAC $CircuitMAC
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(BTNAccount $BTNAccount, Circuit $Circuit, CircuitMAC $CircuitMAC, Request $request, CircuitsRepository $repository)
    {
        $filters = array_merge($request->all(), ['BTNAccount' => $BTNAccount]);
        $page = $request->get('page') ?: $repository->getPage($Circuit);
        $Circuits = $repository->paginate($filters, $page, []);

        return view('dashboard.inventory.circuits.mac.show', [
            'BTNAccount'    =>  $BTNAccount,
            'Circuit'       =>  $Circuit,
            'Circuits'      =>  $Circuits,
            'CircuitMAC'    =>  $CircuitMAC,
            'MACType'       =>  $CircuitMAC['Type'] ? $CircuitMAC['Type'] : MACType::find($request->get('type', MACType::GENERAL)),
            'page'          =>  $page,
        ]);
    }

    /**
     * Display page to create new Circuit MAC Note
     *
     * @param BTNAccount $BTNAccount
     * @param Circuit $Circuit
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(BTNAccount $BTNAccount, Circuit $Circuit, Request $request, CircuitsRepository $repository)
    {
        $filters = array_merge($request->all(), ['BTNAccount' => $BTNAccount]);
        $page = $request->get('page') ?: $repository->getPage($Circuit);
        $Circuits = $repository->paginate($filters, $page, []);

        return view('dashboard.inventory.circuits.mac.create', [
            'BTNAccount'    =>  $BTNAccount,
            'Circuit'       =>  $Circuit,
            'Circuits'      =>  $Circuits,
            'CircuitMAC'    =>  [],
            'MACType'       =>  MACType::find($request->get('type', MACType::GENERAL)),
            '_options'      =>  [
                'MACType'   =>  MACType::getOptionsForSelect()
            ],
            'page'          =>  $page,
        ]);
    }

    /**
     * Create new Circuit MAC Note
     *
     * @param BTNAccount $BTNAccount
     * @param Circuit $Circuit
     * @param MACRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BTNAccount $BTNAccount, Circuit $Circuit, MACRequest $request)
    {
        $CircuitMAC = $Circuit->MACs()->create($request->data(false));

        if ($request->input('Note')) {

            $CircuitMAC->Notes()->create($request->only(['Note']));
        }

        return redirect()
            ->route('dashboard.inventory.circuits.mac.show', [
                $BTNAccount,
                $Circuit,
                $CircuitMAC,
                $request->get('page'),
                $request->get('mac-page'),
            ])
            ->with('notification.success', 'Successfully created new MAC Note');
    }

    /**
     * Edit single Circuit MAC Note
     *
     * @param BTNAccount $BTNAccount
     * @param Circuit $Circuit
     * @param CircuitMAC $CircuitMAC
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(BTNAccount $BTNAccount, Circuit $Circuit, CircuitMAC $CircuitMAC, Request $request, CircuitsRepository $repository)
    {
        $filters = array_merge($request->all(), ['BTNAccount' => $BTNAccount]);
        $page = $request->get('page') ?: $repository->getPage($Circuit);
        $Circuits = $repository->paginate($filters, $page, []);

        return view('dashboard.inventory.circuits.mac.edit', [
            'BTNAccount'    =>  $BTNAccount,
            'Circuit'       =>  $Circuit,
            'Circuits'      =>  $Circuits,
            'CircuitMAC'    =>  $CircuitMAC,
            'MACType'       =>  MACType::find($request->get('type', Arr::get($CircuitMAC, 'MACType', MACType::GENERAL))),
            '_options'      =>  [
                'MACType'   =>  MACType::getOptionsForSelect()
            ],
            'page'          =>  $page,
        ]);
    }

    /**
     * Update single Circuit MAC Note
     *
     * @param BTNAccount $BTNAccount
     * @param Circuit $Circuit
     * @param CircuitMAC $CircuitMAC
     * @param MACRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BTNAccount $BTNAccount, Circuit $Circuit, CircuitMAC $CircuitMAC, MACRequest $request)
    {
        $CircuitMAC->update($request->data(false));
        if ($request->input('Note')) {

            $CircuitMAC->Notes()->create($request->only(['Note']));
        }

        return redirect()
            ->route('dashboard.inventory.circuits.mac.show', [$BTNAccount, $Circuit, $CircuitMAC, $request->get('page'), $request->get('mac-page')])
            ->with('notification.success', 'Successfully updated MAC Note');
    }
}
