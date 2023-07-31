<?php

namespace App\Http\Controllers\Dashboard\Settings;
use App\Http\Controllers\Controller;

/**
 * Class IndexController
 * @package App\Http\Controllers\Dashboard\Settings
 */
class IndexController extends Controller
{
    public function __construct() {
        $this->middleware('auth.admin');
    }

    /**
     * Display index (settings) page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('dashboard.settings.index');
    }
}