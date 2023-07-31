<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Features\StoreRequest;
use App\Http\Requests\Settings\Features\UpdateRequest;
use App\Models\Category;
use App\Models\FeatureType;
use App\Services\Settings\FeaturesService;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class FeaturesController extends Controller
{
    public function __construct()
    {
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
        $query = FeatureType::where('IsActive', true)->orderBy('FeatureName', 'asc');
        $selectedCategory = $request->get('category', null);

        if ($selectedCategory !== null) {
            $query->where('CategoryID', $selectedCategory);
        }

        return view('dashboard.settings.features.index', [
            'featureTypes' => $query->paginate(12),
            'categories' => Category::where('IsActive', true)->whereHas('FeatureTypes', function ($query) {
                $query->where('IsActive', true);
            })->get(),
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
        return view('dashboard.settings.features.create', [
            'categories' => Category::where('IsActive', true)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @param FeaturesService $featuresService
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, FeaturesService $featuresService)
    {
        $featuresService->store($request->all());

        $request->session()->flash('notification.success', 'Feature information was successfully saved.');

        return new Response(['message' => 'Feature information was successfully saved.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  FeatureType $featureType
     * @return \Illuminate\Http\Response
     */
    public function edit(FeatureType $featureType)
    {
        return view('dashboard.settings.features.edit', [
            'featureType' => $featureType,
            'categories' => Category::where('IsActive', true)->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param  FeatureType $featureType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FeatureType $featureType)
    {
        $featureData = $this->validate($request, [
            'FeatureName' => ['required', Rule::unique((new FeatureType())->getTable())->where('IsActive', true)->ignore($featureType->FeatureType, 'FeatureType')],
            'CategoryID' => ['required'],
            'FeatureCode' => ['required', Rule::unique((new FeatureType())->getTable())->where('IsActive', true)->ignore($featureType->FeatureType, 'FeatureType')]
        ]);
        $featureType->fill($featureData)->save();

        $request->session()->flash('notification.success', 'Feature information was successfully changed.');

        return new Response(['message' => 'Feature information was successfully changed.']);
    }

    /**
     * Mark specified resource as inactive.
     *
     * @param  FeatureType $featureType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(FeatureType $featureType)
    {
        $featureType->IsActive = false;
        $featureType->save();

        return redirect()->back()
            ->with('notification.success', 'Successfully deactivated the Feature.');
    }
}
