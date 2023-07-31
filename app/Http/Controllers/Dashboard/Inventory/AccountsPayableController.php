<?php

namespace App\Http\Controllers\Dashboard\Inventory;

use App\Models\InvoiceAP;
use App\Models\StateCode;
use App\Models\BTNAccount;
use App\Models\FiscalYear;
use App\Models\ProcessCode;
use Illuminate\Support\Arr;
use App\Services\InvoiceService;
use App\Services\AddressesService;
use App\Models\ProcessedMethodType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Models\InvoiceRemittanceAddress;
use App\Models\BTNAccountCarrierDetailNote;
use App\Http\Requests\Invoices\UpdateRequest;
use Symfony\Component\HttpFoundation\Request;
use App\Http\Requests\Inventory\AccountsPayable\IndexRequest;
use App\Http\Requests\Inventory\AccountsPayable\EditCarrierRequest;


/**
 * Class AccountsPayableController
 * @package App\Http\Controllers\Dashboard\Inventory
 */
class AccountsPayableController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin')->except(['index', 'show', 'document']);
        $this->middleware('auth.district')->only(['index', 'show', 'document']);
        $this->middleware('auth.model')->except(['changeBTN']);
    }

    /**
     * @param BTNAccount $BTNAccount
     * @param IndexRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(BTNAccount $BTNAccount, IndexRequest $request)
    {
        $AccountsPayable = $BTNAccount->AccountsPayable();
        $CarrierDetails = $BTNAccount->CarrierDetails;

        $AccountsPayable->orderByDesc('BillDate');

        if ($request->get('FiscalYearID', 0) != 0) {

            $FiscalYear = FiscalYear::find($request->get('FiscalYearID'));

            $AccountsPayable->whereBetween('BillDate', [
                $FiscalYear->getAttribute('BeginDate'),
                $FiscalYear->getAttribute('EndDate')
            ]);
        }

        return view('dashboard.inventory.accounts-payable.index', [
            'BTNAccount'        => $BTNAccount,
            'CarrierDetails'    => $CarrierDetails,
            'AccountsPayable'   => $AccountsPayable->paginate(),
            '_options'  =>  [
                'FiscalYearID'  =>  FiscalYear::getOptionsForSelect()
            ]
        ]);
    }

    /**
     * @param BTNAccount $BTNAccount
     * @param EditCarrierRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit_carrier(BTNAccount $BTNAccount, EditCarrierRequest $request)
    {

        $BTNAccount->CarrierDetails()->updateOrCreate([], $request->only('BillingURL', 'InvoiceAvailableDate', 'Username', 'Password', 'IsPaperless', 'PIN'));

        if ($request->has('Notes')) {
            // Delete notes that were removed from the form
            $NoteIDs = Arr::pluck($request->get('Notes'), 'BTNAccountCarrierDetailNoteID');
            foreach ($BTNAccount->CarrierDetails->Notes as $Note) {
                if (!in_array($Note->BTNAccountCarrierDetailNoteID, $NoteIDs)) {
                    $Note->delete();
                }
            }
            // Update existing notes and create new ones
            foreach ($request->input('Notes') as $Note) {
                $BTNAccount->CarrierDetails->Notes()->updateOrCreate(
                    ['BTNAccountCarrierDetailNoteID' => $Note['BTNAccountCarrierDetailNoteID']],
                    ['DetailNotes' => $Note['DetailNotes']]
                );
            }
        } else {
            // Delete all Notes if there were none in the request
            $BTNAccount->CarrierDetails->Notes()->delete();
        }

        return redirect()
            ->route('dashboard.inventory.accounts-payable.index', [
                'inventory'         =>  $BTNAccount,
            ])
            ->with('notification.success', 'The Carrier Billing Details have been updated successfully');
    }

    /**
     * Show Invoice details
     *
     * @param BTNAccount $BTNAccount
     * @param InvoiceAP $AccountPayable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(BTNAccount $BTNAccount, InvoiceAP $AccountPayable)
    {
        return view('dashboard.inventory.accounts-payable.show', [
            'BTNAccount'                =>  $BTNAccount,
            'AccountPayable'            =>  $AccountPayable,
            'LastMonthAccountPayable'   =>  $BTNAccount->AccountsPayable()->latest('BillDate')->first()
        ]);
    }

    /**
     * Download the document of an Invoice
     * If there is not document found in storage,
     * a 404 will be thrown
     *
     * @param BTNAccount $BTNAccount
     * @param InvoiceAP $AccountPayable
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function document(BTNAccount $BTNAccount, InvoiceAP $AccountPayable)
    {
        $ScannedImage = $AccountPayable->getAttribute('ScannedImage');

        //Some of the ImagePaths that are saved in the database are from the old system - this finds those and fixes the path
        if (!$ScannedImage->documentExists()) {
            if (File::exists(storage_path('app\invoices\scanned_images\\' . basename($ScannedImage->ImagePath)))) {
                $ScannedImage->ImagePath = 'app\invoices\scanned_images\\' . basename($ScannedImage->ImagePath);
                $ScannedImage->save();
            } else {
                return redirect()->back()->with('notification.error', 'Image for this invoice does not exist.');
            }
        }
        return response()->file($ScannedImage->getFullPath(), [
            'Content-Type' => 'application/pdf',  // Explicitly set the content type
            'Content-Disposition' => 'inline; filename="' . basename($ScannedImage->ImagePath) . '"'
        ]);

    }

    /**
     * Edit single Invoice
     *
     * @param BTNAccount $BTNAccount
     * @param InvoiceAP $AccountPayable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(BTNAccount $BTNAccount, InvoiceAP $AccountPayable)
    {
        if ($AccountPayable->ProcessedMethod == 4) { // If invoice is In Process
            $MostRecent = $AccountPayable->BTNAccount->MostRecentInvoice();
            if (!is_null($MostRecent)) {
                $AccountPayable->fill(array(
                    'DueDate' => $MostRecent['DueDate'] ? $MostRecent['DueDate']->addMonths(1) : null,
                    'ServiceFromDate' => $MostRecent['ServiceFromDate'] ? $MostRecent['ServiceFromDate']->addMonths(1) : null,
                    'ServiceToDate' => $MostRecent['ServiceToDate'] ? $MostRecent['ServiceToDate']->addMonths(1) : null,
                    'RemittanceAddressID' => $MostRecent['RemittanceAddressID']
                ));
            }
        }

        return view('dashboard.inventory.accounts-payable.edit', [
            'BTNAccount'        =>  $BTNAccount,
            'AccountPayable'    =>  $AccountPayable,
            'Addresses'         =>  $AccountPayable->RemittanceAddress,
            '_options'  =>  [
                'ProcessedMethod'   =>  ProcessedMethodType::getOptionsForSelect(),
                'State'             =>  StateCode::getStateDropdownOptions(),
                'processCodes'      =>  ProcessCode::all(),
            ]
        ]);
    }

    /**
     * Update single invoice
     *
     * @param BTNAccount $BTNAccount
     * @param InvoiceAP $AccountPayable
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(BTNAccount $BTNAccount, InvoiceAP $AccountPayable, UpdateRequest $request, InvoiceService $invoiceService, AddressesService $AddressesService)
    {
        $AddressesService->updateRemittanceAddress($AccountPayable, $request);
        $AccountPayable->fill(array_merge($invoiceService->cleanRequest($request->data()), ['UpdatedByUserID' => auth()->id()]))->save();

        if ($AccountPayable->ScannedImage) {
            $newImagePath = $invoiceService->updateInvoiceFile($AccountPayable->ScannedImage->ImagePath, $request->BillDate);

            if (!$newImagePath) {
                return redirect()->back()->with('notification.error', 'Invalid Bill Date. This account already has a bill with the same Bill Date.');
            }

            $AccountPayable->ScannedImage->update([
                'ProcessCode' => $request->ProcessCode,
                'ImagePath'   => $newImagePath,
                'UpdatedByUserID' => auth()->id()
            ]);
        }

        return redirect()
            ->route('dashboard.inventory.accounts-payable.show', [
                $BTNAccount,
                $AccountPayable,
            ])
            ->with('notification.success', 'The invoice has been updated successfully.');
    }

    /**
     * Display page to create a new invoice.
     *
     * @param BTNAccount $BTNAccount
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(BTNAccount $BTNAccount)
    {
        // 08-22-17 JBC - Prefilling based off most recent invoice
        $MostRecent = $BTNAccount->MostRecentInvoice();
        $AccountPayable = new InvoiceAP;
        if (!is_null($MostRecent)) {
            $AccountPayable->fill(array(
                'BillDate' => $MostRecent['BillDate'] ? $MostRecent['BillDate']->addMonths(1) : null,
                'DueDate' => $MostRecent['DueDate'] ? $MostRecent['DueDate']->addMonths(1) : null,
                'ServiceFromDate' => $MostRecent['ServiceFromDate'] ? $MostRecent['ServiceFromDate']->addMonths(1) : null,
                'ServiceToDate' => $MostRecent['ServiceToDate'] ? $MostRecent['ServiceToDate']->addMonths(1) : null,
                'RemittanceAddressID' => $MostRecent['RemittanceAddressID']
            ));
        }

        $AccountPayable->ProcessedMethod = 2; // Default to online for new bills

        return view('dashboard.inventory.accounts-payable.create', [
            'BTNAccount'        =>  $BTNAccount,
            'AccountPayable'    =>  $AccountPayable,
            'Addresses'         =>  $AccountPayable->RemittanceAddress ?: InvoiceRemittanceAddress::firstOrCreate([
                'RemittanceName' => '',
                'Address1' => '',
                'Address2' => '',
                'City' => '',
                'State' => '0',
                'Zip' => '',
                'Attention' => '',
                'VendorCode' => null,
                'UpdatedByUserID' => 0,
                'Created_at' => null,
                'Updated_at' => null,
            ]), // This should match the empty one that already exists...
            '_options'          =>  [
                'ProcessedMethod'   =>  ProcessedMethodType::getOptionsForSelect(),
                'State'             =>  StateCode::getStateDropdownOptions(),
            ]
        ]);
    }

    /**
     * Create new invoice
     *
     * @param BTNAccount $BTNAccount
     * @param UpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BTNAccount $BTNAccount, UpdateRequest $request, InvoiceService $invoiceService, AddressesService $AddressesService)
    {
        $AccountPayable = $BTNAccount->AccountsPayable()->create(array_merge(
            $invoiceService->cleanRequest($request->data()),
            ['UpdatedByUserID' => auth()->id()]
        ));
        $AddressesService->updateRemittanceAddress($AccountPayable, $request);
        return redirect()
            ->route('dashboard.inventory.accounts-payable.index', [
                'inventory'         =>  $BTNAccount,
            ])
            ->with('notification.success', 'The invoice has been created successfully.');
    }

    /**
     * Change BTN for an invoice.
     *
     * @param BTNAccount $BTNAccount
     * @param InvoiceAP $AccountPayable
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeBTN(BTNAccount $BTNAccount, InvoiceAP $AccountPayable)
    {
        $AccountPayable->setAttribute('BTNAccountID', $BTNAccount->getKey());
        $AccountPayable->save();

        return redirect()
            ->route('dashboard.inventory.accounts-payable.show', [
                'inventory'         =>  $BTNAccount,
                'accounts-payable'  =>  $AccountPayable
            ])
            ->with('notification.success', 'The invoice has been moved successfully.');
    }

    /**
     * Mark single Account Payable as deleted one.
     *
     * @param BTNAccount $BTNAccount
     * @param Circuit $Circuit
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(BTNAccount $BTNAccount, InvoiceAP $AccountPayable, InvoiceService $InvoiceService)
    {

        $InvoiceService->init();

        $InvoiceService->deleteInvoice($AccountPayable);

        return redirect()
            ->route('dashboard.inventory.accounts-payable.index', [
                'inventory'         =>  $BTNAccount,
            ])
            ->with('notification.success', 'The invoice was successfully deleted.');
    }
}
