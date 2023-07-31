<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\FTPFolders\UpdateRequest;
use App\Models\FTPFolder;


class FTPFoldersController extends Controller
{
    public function __construct() {
        $this->middleware('auth.admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.settings.ftp-folders.index', [
            'ftpFolders' => FTPFolder::orderBy('FilePath', 'desc')->paginate(12)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param  FTPFolder $folder
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request, FTPFolder $folder)
    {
        $folder->fill($request->all())->save();

        return redirect()->back()->with('notification.success', 'Successfully updated FTP Folder.');
    }

}
