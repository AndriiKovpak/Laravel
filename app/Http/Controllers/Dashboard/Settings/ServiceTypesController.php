<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ServiceTypes\StoreRequest;
use App\Http\Requests\Settings\ServiceTypes\UpdateRequest;
use App\Models\Category;
use App\Models\ServiceType;
use App\Services\Settings\ServiceTypesService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class ServiceTypesController extends Controller
{
    public function __construct() {
        $this->middleware('auth.admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = ServiceType::query();
        $selectedCategory = $request->get('category', null);

        if ($selectedCategory !== null) {
            $query->where('Category', $selectedCategory);
        }

        $query->where('IsActive', true)->orderBy('ServiceTypeName', 'asc');

        return view('dashboard.settings.service-types.index', [
            'serviceTypes' => $query->paginate(12),
            'categories' => Category::where('IsActive', true)->get(),
            'selectedCategory' => $selectedCategory
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.settings.service-types.create', [
            'categories' => Category::where('IsActive', true)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @param ServiceTypesService $serviceTypesService
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, ServiceTypesService $serviceTypesService)
    {
        $serviceTypesService->store([
            'ServiceTypeName' => $request->ServiceTypeName,
            'Category' => $request->Category
        ]);

        $request->session()->flash('notification.success', 'Service Type information was successfully saved.');

        return new Response(['message' => 'Service Type information was successfully saved.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  ServiceType $serviceType
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceType $serviceType)
    {
        return view('dashboard.settings.service-types.edit', [
            'serviceType' => $serviceType,
            'categories' => Category::where('IsActive', true)->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param  ServiceType $serviceType
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, ServiceType $serviceType)
    {
        $serviceType->ServiceTypeName = $request->ServiceTypeName;
        $serviceType->Category = $request->Category;
        $serviceType->save();

        $request->session()->flash('notification.success', 'Service Type information was successfully changed.');

        return new Response(['message' => 'Service Type information was successfully changed.']);
    }

    /**
     * Mark specified resource as inactive.
     *
     * @param ServiceType $serviceType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ServiceType $serviceType)
    {
        $serviceType->IsActive = false;
        $serviceType->save();

        return redirect()->back()
            ->with('notification.success', 'Successfully deactivated the Service Type.');
    }
}
