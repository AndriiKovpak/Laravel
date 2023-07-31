<?php

namespace App\Http\Controllers;

use App\Models\Carrier;

/**
 * Class IndexController
 * @package App\Http\Controllers
 */
class IndexController extends Controller
{
    /**
     * Display index (landing) page
     * with login form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('index.index', [
            '_options' => [
                'CarrierID'  => Carrier::getOptionsForSelect(),
            ],
        ]);
    }
}
