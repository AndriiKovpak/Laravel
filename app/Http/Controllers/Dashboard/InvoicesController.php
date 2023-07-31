<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Carrier;
use App\Models\InvoiceAP;
use App\Models\StateCode;
use App\Models\BTNAccount;
use App\Models\FiscalYear;
use App\Models\ProcessCode;
use App\Models\ScannedImage;
use Illuminate\Http\Request;
use App\Models\ProcessedType;
use App\Services\InvoiceService;
use App\Services\AddressesService;
use App\Models\ProcessedMethodType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Repositories\InvoicesRepository;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\Invoices\UpdateRequest;
use App\Http\Requests\Invoices\editFileRequest;
use Illuminate\Support\Facades\Auth;

class InvoicesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin')->except(['index', 'show', 'scannedImages']);
        $this->middleware('auth.district')->only(['show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Services\InvoiceService $invoiceService
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, InvoicesRepository $invoicesRepository)
    {
        if ($request->get('newSearch')) {
            $request->session()->forget('invoicesIndexRequest');

            $request->session()->put('invoicesIndexRequest', [
                'datecheck' => '1',
                'batchcheck' => '1',
            ]);
        }

        if (!empty($request->except(['newSearch']))) {
            $request->session()->put('invoicesIndexRequest', $request->except(['newSearch']));
        }
        return view(
            'dashboard.invoices.index',
            [
                'divisionDistricts'     => $request->user()->DivisionDistricts,
                'carriers'              => Carrier::getOptionsForSelect(),
                'fiscalYears'           => FiscalYear::all(),
                'processedTypes'        => ProcessedType::where('IsActive', 1)->orderBy('ProcessedTypeName', 'asc')->get(),
                'processedMethodTypes'  => ProcessedMethodType::where('IsActive', 1)->orderBy('ProcessedMethodName', 'asc')->get(),
                'processCodes'          => ProcessCode::all(),
                'invoices'              => $invoicesRepository->paginate(
                    $request->session()->get('invoicesIndexRequest'),
                    $request->session()->get('invoicesIndexRequest.page', 1),
                    []
                ),
                'sortFields'            =>
                [
                    'CarrierID'  => 'Carrier',
                    'BTN'        => 'BTN',
                    'AccountNum' => 'Account',
                    'BillDate'   => 'Invoice Date',
                    'BatchDate'  => 'Batch Date',
                ],
                'params' => $request->session()->get('invoicesIndexRequest')
            ]
        );
    }

    /**
     * Display a listing of the pending invoices.
     *
     * @param  \App\Services\InvoiceService $invoiceService
     * @return \Illuminate\Http\Response
     */
    public function pending(Request $request, InvoiceService $invoiceService)
    {
        $invoices = array_merge($invoiceService->getPendingInvoices(), $invoiceService->getPendingInvoicesNoConcat());
        return view(
            'dashboard.invoices.pending',
            [
                'invoices'      => $invoices,
                'ScanErrors'    => $request->session()->get('ScanErrors'),
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $BTNAccount = BTNAccount::find($request->BTNAccountID);
        $scannedImage = ScannedImage::find($request->ScannedImageID);

        return view('dashboard.invoices.create', [
            'BTNAccount'            =>  $BTNAccount,
            'scannedImage'          =>  $scannedImage,
            'invoice'               =>  null,
            'Addresses'             =>  null,
            '_options'            => [
                'ProcessedMethod' =>  ProcessedMethodType::getOptionsForSelect(),
                'State'           =>  StateCode::getStateDropdownOptions(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param InvoiceService $invoiceService
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, InvoiceService $invoiceService)
    {
        InvoiceAP::create($invoiceService->cleanRequest($request->data()));

        return redirect(route('dashboard.invoices.index'))->with('notification.success', 'The invoice has been successfully added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  InvoiceAP $invoice
     * @param InvoiceService $invoiceService
     * @return \Illuminate\Http\Response
     */
    public function show(InvoiceAP $invoice, InvoiceService $invoiceService)
    {
        return view('dashboard.invoices.show', [
            'invoice'           => $invoice,
            'lastMonthInvoice'  => $invoiceService->getLastMonthInvoice($invoice->BTNAccountID),
        ]);
    }

    /**
     * Display the Scanned Images file.
     *
     * @param  string $fileName
     * @param InvoiceService $invoiceService
     * @return \Illuminate\Http\Response
     */
    public function scannedImages(ScannedImage $ScannedImage)
    {
        //Some of the ImagePaths that are saved in the database are from the old system - this finds those and fixes the path
        if (!$ScannedImage->documentExists()) {
            if (File::exists(storage_path('app\invoices\scanned_images\\' . basename($ScannedImage->ImagePath)))) {
                $ScannedImage->ImagePath = 'app\invoices\scanned_images\\' . basename($ScannedImage->ImagePath);
                $ScannedImage->save();
            } else {
                return redirect()->back()->with('notification.error', 'Image for this invoice does not exist.');
            }
        }
        return response()
            ->file($ScannedImage->getFullPath(), ['Content-Type', 'application/pdf']);
    }

    /**
     * Display the pdf file.
     *
     * @param  string $fileName
     * @param InvoiceService $invoiceService
     * @return \Illuminate\Http\Response
     */
    public function viewPdf($fileName, InvoiceService $invoiceService)
    {
        $file = $invoiceService->getPDF($fileName);
        if (!$file) {
            return redirect(route('dashboard.invoices.index'))->with('notification.error', 'Could not find invoice.');
        }
        $response = Response::make($file, 200);

        $response->header('Content-Type', 'application/pdf');

        return $response;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  InvoiceAP $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(InvoiceAP $invoice, Request $request)
    {
        if ($invoice->ProcessedMethod == 4) { // If invoice is In Process
            $MostRecent = $invoice->BTNAccount->MostRecentInvoice();
            if (!is_null($MostRecent)) {
                $invoice->fill(array(
                    'ProcessedMethod' => 1, // Change it to Paper
                    'DueDate' => $MostRecent['DueDate'] ? $MostRecent['DueDate']->addMonths(1) : null,
                    'ServiceFromDate' => $MostRecent['ServiceFromDate'] ? $MostRecent['ServiceFromDate']->addMonths(1) : null,
                    'ServiceToDate' => $MostRecent['ServiceToDate'] ? $MostRecent['ServiceToDate']->addMonths(1) : null,
                    'RemittanceAddressID' => $MostRecent['RemittanceAddressID']
                ));
            }
        }

        return view('dashboard.invoices.edit', [
            'invoice'               =>  $invoice,
            'Addresses'             =>  $invoice->RemittanceAddress,
            'new'                   =>  $request->new,
            '_options'  =>  [
                'ProcessedMethod' =>  ProcessedMethodType::getOptionsForSelect(),
                'State'             =>  StateCode::getStateDropdownOptions(),
                'processCodes'      =>  ProcessCode::all(),
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  InvoiceAP $invoice
     * @param  InvoiceService $invoiceService
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, InvoiceAP $invoice, InvoiceService $invoiceService, AddressesService $AddressesService)
    {
        $AddressesService->updateRemittanceAddress($invoice, $request);
        $invoice->fill($invoiceService->cleanRequest($request->data()))->save();
        $newImagePath = $invoiceService->updateInvoiceFile($invoice->ScannedImage->ImagePath, $request->BillDate);

        if (!$newImagePath) {
            return redirect()->back()->with('notification.error', 'Invalid Bill Date. This account already has a bill with the same Bill Date.');
        }

        $invoice->ScannedImage->update([
            'ProcessCode' => $request->ProcessCode,
            'ImagePath'   => $newImagePath,
            'UpdatedByUserID' => Auth::id(),
        ]);
        if ($request->new) {
            return redirect()
                ->route('dashboard.inventory.accounts-payable.index', [
                    'inventory' => $invoice->BTNAccount,
                ])
                ->with('notification.success', 'The invoice has been successfully created.');
        } else {
            return redirect()
                ->route('dashboard.invoices.show', [
                    'invoice'           => $invoice,
                    'lastMonthInvoice'  => $invoiceService->getLastMonthInvoice($invoice->BTNAccountID),
                ])
                ->with('notification.success', 'The invoice has been successfully updated.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($APID, InvoiceService $InvoiceService)
    {
        $InvoiceService->init();

        if ($InvoiceService->deleteInvoice(InvoiceAP::findOrFail($APID))) {
            return redirect(route('dashboard.invoices.index'))->with('notification.success', 'The invoice was successfully deleted.');
        } else {
            return redirect()->back()->with('notification.error', 'There was an error deleting this invoice.');
        }
    }

    /**
     * Remove the specified file from storage folder.
     *
     * @param  string $fileName
     * @param InvoiceService $invoiceService
     * @return \Illuminate\Http\Response
     */
    public function destroyPending($fileName, InvoiceService $invoiceService)
    {
        $invoiceService->init();

        if ($invoiceService->deletePending($fileName)) {
            return redirect()->back()->with('notification.success', 'The pending invoice was successfully deleted.');
        } else {
            return redirect()->back()->with('notification.error', 'There was an error deleting ' . $fileName . '.');
        }
    }

    /*
     * Scan Pending Invoices.
     */
    public function scan(Request $request, InvoiceService $invoiceService)
    {

        $errors = $invoiceService->runArtisan(false);
        $request->session()->put('ScanErrors', $errors);

        return redirect()->back()->with('notification.success', 'All pending invoices have been scanned.');
    }

    /*
     * Rename a pending invoice pdf
     */
    public function editPending(editFileRequest $request, InvoiceService $invoiceService)
    {

        $invoiceService->init();

        if (mb_strtolower(substr($request->FileName, -4)) != '.pdf') {
            $request->FileName .= '.pdf';
        }

        if (mb_strtolower($request->FileName) == mb_strtolower($request->OldFileName)) {
            return redirect()->back();
        }

        //Check that the file is unique
        foreach (array_merge($invoiceService->getPendingInvoices(), $invoiceService->getPendingInvoicesNoConcat()) as $file) {
            if ($request->FileName == $file->getFilename()) {
                return redirect()
                    ->back()
                    ->withErrors(['FileName' => 'There is already a pending invoice with that name.']);
            }
        }

        if ($invoiceService->renameInvoice($request)) {
            return redirect()->back()->with('notification.success', 'File ' . $request->OldFileName . ' has been renamed to ' . $request->FileName);
        } else {
            return redirect()
                ->back()
                ->with('notification.success', 'There was an error renaming the pending invoice.');
        }
    }
}
