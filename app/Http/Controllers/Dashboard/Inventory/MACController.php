<?php

namespace App\Http\Controllers\Dashboard\Inventory;

use App\Models\MACType;
use App\Models\BTNAccount;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\BTNAccountMAC;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\MAC\StoreRequest;

/**
 * Class MACController
 * @package App\Http\Controllers\Dashboard\Inventory
 */
class MACController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin')->except(['index', 'show']);
        $this->middleware('auth.district')->only(['index', 'show']);
        $this->middleware('auth.model');
    }

    /**
     * @param BTNAccount $BTNAccount
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(BTNAccount $BTNAccount)
    {
        return view('dashboard.inventory.mac.index', [
            'BTNAccount'    =>  $BTNAccount,
            'MACs'          =>  $BTNAccount->MACs()->latest()->paginate()
        ]);
    }

    /**
     * Show single MAC
     *
     * @param BTNAccount $BTNAccount
     * @param BTNAccountMAC $BTNAccountMAC
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(BTNAccount $BTNAccount, BTNAccountMAC $BTNAccountMAC, Request $request)
    {
        return view('dashboard.inventory.mac.show', [
            'BTNAccount'    =>  $BTNAccount,
            'BTNAccountMAC'    =>  $BTNAccountMAC,
            'MACType'       =>  $BTNAccountMAC['Type'] ? $BTNAccountMAC['Type'] : MACType::find($request->get('type', MACType::GENERAL)),
        ]);
    }

    /**
     * Edit single MAC
     *
     * @param BTNAccount $BTNAccount
     * @param BTNAccountMAC $BTNAccountMAC
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(BTNAccount $BTNAccount, BTNAccountMAC $BTNAccountMAC, Request $request)
    {
        return view('dashboard.inventory.mac.edit', [
            'BTNAccount'    =>  $BTNAccount,
            'BTNAccountMAC'    =>  $BTNAccountMAC,
            'MACType'       =>  MACType::find($request->get('type', Arr::get($BTNAccountMAC, 'MACType', MACType::GENERAL))),
            '_options'      =>  [
                'MACType'   =>  MACType::getOptionsForSelect()
            ]
        ]);
    }

    /**
     * Update single MAC
     *
     * @param BTNAccount $BTNAccount
     * @param BTNAccountMAC $BTNAccountMAC
     * @param StoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BTNAccount $BTNAccount, BTNAccountMAC $BTNAccountMAC, StoreRequest $request)
    {
        $BTNAccountMAC->update($request->data(false));

        if ($request->has('Note')) {

            $BTNAccountMAC->Notes()->create($request->only(['Note']));
        }

        return redirect()
            ->route('dashboard.inventory.mac.show', [
                $BTNAccount,
                $BTNAccountMAC,
            ])
            ->with('notification.success', 'Successfully updated MAC.');
    }

    /**
     * Display page to create new MAC
     *
     * @param BTNAccount $BTNAccount
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(BTNAccount $BTNAccount, Request $request)
    {
        return view('dashboard.inventory.mac.create', [
            'BTNAccount'    =>  $BTNAccount,
            'BTNAccountMAC'    =>  [],
            'MACType'       =>  MACType::find($request->get('type', MACType::GENERAL)),
            '_options'      =>  [
                'MACType'   =>  MACType::getOptionsForSelect()
            ]
        ]);
    }

    /**
     * Create new MAC
     *
     * @param BTNAccount $BTNAccount
     * @param StoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BTNAccount $BTNAccount, StoreRequest $request)
    {
        $BTNAccountMAC = $BTNAccount->MACs()->create($request->data(false));

        if ($request->has('Note')) {
            $BTNAccountMAC->Notes()->create($request->only(['Note']));
        }

        return redirect()
            ->route('dashboard.inventory.mac.show', [
                $BTNAccount,
                $BTNAccountMAC,
            ])
            ->with('notification.success', 'Successfully created MAC.');
    }
}
