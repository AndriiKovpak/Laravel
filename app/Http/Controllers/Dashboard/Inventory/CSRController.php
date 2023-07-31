<?php

namespace App\Http\Controllers\Dashboard\Inventory;

use App\Models\BTNAccount;
use Illuminate\Http\Request;
use App\Models\BTNAccountCSR;
use App\Models\BTNAccountCSRFile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Inventory\CSR\StoreRequest;

/**
 * Class CSRController
 * @package App\Http\Controllers\Dashboard\Inventory
 */
class CSRController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin')->except(['index', 'show', 'downloadCSR']);
        $this->middleware('auth.district')->only(['index', 'show', 'downloadCSR']);
        $this->middleware('auth.model');
    }

    /**
     * Display all the CSR of a BTNAccount
     *
     * @param BTNAccount $BTNAccount
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(BTNAccount $BTNAccount, Request $request)
    {
        return view('dashboard.inventory.csr.index', [
            'BTNAccount' => $BTNAccount,
            'CSRs' => $this->getPaginatedCSRs($BTNAccount, $request->input('search')),
        ]);
    }

    /**
     * Download a CSR
     *
     * @param BTNAccount $BTNAccount
     * @param BTNAccountCSR $BTNAccountCSR
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function show(BTNAccount $BTNAccount, BTNAccountCSR $BTNAccountCSR)
    {
        //CSR uploaded in new system
        if (File::exists($BTNAccountCSR->getFullPath())) {
            return response()->download($BTNAccountCSR->getFullPath(), basename($BTNAccountCSR->getFullPath()));
            //legacy CSR fle
        } else if (File::exists(storage_path('app\\CSR\\' . basename($BTNAccountCSR->getFullPath())))) {
            return response()->download(storage_path('app\\CSR\\' . basename($BTNAccountCSR->getFullPath())));
        }

        return redirect()->back()->with('notification.error', 'CSR file could not be found');
    }

    /**
     * Display page to edit a CSR
     *
     * @param BTNAccount $BTNAccount
     * @param BTNAccountCSR $BTNAccountCSR
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(BTNAccount $BTNAccount, BTNAccountCSR $BTNAccountCSR, Request $request)
    {
        return view('dashboard.inventory.csr.create-edit', [
            'BTNAccount'        =>  $BTNAccount,
            'BTNAccountCSR'     =>  $BTNAccountCSR,
            'BTNAccountCSRs'    =>  $this->getPaginatedCSRs($BTNAccount, $request->input('search')),
        ]);
    }

    /**
     * Update single BTNAccountCSR
     *
     * @param BTNAccount $BTNAccount
     * @param BTNAccountCSR $BTNAccountCSR
     * @param StoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BTNAccount $BTNAccount, BTNAccountCSR $BTNAccountCSR, StoreRequest $request)
    {
        $BTNAccountCSR->update($request->data(false));

        if ($request->hasFile('File')) {
            if ($BTNAccountCSR->FilePath) {
                $fileName = 'CSR_' . $BTNAccount->BTN . '_' . basename($BTNAccountCSR->FilePath);
            } else {
                $fileName = 'CSR_' . $BTNAccount->BTN . '_' . uniqid() . '.' . ($request->file('File')->getClientOriginalExtension() ?: $request->file('File')->guessExtension());
            }

            $file = storage_path('app\\CSR\\');

            $request->file('File')->move($file, $fileName);

            $BTNAccountCSR->FilePath = 'app\\CSR\\' . $fileName;
            $BTNAccountCSR->save();
        }

        return redirect()
            ->route('dashboard.inventory.csr.edit', array_merge([
                $BTNAccount,
                $BTNAccountCSR,
            ], $request->only(['search', 'page'])))
            ->with('notification.success', 'Successfully updated CSR.');
    }

    /**
     * Display page to create new CSR
     *
     * @param BTNAccount $BTNAccount
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(BTNAccount $BTNAccount, Request $request)
    {
        return view('dashboard.inventory.csr.create-edit', [
            'BTNAccount'        =>  $BTNAccount,
            'BTNAccountCSR'     =>  [],
            'BTNAccountCSRs'    =>  $this->getPaginatedCSRs($BTNAccount, $request->input('search')),
        ]);
    }

    /**
     * @param BTNAccount $BTNAccount
     * @param StoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BTNAccount $BTNAccount, StoreRequest $request)
    {
        $BTNAccountCSR = $BTNAccount->CSRs()->create($request->data());
        if ($request->hasFile('File')) {
            $fileName = 'CSR_' . $BTNAccount->BTN . '_' . uniqid() . '.' . ($request->file('File')->getClientOriginalExtension() ?: $request->file('File')->guessExtension());
            $file = storage_path('app\\CSR\\');

            $request->file('File')->move($file, $fileName);

            $BTNAccountCSR->FilePath = 'app\\CSR\\' . $fileName;
            $BTNAccountCSR->save();
        }

        return redirect()
            ->route('dashboard.inventory.csr.edit', array_merge([
                'inventory' =>  $BTNAccount,
                'csr'       =>  $BTNAccountCSR,
            ], $request->only(['search', 'page'])))
            ->with('notification.success', 'Successfully created new CSR');
    }

    /**
     * Download single file of a CSR
     *
     * @param BTNAccount $BTNAccount
     * @param BTNAccountCSR $BTNAccountCSR
     * @param BTNAccountCSRFile $BTNAccountCSRFile
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadCSR(BTNAccount $BTNAccount, BTNAccountCSR $BTNAccountCSR)
    {
        //CSR uploaded in new system

        if (File::exists($BTNAccountCSR->getFullPath())) {
            return response()->download($BTNAccountCSR->getFullPath(), basename($BTNAccountCSR->getFullPath()));
            //legacy CSR fle
        } else if (File::exists(storage_path('app\\CSR\\' . basename($BTNAccountCSR->getFullPath())))) {
            return response()->download(storage_path('app\\CSR\\' . basename($BTNAccountCSR->getFullPath())));
        }

        return redirect()->back()->with('notification.error', 'CSR file could not be found');
    }

    /**
     * Delete single CSR
     *
     * @param BTNAccount $BTNAccount
     * @param BTNAccountCSR $BTNAccountCSR
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(BTNAccount $BTNAccount, BTNAccountCSR $BTNAccountCSR, Request $request)
    {
        if ($BTNAccountCSR->FilePath && File::exists(storage_path('app\\CSR\\' . basename($BTNAccountCSR->FilePath)))) {
            Storage::move(
                'CSR\\' . basename($BTNAccountCSR->FilePath),
                'CSR\\Deleted\\' . time() . '_' . basename($BTNAccountCSR->FilePath)
            );
        }
        $BTNAccountCSR->delete();

        return redirect()
            ->route('dashboard.inventory.csr.index', array_merge([
                'inventory' =>  $BTNAccount,
            ], $request->only(['search', 'page'])))
            ->with('notification.success', 'Successfully deleted CSR');
    }

    /**
     * Get paginated CSRs sorted alphabetically by AccountNum
     *
     * It didn't seem worth breaking this into a repository
     *
     * @param BTNAccount $BTNAccount
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    private function getPaginatedCSRs(BTNAccount $BTNAccount, $search = '')
    {
        $query = $BTNAccount->CSRs();

        // Circuit search inside inventory
        if (!empty($search)) {
            // It would be faster to clean in PHP, but using SQL makes sure it is cleaned consistently.
            $search = DB::selectOne('select dbo.fnCleanString(?) as CleanString', [$search])->CleanString;
            $search = '%' . $search . '%';

            $query->where(function ($query) use ($search) {
                $query->whereRaw('dbo.fnCleanString(BTNAccountCSRs.AccountNum) LIKE ?', [$search]);
            });
        }

        return $query
            ->orderByRaw("CASE WHEN NULLIF(AccountNum, '') IS NULL THEN 1 ELSE 0 END") // NULL and '' last
            ->orderByRaw("NULLIF(dbo.fnCleanString(AccountNum), '') asc") // Alphabetical order, grouping NULL and ''
            ->orderBy('PrintedDate', 'desc') // Most recent
            ->paginate();
    }
}
