<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Util;
use App\Models\FTPFolder;
use App\Models\InvoiceAP;
use App\Models\BTNAccount;
use App\Models\FTPSetting;
use Illuminate\Support\Arr;
use App\Models\ScannedImage;
use App\Models\DivisionDistrict;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\DB;
use League\Flysystem\MountManager;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use League\Flysystem\FileAttributes;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FilesystemException;
use League\Flysystem\UnableToCheckExistence;
use League\Flysystem\PhpseclibV3\SftpAdapter;
use League\Flysystem\PhpseclibV3\SftpConnectionProvider;

class InvoiceService
{
    /**
     * Path to the source folder for invoices.
     *
     * @var string
     */
    private $sourceInvoices;

    /**
     * Path to the source folder for receiving reports.
     *
     * @var string
     */
    private $sourceRR;

    /**
     * Path to the destination folder for finance.
     *
     * @var string
     */
    private $destFinance;

    /**
     * Path to the destination folder for COMS.
     *
     * @var string
     */
    private $destCOMS;

    /*
     * Turn on or off status messages
     */

    private $messagesOn;

    /*
     * Errors array to return on the Scan Pending Invoices button
     */
    private $errors = [];

    /*
     * BTNStatus Types that are allowed to process
     */
    private $allowToProcess = [1, 3, 7, 8, 9, 10, 11, 12, 13];

    /**
     * return League\Flysystem\MountManager;
     */
    protected $sftp_manager;

    // TODO: These paths should be set in config
    public function getPendingInvoices()
    {
        return File::allFiles(storage_path('app\\invoices\\pending_images\\pending\\'));
    }

    public function getPendingInvoicesNoConcat()
    {
        return File::allFiles(storage_path('app\\invoices\\pending_images\\pending_noconcat\\'));
    }

    public function getReceivingReports($format = false)
    {
        $receivingReports = File::allFiles(storage_path('app\\invoices\\receiving_reports\\pending\\'));
        if ($format) {
            $reports = [];
            foreach ($receivingReports as $receivingReport) {
                $reports[] = [
                    'strippedName'  => strtolower(trim(DB::selectOne('select dbo.fnCleanString(?) as ReceivingReportName', [$receivingReport->getFilename()])->ReceivingReportName)),
                    'fileName'      => $receivingReport->getFilename(),
                ];
            }
            return $reports;
        }
        return $receivingReports;
    }

    public function deleteReceivingReports()
    {
        foreach ($this->getReceivingReports() as $file) {
            Storage::delete('invoices\\receiving_reports\\pending\\' . $file->getFileName());
        }
    }

    public function clearPendingInvoicesFolder()
    {
        foreach (File::allFiles(storage_path('app\\invoices\\pending_images\\pending\\')) as $file) {
            Storage::delete('invoices\\pending_images\\pending\\' . $file->getFileName());
        }
    }


    public function deletePending($fileName)
    {

        /** test methods */
        /**
            try {
                $contents = $this->sftp_manager->listContents('TestServer://home/ubuntu/storage');
                foreach ($contents as $c) {
                    $path = $c->path();
                    if ($c instanceof \League\Flysystem\FileAttributes) {
                        // handle the file
                        dd($c, $path);
                    } elseif ($c instanceof \League\Flysystem\DirectoryAttributes) {
                        // handle the directory
                        // dd($c, $path);
                    }
                }
            } catch (FilesystemException $e) {
                //throw $th;
            }
            dd('finished test');
         */
        /** test methods */

        if (Storage::exists('invoices\\pending_images\\pending\\' . $fileName)) {
            try {
                Storage::delete('invoices\\pending_images\\pending\\' . $fileName);
                // Flysystem::connection('InvoicePreConcat')->delete($fileName);
                $this->sftp_manager->delete('InvoicePreConcat://' . $fileName);
            } catch (FilesystemException | UnableToDeleteFile $e) {
                Util::log('Error: ' . $e);
                return false;
            }
            return true;
        } else if (Storage::exists('invoices\\pending_images\\pending_noconcat\\' . $fileName)) {
            try {
                Storage::delete('invoices\\pending_images\\pending_noconcat\\' . $fileName);
                if ($this->sftp_manager->has('InvoiceNoConcat://' . $fileName)) {
                    //Flysystem::connection('InvoiceNoConcat')->delete($fileName);
                    $this->sftp_manager->delete('InvoiceNoConcat://' . $fileName);
                }
                // should remove files in finance folder(acefcmsoftp) if the same file in the pending(non_concat) folder
                if ($this->sftp_manager->has('Finance://' . $fileName)) {
                    $this->sftp_manager->delete('Finance://' . $fileName);
                }
            } catch (FilesystemException | UnableToDeleteFile | UnableToCheckExistence $e) {
                Util::log('Error: ' . $e);
                return false;
            }
            return true;
        }

        return false;
    }

    public function renameInvoice($request)
    {
        if (Storage::exists('invoices\\pending_images\\pending\\' . $request->OldFileName)) {
            try {
                Storage::move(
                    'invoices\\pending_images\\pending\\' . $request->OldFileName,
                    'invoices\\pending_images\\pending\\' . $request->FileName
                );
                // Flysystem::connection('InvoicePreConcat')->rename(
                //     $request->OldFileName,
                //     $request->FileName
                // );
                $this->sftp_manager->move(
                    'InvoicePreConcat://' . $request->OldFileName,
                    'InvoicePreConcat://' . $request->FileName
                );
            } catch (FilesystemException | UnableToCheckExistence $e) {
                Util::log('Error: ' . $e);
                return false;
            }
            return true;
        } else if (Storage::exists('invoices\\pending_images\\pending_noconcat\\' . $request->OldFileName)) {
            try {
                Storage::move(
                    'invoices\\pending_images\\pending_noconcat\\' . $request->OldFileName,
                    'invoices\\pending_images\\pending_noconcat\\' . $request->FileName
                );
                // if(Flysystem::connection('InvoiceNoConcat')->has($request->OldFileName)) {
                //     Flysystem::connection('InvoiceNoConcat')->rename(
                //         $request->OldFileName,
                //         $request->FileName
                //     );
                // }
                $this->sftp_manager->move(
                    'InvoiceNoConcat://' . $request->OldFileName,
                    'InvoiceNoConcat://' . $request->FileName
                );
            } catch (FilesystemException | UnableToCheckExistence $e) {
                Util::log('Error: ' . $e);
                return false;
            }
            return true;
        }

        return false;
    }

    public function getPDF($fileName)
    {
        if (Storage::exists('invoices\\pending_images\\pending\\' . $fileName)) {
            return Storage::get('invoices\\pending_images\\pending\\' . $fileName);
        } else if (Storage::exists('invoices\\pending_images\\pending_noconcat\\' . $fileName)) {
            return Storage::get('invoices\\pending_images\\pending_noconcat\\' . $fileName);
        }

        return false;
    }

    public function getLastMonthInvoice($btnAccountID)
    {
        $btnAccount = BTNAccount::find($btnAccountID);

        $startDate = new Carbon('first day of last month');
        $endDate = new Carbon('last day of last month');

        return $btnAccount->AccountsPayable()
            ->where('BillDate', '>=', $startDate)
            ->where('BillDate', '<=', $endDate)
            ->where('ProcessedMethod', '<>', 5)
            ->orderBy('BillDate', 'desc')
            ->first();
    }

    public function cleanRequest($request)
    {
        $request['IsFinalBill'] = (Arr::get($request, 'IsFinalBill') == 1);

        $request['RemittanceAddressIDSearch'] = null;

        return $request;
    }

    public function updateInvoiceFile($ScannedInvoicePath, $BillDate)
    {

        //Get original date
        $originalDate = array_values(array_slice(explode('_', basename($ScannedInvoicePath)), -1))[0];
        //Format date value to mmddyy
        $date = date('mdy', strtotime($BillDate)) . '.pdf';

        if (strtolower($originalDate) == strtolower($date)) {
            return $ScannedInvoicePath;
        }

        //Get the scanned image directory
        $scannedImagePath = implode('\\', array_slice(explode('\\', $ScannedInvoicePath), 0, -1));
        //Filename without the date
        $fileName = implode("_", array_slice(explode('_', basename($ScannedInvoicePath)), 0, -1));
        //New file name
        $newFile = $fileName . '_' . $date;

        //rename the scanned image file
        if (!Storage::exists('app\\invoices\\scanned_images\\' . $newFile)) {
            Storage::move(
                'invoices\\scanned_images\\' . basename($ScannedInvoicePath),
                'invoices\\scanned_images\\' . $newFile
            );
        } else {
            return false;
        }

        //New scanned image file path
        return $scannedImagePath . '\\' . $newFile;
    }

    public function deleteInvoice($AccountPayable)
    {
        // Soft delete because of foreign key constraint
        $AccountPayable->setAttribute('ProcessedMethod', 5);
        $AccountPayable->save();
        return true;
    }

    //This is currently run every 5 minutes on the server, or it can be manually run on the pending invoices page by the "Scan Pending Images" button
    public function runArtisan($messagesOn = true)
    {
        $this->messagesOn = $messagesOn;
        if (!$this->init()) {
            return;
        }
        $this->scanFTPFolders(); // create or update FTPFolder based on Databank SFTP server.
        $this->downloadScheduledFTPFolders(); // downloads only pdf files on Databank SFTP server which exists on `FTPFolder table` and pending status = 2
        $this->downloadPendingFromSTFTPWithConcat(); // downloads pdf files into /pending_images/pending, /receiving_reports/pending
        $this->downloadPendingFromSTFTPWithoutConcat();
        $this->downloadPendingFromSTFTPFinance();
        $this->processPending();
        if (!$messagesOn) {
            if ($this->errors) {
                return $this->errors;
            }
        }
        return true;
    }

    /*
     * Matches pending invoices with db invoices, finds corresponding Receiving Report, downgrades them so they can be merged,
     * merges them, then moves them to the destScannedImg folder.
     *
     * sourceInvoices: pending invoices are stored here before they have been merged and scanned
     * sourceRR: where Receiving Reports from ftp site are moved
     * destFinance: merged invoices moved here to then be sent to ftp site
     * destScannedImg: completed invoices that have been merged and scanned to db invoice
     *
     */
    public function init()
    {

        $this->sourceInvoices =  'storage\\app\\invoices\\pending_images\\'; //env('SOURCE_FOLDER_INVOICES');
        $this->sourceRR       =  'storage\\app\\invoices\\receiving_reports\\'; //env('SOURCE_FOLDER_RECEIVING_REPORTS');
        //$this->destFinance    =  'storage\app\invoices\finance\\';//env('DESTINATION_FOLDER_FINANCE');
        $this->destScannedImg =  'storage\\app\\invoices\\scanned_images\\'; // env('DESTINATION_FOLDER_COMS');

        //make sure invoice directory exists
        if (file_exists('..\\' . $this->sourceInvoices . 'pending\\')) {
            $this->sourceInvoices = '..\\' . $this->sourceInvoices;
        } else if (!file_exists($this->sourceInvoices . 'pending\\')) {
            Util::log("Source folder for invoices does not exist.", true, true);
            return false;
        }

        //make sure receiving report directory exists
        if (file_exists('..\\' . $this->sourceRR . 'pending\\')) {
            $this->sourceRR = '..\\' . $this->sourceRR;
        } else if (!file_exists($this->sourceRR . 'pending\\')) {
            Util::log("Source folder for receiving reports does not exist.", true, true);
            return false;
        }

        // Make sure completed directories exist
        if (file_exists('..\\' . $this->destScannedImg)) {
            $this->destScannedImg = '..\\' . $this->destScannedImg;
        } else if (!file_exists($this->destScannedImg)) {
            Util::log("Destination folder for scanned invoices does not exist.", true, true);
            return false;
        }
        /*
        //make sure finance directory exists
        if(file_exists('..\\' . $this->destFinance)){
            $this->destFinance = '..\\' . $this->destFinance;
        }else if(!file_exists($this->destFinance)){
            Util::log("Destination folder for finance does not exist.", true, true);
            return false;
        }
        */

        $SMTPServerList = [];
        $sites = FTPSetting::all();
        foreach ($sites as $site) {
            // Set up connection
            $adapter = new SftpAdapter(
                new SftpConnectionProvider(
                    $site['FTPHost'],           // host (required)
                    $site['FTPUsername'],       // username (required)
                    $site['FTPPassword'],       // password (optional, default: null) set to null if privateKey is used
                ),
                '/'                             // root path (required)
            );

            $SMTPServerList[$site['Description']] = new Filesystem($adapter);
        }
        $this->sftp_manager = new MountManager($SMTPServerList);
        return true;
    }

    /**
     * TODO Implement SubAccount use case
     */
    private function processPending()
    {
        $files_noconcat = $this->getPendingInvoicesNoConcat();
        foreach ($files_noconcat as $file) {
            $file->Concat = false;
        }

        $files = $this->getPendingInvoices();
        foreach ($files as $file) {
            $file->Concat = true;
        }

        $files = array_merge($files, $files_noconcat);

        $receivingReports = $this->getReceivingReports(true);
        $completedFile = '';
        foreach ($files as $file) {
            $scannedImage = new ScannedImage();
            $time = time();

            $fileName = $file->getFilename();

            $this->displayMessage('Processing ' . $fileName . " - " . ($file->Concat ? 'with' : 'without') . " concatenation...");

            if ($this->isLegacyFile($fileName)) {
                // LEGACY PROCESSED TYPE
                $scannedImage->ProcessedType = 0;

                $fileName = $this->removeLegacyPrefix($fileName);
            } else {
                $scannedImage->ProcessedType = 1;
            }

            // $scannedImage->UpdatedByUserID = Auth::id(); // can't know who is logged in bc it is handled by scheduler

            $parts = explode('_', $fileName);
            $divisionDistrictCode = null;
            $btnAccountNum = null;
            $billDate = null;

            if (count($parts) === 2) {
                $divisionDistrictCode = 'UNKNOWN';
                $btnAccountNum = $parts[0];
                $billDate = $parts[1];
            } else if (count($parts) === 3) {
                $divisionDistrictCode = $parts[0];
                $btnAccountNum = $parts[1];
                $billDate = $parts[2];
            } else {
                $this->errorMessages("Invoice does not have a valid file name format.", $file->getFilename());
                $this->displayMessage("Invoice does not have a valid file name format: " . $file->getFilename() . ".\n");
                Util::log("Invoice does not have a valid format file name: " . $file->getFilename() . ".", false);
                continue;
            }

            if ($divisionDistrictCode !== 'UNKNOWN') {
                if (!$this->isValidDivisionDistrict($divisionDistrictCode)) {
                    $this->errorMessages("Invoice does not have a valid District.", $file->getFilename());
                    $this->displayMessage("Invoice does not have a valid District: " . $file->getFilename() . ".\n");
                    Util::log("Invoice does not have a valid District: " . $file->getFilename() . ".", false);
                    continue;
                }
            }

            if (!$this->isValidAccountNum($btnAccountNum)) {
                $this->errorMessages("Invoice does not have a valid account number. No account exists with that account number.", $file->getFilename());
                $this->displayMessage("Invoice does not have a valid account number. No account exists with that account number: " . $file->getFilename() . " (Account Number: " . $btnAccountNum . ").");
                Util::log("Invoice does not have a valid account number. No account exists with that account number: " . $file->getFilename() . ".", false);
                continue;
            } else if ($this->isValidAccountNum($btnAccountNum) > 1) {
                $this->errorMessages("Invoice does not have a valid account number. Duplicate accounts exists with that account number.", $file->getFilename());
                $this->displayMessage("Invoice does not have a valid account number. Duplicate accounts exists with that account number: " . $file->getFilename() . " (Account Number: " . $btnAccountNum . ").");
                Util::log("Invoice does not have a valid account number. Duplicate accounts exists with that account number: " . $file->getFilename() . ".", false);
                continue;
            } else {
                $scannedImage->BTNAccountID = $this->getBtnAccountID($btnAccountNum);
                try {
                    $scannedImage->BillDate = $this->getBillDate($billDate);
                } catch (\Exception $e) {
                    $this->errorMessages("Invalid date format.", $file->getFilename());
                    $this->displayMessage("Invalid date format.", $file->getFilename());
                    Util::log("Invalid date format [" . $file->getFilename() . "]. Error: {$e->getMessage()}", true);
                    continue;
                }
                $scannedImage->BatchDate = new Carbon();
                $FiscalYear = \App\Models\FiscalYear::where('BeginDate', '<=',  $scannedImage->BillDate)
                    ->where('EndDate', '>=',  $scannedImage->BillDate)
                    ->get();
            }

            //Find Invoice that matches to PDF
            $r = InvoiceAP::query()
                ->join('BTNAccounts', 'InvoicesAccountsPayable.BTNAccountID', '=', 'BTNAccounts.BTNAccountID')
                ->leftJoin('ScannedImages', 'InvoicesAccountsPayable.ScannedImageID', '=', 'ScannedImages.ScannedImageID')
                ->leftJoin('DivisionDistricts', 'BTNAccounts.DivisionDistrictID', '=', 'DivisionDistricts.DivisionDistrictID')
                ->where('InvoicesAccountsPayable.BTNAccountID', '=', $scannedImage->BTNAccountID)
                ->where('InvoicesAccountsPayable.BillDate', '=', $scannedImage->BillDate)
                ->where('InvoicesAccountsPayable.ProcessedMethod', '<>', 5)
                ->selectRaw('InvoicesAccountsPayable.InvoiceAPID, DivisionDistricts.DivisionDistrictCode , InvoicesAccountsPayable.ScannedImageID')
                ->get();
            if ($file->Concat) { // Only search for ReceivingReport if concatenation is needed

                if (!count($r)) {
                    $this->errorMessages("Invoice does not exist for file.", $file->getFilename());
                    $this->displayMessage("Invoice does not exist for file: " . $file->getFilename() . ". Skipping.");
                    Util::log("Invoice does not exist for file: " . $file->getFilename() . ".", false);
                    continue;
                } else {
                    if (count($r) > 1) {
                        $this->errorMessages("BTN Account already has a saved invoice for this date. Possible duplicate.", $file->getFilename());
                        $this->displayMessage('BTN Account already has a saved invoice for this date. Possible duplicate.');
                        Util::log('BTN Account already has a saved invoice for this date. Possible duplicate. [' . $file->getFilename() . '].', false);
                        continue;
                    }
                    $this->displayMessage("Invoice exists for file: " . $file->getFilename() . ".");
                    $Invoice = InvoiceAP::find($r[0]['InvoiceAPID']);
                    if ($r[0]['ScannedImageID']) {
                        $this->errorMessages("BTN Account already has a saved invoice for this date. Possible duplicate.", $file->getFilename());
                        $this->displayMessage('BTN Account already has a saved invoice for this date. Possible duplicate.');
                        Util::log('BTN Account already has a saved invoice for this date. Possible duplicate. [' . $file->getFilename() . '].', false);
                        continue;
                    }
                    //Get Division District from Invoice if isset
                    if ($r[0]['DivisionDistrictCode']) {
                        $divisionDistrictCode = $r[0]['DivisionDistrictCode'];
                    } else {
                        $divisionDistrictCode = '';
                    }
                }

                try {
                    // Loop through SourceFolderReceivingReports to find match
                    // TODO: Might be more efficient to just check if file exists

                    $recRptFilename = '';

                    $InvoiceFileName = strtolower(trim(DB::selectOne('select dbo.fnCleanString(?) as InvoiceFileName', [$file->getFilename()])->InvoiceFileName));

                    //Checks current receiving_reports/pending folder for matches
                    foreach ($receivingReports as $report) { //Util::log('Invoice Account #: ' . strtolower(trim($file->getFilename())) . ' RR Account #: ' . strtolower(trim($report->getFilename())), true, true);
                        if ($InvoiceFileName == $report['strippedName']) {
                            $this->displayMessage('Invoice Account #: ' . strtolower(trim($InvoiceFileName)) . ' RR Account #: ' . strtolower(trim($report['fileName'])));
                            $recRptFilename = $report['fileName'];
                        }
                    }

                    //Could not find Matching receiving report
                    if (strlen(trim($recRptFilename)) == 0) {
                        $this->errorMessages("Invoice is missing receiving report.", $file->getFilename());
                        $this->displayMessage($file->getFilename() . " is missing receiving report.  Skipping.");
                        Util::log($file->getFilename() . " is missing receiving report.  Skipping.", false);
                        continue;
                    } else {
                        $this->displayMessage("Receiving Report found. File name: " . $recRptFilename);
                    }
                } catch (\Exception $e) {
                    $this->displayMessage("Error finding receiving report for invoice [" . $file->getFilename() . "]. Error: {$e->getMessage()}\n");
                    Util::log("Error finding receiving report for invoice [" . $file->getFilename() . "]. Error: {$e->getMessage()}", true);
                    continue;
                }

                try {

                    // Downgrade PDFs to the same version and then merge them
                    $gsPath = env('GHOST_SCRIPT_PATH') ?? "C:\\PROGRA~1\\gs\\gs10.00.0\\bin\\gswin64c.exe ";
                    if(!$gsPath) {
                        $this->displayMessage("Gs path is ====================>:", $gsPath);
                        Util::log("GS pathe isnot exist, Please check gs is installed and set env path correctly", true, true);
                    }
                    exec($gsPath . ' -sDEVICE=pdfwrite -dCompatibilityLevel=1.6 -dNOPAUSE -dSAFER -dNOPAGEPROMPT -dQUIET -dBATCH -sOutputFile='
                        . '"' . $this->sourceInvoices . 'completed\\' . $file->getFilename() . '" '
                        . '"' . $this->sourceInvoices . 'pending\\' . $file->getFilename() . '" '
                        . '"' . $this->sourceRR . 'pending\\' . $recRptFilename . '" ',
                        $output,
                        $error
                    );

                    if ($error > 0) {
                        Util::log("Error downgrading pending invoice. [" . $file->getFilename() . "]. Error: {" . implode(PHP_EOL, $output) . "}", true, true);
                        continue;
                    }
                } catch (\Exception $e) {
                    $this->displayMessage("Error merging PDFs for invoice [" . $file->getFilename() . "]. \n Error: {$e->getMessage()}");
                    Util::log("Error merging PDFs for invoice [" . $file->getFilename() . "]. Error: {$e->getMessage()}", true, true);
                    continue;
                }

                try {
                    $scannedImage->save();
                    //New file name
                    $completedFile =  $scannedImage->ScannedImageID . '~' . implode('_', [$divisionDistrictCode, $btnAccountNum, $billDate]);

                    // Moving new pdf to scanned_images folder
                    $this->displayMessage("Copying " . $this->sourceInvoices . 'completed\\' . $file->getFilename() . "\r");
                    $this->displayMessage(" .. to " . 'invoices\\scanned_images\\' . $completedFile . "\r");

                    Storage::move('invoices\\pending_images\\completed\\' . $file->getFilename(), 'invoices\scanned_images\\' . $completedFile);

                    //Move original invoice to completed folders
                    Storage::move('invoices\\pending_images\\pending\\' . $file->getFilename(), 'invoices\pending_images\completed\\' . $time . '_' .  $file->getFilename());

                    //Upload to Finance SFTP Site
                    try {
                        // Flysystem::connection('Combined')->putStream(
                        //     $file->getFilename(),
                        //     fopen(storage_path("app\\invoices\\scanned_images\\" . $completedFile), 'r')
                        // );
                        $this->sftp_manager->writeStream(
                            'Combined://' . $file->getFilename(),
                            fopen(storage_path("app\\invoices\\scanned_images\\" . $completedFile), 'r')
                        );

                        $this->displayMessage($file->getFilename() . ' successfully uploaded to Finance SFTP Site.');
                    } catch (FilesystemException | UnableToCheckExistence $e) {
                        $this->displayMessage("Error uploading to Combined [" . $file->getFilename() . "] Error: {$e->getMessage()}");
                        Util::log("Error uploading to Finance [" . $file->getFilename() . "] Error: {$e->getMessage()}", true, true);
                    }

                    //Move invoice and Receiving Report to completed folders on SFTP sites
                    try {
                        // Flysystem::connection('InvoicePreConcat')->rename(
                        //     $file->getFilename(),
                        //     "completed\\" . $time . '_' . $file->getFilename()
                        // );
                        $this->sftp_manager->move(
                            'InvoicePreConcat://' . $file->getFilename(),
                            'InvoicePreConcat://completed/' . $time . '_' . $file->getFilename()
                        );

                        $this->displayMessage('InvoicePreConcat [' . $file->getFilename() . '] successfully moved to completed on SFTP site.');
                    } catch (FilesystemException | UnableToCheckExistence $e) {
                        $this->displayMessage("Error moving Invoice from SFTP [" . $file->getFilename() . "] Error: {$e->getMessage()}");
                        Util::log("Error moving Invoice from SFTP [" . $file->getFilename() . "] Error: {$e->getMessage()}", true, true);
                    }

                    try {
                        // Flysystem::connection('RRPreConcat')->rename(
                        //     $recRptFilename,
                        //     "completed\\" . $time . '_' .  $recRptFilename
                        // );
                        $this->sftp_manager->move(
                            'RRPreConcat://' . $recRptFilename,
                            'RRPreConcat://completed/' . $time . '_' . $recRptFilename
                        );

                        $this->displayMessage('RRPreConcat [' . $recRptFilename . '] successfully moved to completed on SFTP site.');
                    } catch (FilesystemException | UnableToCheckExistence $e) {
                        $this->displayMessage("Error moving Receiving Report from SFTP [" . $recRptFilename . "] Error: {$e->getMessage()}");
                        Util::log("Error moving Receiving Report from SFTP [" . $recRptFilename . "] Error: {$e->getMessage()}", true, true);
                    }
                } catch (\Exception $e) {
                    $this->displayMessage("Error copying file for invoice [" . $file->getFilename() . "] Error: {$e->getMessage()}");
                    Util::log("Error copying file for invoice [" . $file->getFilename() . "] Error: {$e->getMessage()}", true, true);
                }

                try {
                    //Remove files from pending folders
                    Storage::delete('invoices\\pending_images\\downgraded\\' . $file->getFilename());
                    Storage::delete('invoices\\receiving_reports\\pending\\' . $file->getFilename());
                    //TODO: delete from Receiving Report FTP Site
                } catch (\Exception $e) {
                    Util::log("Error deleting temp file for invoice [" . $file->getFilename() . "] Error: {$e->getMessage()}", true, true);
                }
                // Invoices without concatenation
            } else {
                if (count($r) >= 1) {
                    $this->errorMessages("BTN Account already has a saved invoice for this date. Possible duplicate.", $file->getFilename());
                    $this->displayMessage('BTN Account already has a saved invoice for this date. Possible duplicate.');
                    Util::log('BTN Account already has a saved invoice for this date. Possible duplicate. [' . $file->getFilename() . '].', false);
                    continue;
                } else {
                    $scannedImage->save();
                    $completedFile =  $scannedImage->ScannedImageID . '~' . implode('_', [$divisionDistrictCode, $btnAccountNum, $billDate]);

                    //Create new Accounts Payable record and save the scanned image with it.
                    $Invoice = new InvoiceAP();
                    $Invoice->BTNAccountID = $scannedImage->BTNAccountID;
                    $Invoice->BillDate = $this->getBillDate($billDate);
                    $Invoice->ProcessedMethod = '4';
                }

                try {
                    // Add PDF to website
                    Storage::copy('invoices\\pending_images\\pending_noconcat\\' . $file->getFilename(), 'invoices\\scanned_images\\' . $completedFile);
                    // Move original invoice to completed folders
                    Storage::move('invoices\\pending_images\\pending_noconcat\\' . $file->getFilename(), 'invoices\\pending_images\\completed\\' . $time . '_' . $file->getFilename());
                    $this->displayMessage('Invoice file successfully saved to folder.');
                    // if (Flysystem::connection('InvoiceNoConcat')->has($file->getFilename())) {
                    if ($this->sftp_manager->has('InvoiceNoConcat://' . $file->getFilename())) {
                        //Move invoice on the FTP site
                        try {
                            // Flysystem::connection('InvoiceNoConcat')->rename(
                            //     $file->getFilename(),
                            //     "completed\\" . $time . '_' . $completedFile
                            // );
                            $this->sftp_manager->move(
                                'InvoiceNoConcat://' . $file->getFilename(),
                                'InvoiceNoConcat://completed/' . $time . '_' . $completedFile
                            );

                            $this->displayMessage('Moved file [' . $time . '_' . $file->getFilename()  . '] to completed folder on SFTP InvoiceNoConcat ');
                        } catch (FilesystemException | UnableToCheckExistence $e) {
                            $this->displayMessage("Error moving PDFs for invoice on SFTP site InvoiceNoConcat [" . $file->getFilename() . "]  Error: {$e->getMessage()}");
                            Util::log("Error moving PDFs for invoice on SFTP site InvoiceNoConcat [" . $file->getFilename() . "] Error: {$e->getMessage()}", true, true);
                        }
                    }
                } catch (\Exception $e) {

                    $this->displayMessage("Error moving PDFs for invoice [" . $file->getFilename() . "] Error: {$e->getMessage()}");
                    Util::log("Error moving PDFs for invoice [" . $file->getFilename() . "] Error: {$e->getMessage()}", true, true);
                    continue;
                }
            }

            try {
                $scannedImage->BTNAccountID = $Invoice->BTNAccountID;
                $scannedImage->ImagePath = 'storage\\app\\invoices\\scanned_images\\' . $completedFile;
                $scannedImage->FiscalYearID = $FiscalYear[0]['FiscalYearID'];
                $scannedImage->save();

                $Invoice->ScannedImageID = $scannedImage->ScannedImageID;
                $Invoice->save();
                $this->displayMessage('Invoice path saved to database.');
            } catch (\Exception $e) {
                $this->displayMessage("Error updating ImagePath in database for invoice [" . $file->getFilename() . "] " .  " \n Error: {$e->getMessage()}");
                Util::log("Error updating ImagePath in database for invoice [" . $file->getFilename() . "] " . "Error: {$e->getMessage()}", true, true);
                continue;
            }
        }
        $this->checkReceivingReports();
    }

    /**
     * 1. check pending or processing rows in `FTPFolder` table, if exists return();
     * 2. scan folders on Databank SFTP server,
     * 3. loop -> folder:
     * 4. check if folder exist in `FTPFolder` table and status is enterprise or not exist folder
     * 5. check if folder has one or more pdf files, and then count of pdf files only.
     * 6. create new FTPFolder in case not exist folder
     * 7. update existing FTPFolder
     */
    private function scanFTPFolders()
    {
        $this->displayMessage("scanFTPFolders\r");
        try {
            // Make sure no folders are scheduled or processing
            // FTPFolderStatus == [2, 3] is [Scheduled or Processing]
            $scheduled = FTPFolder::pending()->get();
            $this->displayMessage(count($scheduled) . " pending folders on Databank.");
            if (count($scheduled) > 0) {
                $this->displayMessage("FTP Folders still in scheduled status.  Skipping folder update until folder is processed.");
                Util::log("FTP Folders still in scheduled status.  Skipping folder update until folder is processed.", false);
                return;
            }

            // List directories, loop through
            // $contents = Flysystem::connection('Databank')->listContents();
            $contents = $this->sftp_manager->listContents('Databank://');

            foreach ($contents as $c) {
                $existingFolder = FTPFolder::where(
                    'FilePath',
                    $this->stripeServerUrl($c->path())
                )->first();
                if ($c instanceof DirectoryAttributes && (!$existingFolder || $existingFolder->FTPFolderStatus == 4)) {
                    // $subcontents = Flysystem::connection('Databank')->listContents($c['basename']);
                    $subcontents = $this->sftp_manager->listContents($c->path());

                    $count = 0;
                    foreach ($subcontents as $sc) {
                        if ($sc instanceof FileAttributes) {
                            $path = $sc->path();
                            // $ext = strtolower(substr($sc['basename'], strlen($sc['basename']) - 3));
                            $ext = strtolower(substr($path, strlen($path) - 3));

                            if ($ext == 'pdf')
                                $count++;
                        }
                    }
                    if ($count > 0) {
                        $folder = $existingFolder ?: new FTPFolder();
                        $folder['FilePath'] = $this->stripeServerUrl($c->path());
                        $folder['FTPFolderStatus'] = 4;
                        $folder['ImageCount'] = $count;
                        $folder->save();

                        $this->displayMessage("SAVED[{$this->stripeServerUrl($c->path())}] with $count images.");
                    }
                }
            }
        } catch (FilesystemException $e) {
            Util::log("Error while attempting to scan Databank SFTP folder structure.\nError: {$e->getMessage()}", true, true);
        }
    }

    public function downloadPendingFromSTFTPWithoutConcat()
    {
        // Fcmsoftp – (InvoiceNoConcat) - another folder where we put additional invoices – no concatenation.  The Finance center takes them.  Once they place the invoices in the folder, we want to have COMs make a copy and upload so we can FC process them.
        try {
            // $contents = Flysystem::connection('InvoiceNoConcat')->listContents();
            $contents = $this->sftp_manager->listContents('InvoiceNoConcat://');
            $count = 0;
            $file_count = 0;
            foreach ($contents as $c) {
                if ($c instanceof FileAttributes) {
                    $path = $c->path();
                    // $ext = strtolower(substr($c['basename'], strlen($c['basename']) - 3));
                    $ext = strtolower(substr($path, strlen($path) - 3));

                    if ($ext == 'pdf') {
                        $this->displayMessage($path . "... ");
                        // $data = Flysystem::connection('InvoiceNoConcat')->read($c['basename']);
                        $data = $this->sftp_manager->read($path);

                        $handle = fopen(storage_path("app\\invoices\\pending_images\\pending_noconcat\\" . $this->stripeServerUrl($path)), 'w');
                        fwrite($handle, $data);
                        fclose($handle);
                        $this->displayMessage(storage_path("app\\invoices\\pending_images\\pending_noconcat\\" . $this->stripeServerUrl($path) . "\r"));
                    }
                    $file_count++;
                }
                $count++;
            }
            $folder_count = $count - $file_count;
            $this->displayMessage($file_count . " files and ". $folder_count ." folders on Fcmsoftp.");
        } catch (\Exception $e) {
            Util::log("Error while attempting to download invoices from STI SFTP site. \nFolder: Fcmsoftp\nError: {$e->getMessage()}", true, true);
        }
    }

    public function downloadPendingFromSTFTPFinance()
    {
        // Fcmsoftp – (InvoiceNoConcat) - another folder where we put additional invoices – no concatenation.  The Finance center takes them.  Once they place the invoices in the folder, we want to have COMs make a copy and upload so we can FC process them.
        try {
            $contents = $this->sftp_manager->listContents('Finance://');
            $count = 0;
            $file_count = 0;
            foreach ($contents as $c) {
                if ($c instanceof FileAttributes) {
                    $path = $c->path();
                    // $ext = strtolower(substr($c['basename'], strlen($c['basename']) - 3));
                    $ext = strtolower(substr($path, strlen($path) - 3));

                    if ($ext == 'pdf' && ! $this->isAlreadyProceed($path)) {
                        $this->displayMessage($path . "... ");
                        // $data = Flysystem::connection('InvoiceNoConcat')->read($c['basename']);
                        $data = $this->sftp_manager->read($path);

                        $handle = fopen(storage_path("app\\invoices\\pending_images\\pending_noconcat\\" . $this->stripeServerUrl($path)), 'w');
                        fwrite($handle, $data);
                        fclose($handle);
                        $this->displayMessage(storage_path("app\\invoices\\pending_images\\pending_noconcat\\" . $this->stripeServerUrl($path) . "\r"));

                        // remove this file
                        $this->sftp_manager->delete($path);
                    }
                    $file_count++;
                }
                $count++;
            }
            $folder_count = $count - $file_count;
            $this->displayMessage($file_count . " files and ". $folder_count ." folders on acefcmsoftp.");
        } catch (\Exception $e) {
            Util::log("Error while attempting to download invoices from Finance FTP(acefcmsoftp). \nFolder: Fcmsoftp\nError: {$e->getMessage()}", true, true);
        }
    }

    /**
     * 1. clear /pending_invoice/pending,
     * 2. download files in root folder in InvoicePreConcat server.
     * 3. clear /receiving_reports/pending
     * 4. download files in root folder in RRPreConcat server.
     */
    public function downloadPendingFromSTFTPWithConcat()
    {
        /*
         * Advoftpuser – (InvoicePreConcat) - where we place invoices so they can concatenate after we enter the invoice data into COMs.  We name is by account number_date.
         * Msoftpuser – (RRPreConcat) - where they place receiving reports that are merged with invoices. Names same as above.
         * Jjusaceftp – (Combined) - site where the invoices go after they concatenate – then the Finance center takes them.  Then a copy is uploaded to COMs and attached to the invoice we entered.
         */

        //Clear out pending invoices that need concatenated from our webserver
        $this->clearPendingInvoicesFolder();

        // Get pre-concat invoices
        try {
            // $contents = $this->filesystem->listContents('app')->toArray();
            $contents = $this->sftp_manager->listContents('InvoicePreConcat://');

            $file_count = 0;
            $count = 0;
            foreach ($contents as $c) {
                if ($c instanceof FileAttributes) {
                    $path = $c->path(); // not inside in folder, path will be the invoice file name.
                    $ext = strtolower(substr($path, strlen($path) - 3));
                    if ($ext == 'pdf') {
                        $this->displayMessage($this->stripeServerUrl($path) . "... ");
                        // $data = Flysystem::connection('InvoicePreConcat')->read($c['basename']);
                        $data = $this->sftp_manager->read($path);

                        $handle = fopen(storage_path("app\\invoices\\pending_images\\pending\\" . $this->stripeServerUrl($path)), 'w');
                        fwrite($handle, $data);
                        fclose($handle);
                        $this->displayMessage(storage_path("app\\invoices\\pending_images\\pending\\" .  $this->stripeServerUrl($path) . "\r"));
                    }
                    $file_count++;
                }
                $count++;
            }
            $folder_count = $count - $file_count;
            $this->displayMessage($file_count . " files and ". $folder_count ." folders on Advoftpuser."); // 2 permanant folders: completed and corrupted
        } catch (FilesystemException $e) {
            Util::log("Error while attempting to download invoices from STI SFTP site. \nFolder: Advoftpuser\nError: {$e->getMessage()}", true, true);
        }

        //delete pending RRs on webserver
        $this->deleteReceivingReports();

        // Get pre-concat RRs
        try {
            // $contents = Flysystem::connection('RRPreConcat')->listContents();
            $contents = $this->sftp_manager->listContents('RRPreConcat://');

            $file_count = 0;
            $count = 0;
            foreach ($contents as $c) {
                if ($c instanceof FileAttributes) {
                    $path = $c->path();
                    $ext = strtolower(substr($path, strlen($path) - 3));
                    if ($ext == 'pdf') {
                        $this->displayMessage($this->stripeServerUrl($path) . "... ");
                        // $data = Flysystem::connection('RRPreConcat')->read($c['basename']);
                        $data = $this->sftp_manager->read($path);

                        $handle = fopen(storage_path("app\\invoices\\receiving_reports\\pending\\" . $this->stripeServerUrl($path)), 'w');
                        fwrite($handle, $data);
                        fclose($handle);
                        $this->displayMessage(storage_path("app\\invoices\\receiving_reports\\pending\\" . $this->stripeServerUrl($path) . "\r"));
                    }
                    $file_count++;
                }
                $count++;
            }
            $folder_count = $count - $file_count;
            $this->displayMessage($file_count . " files and ". $folder_count ." folders on Msoftpuser.");
        } catch (\Exception $e) {
            Util::log("Error while attempting to download invoices from STI SFTP site. \nFolder: Msoftpuser\nError: {$e->getMessage()}", true, true);
        }
    }

    /**
     * 1. scan `FTPFolder` table if pending rows exists.
     * 2. set flat `processing` = 3
     * 3. loop each folder
     * 4. find all files in a folder and loop whose extension is pdf.
     * 5. downloads each file into /pending_images/pending_noconcat.
     * 6. if any error, log/mail
     */
    private function downloadScheduledFTPFolders()
    {
        try {
            $folders = FTPFolder::pending()->get();

            // Update folders to processing
            foreach ($folders as $folder) {
                $folder['FTPFolderStatus'] = 3; // TODO: Should be config settings
                $folder->save();
            }

            foreach ($folders as $folder) {
                try {
                    // $contents = Flysystem::connection('Databank')->listContents($folder['FilePath']);
                    $contents = $this->sftp_manager->listContents('Databank://' . $folder['FilePath']);

                    $count = 0;
                    foreach ($contents as $c) {
                        if ($c instanceof FileAttributes) {

                            $path = $c->path();
                            $ext = strtolower(substr($path, strlen($path) - 3));
                            if ($ext == 'pdf') {
                                // $data = Flysystem::connection('Databank')->read($folder['FilePath'] . '/' . $c['basename']);
                                $data = $this->sftp_manager->read($path);

                                $filename = preg_replace('/.+?[\/]/i', '', '' . $this->stripeServerUrl($path));
                                $this->displayMessage("downloading from Databank SFTP server " . $filename . "... ");
                                $handle = fopen(storage_path("app\\invoices\\pending_images\\pending_noconcat\\"
                                     . $filename), 'w');
                                fwrite($handle, $data);
                                fclose($handle);
                                $this->displayMessage(storage_path("app\\invoices\\pending_images\\pending_noconcat\\" . $filename . "\r"));
                            }
                        }
                        $count++;
                    }
                    if ($count > 0) {
                        $this->displayMessage($count . " files downloaded into /pending_noconcat directory successfully.");
                    }

                    // Update folder status
                    $folder['FTPFolderStatus'] = 1;
                    $folder->save();
                } catch (\Exception $e) {
                    Util::log("Error while attempting to download invoices from Databank SFTP site. \nFolder: {$folder['FilePath']}\nError: {$e->getMessage()}", true, true);
                    $folder['FTPFolderStatus'] = 5; // TODO: New value in table for errors
                    $folder->save();
                }
            }
        } catch (\Exception $e) {
            Util::log("Error while attempting to download invoices from Databank SFTP site.\nError: {$e->getMessage()}", true, true);
        }
    }

    //verify that no receiving reports remain in the SFTP site
    public function checkReceivingReports()
    {
        //Check if any receiving reports are left in the pending folder
        if (count($this->getReceivingReports()) > 0) {
            foreach ($this->getReceivingReports() as $report) {
                Util::log('Error: no matching invoice found for receiving report: ' . strtolower(trim($report->getFilename())) . '.', true, false);
                $this->displayMessage('Error: no invoice found for receiving report: ' . strtolower(trim($report->getFilename())) . '.');
            }
        }
    }

    private function isLegacyFile($fileName)
    {
        return strpos($fileName, 't_') === 0
            || strpos($fileName, 'T_') === 0
            || strpos($fileName, 'l_') === 0
            || strpos($fileName, 'L_') === 0;
    }

    private function removeLegacyPrefix($fileName)
    {
        return substr($fileName, 2);
    }

    private function isValidDivisionDistrict($divisionDistrictCode)
    {
        return DivisionDistrict::where('DivisionDistrictCode', $divisionDistrictCode)->count() === 1;
    }

    private function isValidAccountNum($btnAccountNum)
    {
        return BTNAccount::whereRaw("dbo.fnCleanString(AccountNum) = dbo.fnCleanString(?)", [$btnAccountNum])->whereIn('Status', $this->allowToProcess)->count();
    }

    private function getBtnAccountID($btnAccountNum)
    {
        return BTNAccount::whereRaw("dbo.fnCleanString(AccountNum) = dbo.fnCleanString(?)", [$btnAccountNum])->whereIn('Status', $this->allowToProcess)->first()->BTNAccountID;
    }

    private function getBillDate($billDate)
    {
        $parts = str_split($billDate, 2);
        $year = '20' . $parts[2];
        $month = $parts[0];
        $day = $parts[1];
        return Carbon::createFromDate($year, $month, $day);
    }

    //Displays status messages
    private function displayMessage($text)
    {
        if ($this->messagesOn) {
            echo $text . "\n";
        }
    }

    private function errorMessages($message, $account)
    {
        if (!$this->messagesOn) {
            $this->errors[$account] = $message;
        }
    }

    /**
     * Remove SFTP server prefix url ex: 'Databank://'
     *
     * @param  str $path: 'Databank://path/file.pdf'
     * @return str $result_str: 'path/file.pdf'
     */
    private function stripeServerUrl($path)
    {
        $pattern = '/.+?:[\/]{2}/i';
        $result_str = preg_replace($pattern, '', $path);
        return $result_str;
    }

    /**
     * Check if given string filepath has been already proceed from fcmsoftp folder
     * ex: 1689284034_254755~UNKNOWN_2400XF12S3_061023.pdf
     * @param string $path
     * @return boolean
     */
    public function isAlreadyProceed($path) {
        return count(explode('_', $path)) >= 4;
    }
}
