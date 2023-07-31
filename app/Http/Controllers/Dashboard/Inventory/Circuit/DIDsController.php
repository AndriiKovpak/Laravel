<?php

namespace App\Http\Controllers\Dashboard\Inventory\Circuit;

use App\Models\Circuit;
use App\Models\BTNAccount;
use App\Models\CircuitDID;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\Circuits\DIDRequest;
use App\Repositories\CircuitsRepository;
use  Illuminate\Support\Facades\DB;
use App\Models\Util;

use Illuminate\Http\Request;

/**
 * Class DIDsController
 * @package App\Http\Controllers\Dashboard\Inventory\Circuit
 */
class DIDsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin')->except(['index']);
        $this->middleware('auth.district')->only(['index']);
        $this->middleware('auth.model');
    }

    /**
     * Display DIDs of a Circuit
     *
     * @param BTNAccount $BTNAccount
     * @param Circuit $Circuit
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(BTNAccount $BTNAccount, Circuit $Circuit, Request $request, CircuitsRepository $repository)
    {
        $filters = array_merge($request->all(), ['BTNAccount' => $BTNAccount]);
        $page = $request->get('page') ?: $repository->getPage($Circuit);
        $Circuits = $repository->paginate($filters, $page, []);

        $DIDs = $Circuit->DIDs()->orderBy('DID', 'asc');

        if ($request->has('DID')) {

            $search = DB::selectOne('select dbo.fnCleanString(?) as CleanString', [$request->get('DID')])->CleanString;

            $DIDs->whereRaw("CircuitDIDs.DIDSearch LIKE ?", ['%' . $search . '%']);
        }

        $DIDsPaginate = $DIDs->paginate(15);
        // Show last page if trying to show page after last page (like when it was deleted)
        if ($DIDsPaginate->lastPage() < request('did-page')) {
            $DIDsPaginate = $DIDs->paginate(null, null, 'did-page', $DIDsPaginate->lastPage() ?: 1);
        }

        // return redirect to circuit.show. will remove index function in the future.
        Util::log("did.index was called. please review why it is called", true, true);
         return redirect()
         ->route('dashboard.inventory.circuits.show', [
             $BTNAccount,
             $Circuit,
             'page'          =>  $page,
         ]);

        return view('dashboard.inventory.circuits.show', [
            'BTNAccount'    =>  $BTNAccount,
            'Circuit'       =>  $Circuit,
            'Circuits'      =>  $Circuits,
            'DIDs'          =>  $DIDsPaginate,
            'page'          =>  $page,
        ]);
    }

    /**
     * Display form to create new DID
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

        return view('dashboard.inventory.circuits.dids.create', [
            'BTNAccount'    =>  $BTNAccount,
            'Circuit'       =>  $Circuit,
            'Circuits'      =>  $Circuits,
            'CircuitDID'    =>  [],
            '_options'      =>  [
                'Type'      =>  [
                    'single'    =>  'Single DID',
                    'range'     =>  'DID Range'
                ]
            ],
            'page'          =>  $page,
        ]);
    }

    /**
     * Edit single DID
     *
     * @param BTNAccount $BTNAccount
     * @param Circuit $Circuit
     * @param CircuitDID $CircuitDID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(BTNAccount $BTNAccount, Circuit $Circuit, CircuitDID $CircuitDID, Request $request, CircuitsRepository $repository)
    {
        $filters = array_merge($request->all(), ['BTNAccount' => $BTNAccount]);
        $page = $request->get('page') ?: $repository->getPage($Circuit);
        $Circuits = $repository->paginate($filters, $page, []);

        return view('dashboard.inventory.circuits.dids.edit', [
            'BTNAccount'    =>  $BTNAccount,
            'Circuit'       =>  $Circuit,
            'Circuits'      =>  $Circuits,
            'CircuitDID'    =>  $CircuitDID,
            'page'          =>  $page,
        ]);
    }

    /**
     * Update single DID
     *
     * @param BTNAccount $BTNAccount
     * @param Circuit $Circuit
     * @param CircuitDID $CircuitDID
     * @param DIDRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BTNAccount $BTNAccount, Circuit $Circuit, CircuitDID $CircuitDID, DIDRequest $request)
    {
        $CircuitDID->update($request->data(false));

        return redirect()
            ->route('dashboard.inventory.circuits.show', [
                'inventory' =>  $BTNAccount,
                'circuit'   =>  $Circuit,
                'page'      =>  $request->get('page'),
                'search'    =>  $request->get('search'),
                'DID'       =>  $request->get('DID-search'),
                'did-page'  =>  $request->get('did-page'),
            ])
            ->with('notification.success', 'Successfully updated DID#' . $CircuitDID->getAttribute('DID'));
    }

    /**
     * Create new DID
     *
     * @param BTNAccount $BTNAccount
     * @param Circuit $Circuit
     * @param DIDRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BTNAccount $BTNAccount, Circuit $Circuit, DIDRequest $request)
    {
        if ('range' == $request->get('Type')) {

            app('db')->transaction(function () use ($Circuit, $request) {

                for ($did = $request->get('DIDFrom'); $did <= $request->get('DIDTo'); $did++) {
                    $Circuit->DIDs()->updateOrCreate([
                        'DID'       =>  $request->get('DIDPrefix') . '-' . str_pad($did, 4, '0', STR_PAD_LEFT),
                    ], [
                        'DIDNote'   =>  $request->get('DIDNote')
                    ]);
                }
            });
        } else {
            $Circuit->DIDs()->create($request->only(['DID', 'DIDNote']));
        }

        return redirect()
            ->route('dashboard.inventory.circuits.dids.create', [
                'inventory' =>  $BTNAccount,
                'circuit'   =>  $Circuit,
                'page'      =>  $request->get('page'),
                'search'    =>  $request->get('search'),
                'DID'       =>  $request->get('DID-search'),
                'did-page'  =>  $request->get('did-page')
            ])
            ->with('notification.success', 'Successfully created new DID');
    }

    /**
     * Delete DID('s) of of a Circuit
     *
     * @param BTNAccount $BTNAccount
     * @param Circuit $Circuit
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(BTNAccount $BTNAccount, Circuit $Circuit, Request $request)
    {
        if ($request->get('Type', 'array') == 'range') {

            $this->validate($request, [
                'DIDPrefix' =>  ['required', 'regex:/^[0-9]{3}-[0-9]{3}$/'],
                'DIDFrom'   =>  ['required', 'regex:/^[0-9]{4}$/'],
                'DIDTo'     =>  ['required', 'regex:/^[0-9]{4}$/'],
            ], [
                'DIDPrefix.regex'   =>  'The :attribute must be a six-digit number in the format XXX-XXX.',
                'DIDFrom.regex' =>  'The :attribute must be a four-digit number.',
                'DIDTo.regex'   =>  'The :attribute must be a four-digit number.',
            ]);

            $DIDRange = [
                $request->get('DIDPrefix') . '-' .  $request->get('DIDFrom'),
                $request->get('DIDPrefix') . '-' .  $request->get('DIDTo'),
            ];
            /*
             * https://laravel.com/docs/5.4/eloquent#deleting-models
             * When executing a mass delete statement via Eloquent, the deleting and deleted model events will not be
             * fired for the deleted models. This is because the models are never actually retrieved when executing the
             * delete statement.
             */
            // The mass delete statement is much faster, but it could cause problems if we use the deleting and deleted model events
            $Circuit->DIDs()->whereBetween('DID', $DIDRange)->delete();
        } else {
            // FIXME: This is probably OK because only Admins can get here, but the mass destroy does not verify these CircuitDIDs belong to this Circuit
            CircuitDID::destroy($request->get('DID'));
        }

        return redirect()
            ->route('dashboard.inventory.circuits.show', [
                'inventory' =>  $BTNAccount,
                'circuit'   =>  $Circuit,
                'page'      =>  $request->get('page'),
                'did-page'  =>  $request->get('did-page')
            ])
            ->with('notification.success', 'Selected DID(s) were successfully deleted.');
    }
}
