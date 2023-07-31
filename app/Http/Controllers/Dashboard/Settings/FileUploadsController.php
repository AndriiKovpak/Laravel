<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Exceptions\FileUploadException;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\FileUploads\StoreRequest;

use App\Services\Settings\FileUploads\CircuitFeaturesFileUploadsService;
use App\Services\Settings\FileUploads\NewCircuitsFileUploadsService;
use App\Services\Settings\FileUploads\UpdateCircuitsFileUploadsService;

/**
 * Class FileUploadsController
 *
 * @package App\Http\Controllers\Dashboard\Settings
 */
class FileUploadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    /**
     * Display index page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('dashboard.settings.file-upload.index');
    }

    /**
     * Process file and display index page.
     *
     * @param StoreRequest $request
     *
     */
    public function store(StoreRequest $request)
    {
        set_time_limit(1200);

        $delimiter = '';
        switch ($request->input('delimiter')) {
            case 'Comma Delimited':
                $delimiter = ',';
                break;
            case 'Tab Delimited':
                $delimiter = "\t";
                break;
            case 'Pipe Delimited':
                $delimiter = '|';
                break;
        }

        $fileUploadService = null;
        switch ($request->input('file-type')) {
            case 'New Circuits':
                $fileUploadService = new NewCircuitsFileUploadsService();
                break;
            case 'Update Circuits':
                $fileUploadService = new UpdateCircuitsFileUploadsService();
                break;
            case 'Circuit Features':
                $fileUploadService = new CircuitFeaturesFileUploadsService();
                break;
        }

        try {
            $fileUploadService->processFile($request->file('file-upload'), $delimiter);
        } catch (FileUploadException $e) {
            return back()
                ->withInput($request->input())
                ->withErrors($e->getMessages());
        }

        return view('dashboard.settings.file-upload.index')
            ->with([
                'successMessage' => 'File "' . $request->file('file-upload')->getClientOriginalName() . '" uploaded successfully.',
            ]);
    }
}
