<?php

namespace App\Http\Controllers\Dashboard;

use Carbon\Carbon;
use App\Models\Address;
use App\Models\Carrier;
use App\Models\Circuit;
use App\Models\Category;
use App\Models\StateCode;
use App\Models\BTNAccount;
use App\Models\CircuitData;
use App\Models\FeatureType;
use App\Models\ServiceType;
use Illuminate\Support\Arr;
use App\Models\CircuitVoice;
use Illuminate\Http\Request;
use App\Models\BTNStatusType;
use App\Models\BTNAccountOrder;
use App\Models\OrderStatusType;
use App\Services\OrdersService;
use App\Models\CircuitSatellite;
use App\Models\DivisionDistrict;
use App\Models\CircuitDescription;
use App\Models\CircuitUpdateQueue;
use App\Services\AddressesService;
use Illuminate\Support\Facades\DB;
use App\Models\BTNAccountOrderFile;
use App\Http\Controllers\Controller;
use App\Services\ServiceTypesService;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Orders\StoreRequest;
use App\Http\Requests\Orders\CreateRequest;

class OrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    /**
     * @param BTNAccount $BTNAccount
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $Orders = BTNAccountOrder::select('BTNAccountOrders.*', 'Carriers.CarrierName', 'Addresses.SiteName')
        ->leftJoin('BTNAccounts', 'BTNAccounts.BTNAccountID', '=', 'BTNAccountOrders.BTNAccountID')
        ->leftJoin('Addresses', 'BTNAccounts.SiteAddressID', '=', 'Addresses.AddressId')
        ->leftJoin('Carriers', 'Carriers.CarrierID', 'BTNAccounts.CarrierID')
        ->where('OrderStatus', 1)
        ->latest()
        ->paginate(15);

        return view('dashboard.orders.index', [
            'Orders'    =>    $Orders
        ]);
    }

    /**
     * Create a new Order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        return view('dashboard.orders.create', [
            'BTNAccount'            => '',
            'BTNAccountOrder'       => '',
            'Order'                 => [],
            'request'               => $request,
            'Circuit'               => [],
            'BTNAccountID'          => '',
            'new'                   => true,
            'Addresses'             => null,
            'Category'              => Category::active()->where('CategoryID', $request->get('category'))->firstOrFail(),
            'back'                  => $request->input('from'),
            '_options'              =>  [
                'Category'              =>  Category::getOptionsForSelect(),
                'Status'                =>  BTNStatusType::getOptionsForSelect(),
                'State'                 =>  StateCode::getStateDropdownOptions(),
                'ServiceType'           =>  ServiceType::getOptionsForSelect($request->get('category')),
                'CarrierID'             =>  Carrier::getOptionsForSelect(),
                'DescriptionID'         =>  CircuitDescription::getOptionsForSelect(),
                'DivisionDistrict'      =>  DivisionDistrict::getOptionsForSelect(),
                'FeatureType'           =>  FeatureType::getOptionsForSelect($request->get('category')),
            ],
            'page'                  => $request->get('page'),
        ]);
    }

    /**
     * Store - save the newly created Order
     * @param BTNAccount $BTNAccount
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(CreateRequest $request, AddressesService $addressesService)
    {
        $CircuitID = null;
        //Create new BTN
        if ($request->BTNAccountExists == 'no' || $request->BTNAccountExists == '') {
            $BTNAccount = BTNAccount::create(array_merge($request->dataBTNAccount(), ['Status' => BTNStatusType::STATUS_INACTIVE]));
            if ($request->has('Note')) {
                $BTNAccount->Notes()->create($request->only(['Note']));
            }

            $addressesService->updateAddress($BTNAccount, $request);
            //Create new Circuit
        } else if ($request->BTNAccountExists) {
            $BTNAccount = BTNAccount::find($request->get('BTNAccountExists'));
            //Create a circuit or update existing circuit
            $CircuitData = $request->getCircuitData();
            $CircuitData['Status'] = BTNStatusType::STATUS_PENDING;
            $Circuit = $BTNAccount->Circuits()->create($CircuitData);
            $CircuitQueue = CircuitUpdateQueue::find($Circuit->CircuitID);
            $Circuit = Circuit::find($CircuitQueue->CircuitID);
            $CircuitID = $Circuit->CircuitID;
            $Circuit->CategoryData()->create($request->getCategoryData());
            $addressesService->updateCircuitAddresses($Circuit, $request);

            if ($request->has('CircuitFeatures')) {
                foreach ($request->get('CircuitFeatures') as $CircuitFeature) {
                    if (Arr::has($CircuitFeature, 'FeatureType') && Arr::has($CircuitFeature, 'FeatureCost')) {
                        $Circuit->Features()->create($CircuitFeature);
                    }
                }
            }

            if ($request->has('Notes')) {
                $Circuit->Notes()->create([
                    'Note'  =>  $request->get('Notes')
                ]);
            }
        }

        // FIXME I'm not sure 'CarrierCircuitID' => $CircuitID is right here...
        $BTNAccountOrder = BTNAccountOrder::create(
            array_merge(
                $request->dataOrder(),
                [
                    'BTNAccountID' => $BTNAccount->BTNAccountID,
                    'OrderStatus' => OrderStatusType::STATUS_PENDING,
                    'CarrierCircuitID' => $CircuitID,
                ]
            )
        );

        foreach ($request->file('OrderFiles') ?: [] as $file) {
            $newFile = uniqid() . '.' . ($file->getClientOriginalExtension() ?: $file->guessExtension());

            $file->move(
                storage_path('app\\CSR\\Pending'),
                $newFile
            );

            $BTNAccountOrder->Files()->create([
                'FilePath'      => 'app\\CSR\\Pending\\' . $newFile,
                'OriginalName'  => $file->getClientOriginalName(),
            ]);
        }

        return redirect()
            ->route('dashboard.orders.index')
            ->with('notification.success', 'New order was successfully created.');
    }

    /**
     * Display page to edit single BTN Account Order
     *
     * @param BTNAccount $BTNAccount
     * @param BTNAccountOrder $BTNAccountOrder
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(BTNAccountOrder $BTNAccountOrder, Request $request)
    {
        $BTNAccount = BTNAccount::find($BTNAccountOrder->BTNAccountID);
        $Circuit = Circuit::find($BTNAccountOrder->CarrierCircuitID);
        if ($Circuit) {
            $Category = Category::active()->where('CategoryID', Circuit::find($BTNAccountOrder->CarrierCircuitID)->Category()->get()[0]['CategoryID'])->firstOrFail();
        } else {
            $Category = null;
            $BTNAccount->BTNAccountID = '0'; // BTNAccountID will be casted into number, eny string will be 0.
        }

        $data = [
            'BTNAccount'            =>  $BTNAccount,
            'Addresses'             =>  $BTNAccount->SiteAddress,
            'Circuit'               =>  $Circuit,
            'new'                   =>  false,
            'back'                  =>  '',
            'BTNAccountID'          =>  $BTNAccount->BTNAccountID,
            'Category'              =>  $Category,
            'Order'                 =>  $BTNAccountOrder,
            'request'               =>  '',

            '_options'  =>  [
                'Category'              =>  Category::getOptionsForSelect(),
                'Status'                =>  BTNStatusType::getOptionsForSelect(),
                'State'                 =>  StateCode::getStateDropdownOptions(),
                'ServiceType'           =>  ServiceType::getOptionsForSelect($Circuit ? $Circuit['CategoryID'] : false),
                'CarrierID'             =>  Carrier::getOptionsForSelect(),
                'DescriptionID'         =>  CircuitDescription::getOptionsForSelect(),
                'DivisionDistrict'      =>  DivisionDistrict::getOptionsForSelect(),
                'FeatureType'           =>  FeatureType::getOptionsForSelect($Circuit ? $Circuit['CategoryID'] : false),
            ],
            'page'                  => $request->get('page'),
        ];
        if (isset($Circuit)) {
            // Make sure the currently selected option is in the array if it would normally be inactive, etc.
            // Note: array_merge creates new indexes if the originals were numeric
            if (isset($Circuit['Category']) && $Circuit['Category'] && !$Circuit['Category']['IsActive']) {
                $data['_options']['Category'][$Circuit['Category']['CategoryID']] = $Circuit['Category']['CategoryName'];
            }
            if ($Circuit['StatusType'] && !$Circuit['StatusType']['IsDisplay']) {
                $data['_options']['Status'][$Circuit['StatusType']['BTNStatus']] = $Circuit['StatusType']['BTNStatusName'];
            }
            if ($Circuit['Service'] && !$Circuit['Service']['IsActive']) {
                $data['_options']['ServiceType'][$Circuit['Service']['ServiceType']] = $Circuit['Service']['ServiceTypeName'];
            }
            if ($Circuit['DivisionDistrict'] && !$Circuit['DivisionDistrict']['IsActive']) {
                $data['_options']['DivisionDistrict'][$Circuit['DivisionDistrict']['DivisionDistrictID']] = $Circuit['DivisionDistrict']['DivisionDistrictName'];
            }
            foreach ($Circuit['Features'] as $CircuitFeature) {
                if (!$CircuitFeature['Feature']['IsActive']) {
                    $data['_options']['FeatureType'][$CircuitFeature['Feature']['FeatureType']] = [
                        'FeatureName' => $CircuitFeature['Feature']['FeatureName'],
                        'ServiceType' => $CircuitFeature['Feature']['ServiceType'],
                        'show' => true,
                    ];
                }
            }
        }

        return view('dashboard.orders.edit', $data);
    }

    /**
     * Update an Order
     *
     * @param BTNAccount $BTNAccount
     * @param BTNAccountOrder $BTNAccountOrder
     * @param StoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CreateRequest $request, AddressesService $addressesService, BTNAccountOrder $BTNAccountOrder)
    {
        $BTNAccount = BTNAccount::find($BTNAccountOrder->BTNAccountID);
        $BTNAccount->update($request->dataBTNAccount());
        $Circuit = Circuit::find($BTNAccountOrder->CarrierCircuitID);
        $CircuitID = null;
        //Create a circuit or update existing circuit
        if ($Circuit) {
            $Circuit->update($request->getCircuitData());
            $Circuit->CategoryData()->update($request->getCategoryData());
            $addressesService->updateCircuitAddresses($Circuit, $request);
            $BTNAccountOrder->update(array_merge($request->dataOrder(), ['CarrierCircuitID' => $Circuit->CircuitID]));

            if ($request->has('CircuitFeatures')) {
                foreach ($request->get('CircuitFeatures') as $CircuitFeature) {
                    if (Arr::has($CircuitFeature, 'FeatureType') && Arr::has($CircuitFeature, 'FeatureCost')) {
                        $Circuit->Features()->firstOrNew([
                            'FeatureType' => $CircuitFeature['FeatureType'],
                        ])->setAttribute('FeatureCost', $CircuitFeature['FeatureCost'])->save();
                    }
                }
                // Delete those that don't exist. Doing this instead of deleting all then recreating ones that exists maintains Created_at and Updated_At history.
                $FeatureTypes = Arr::pluck($request->get('CircuitFeatures'), 'FeatureType');
                foreach ($Circuit->Features as $CircuitFeature) {
                    if (!in_array($CircuitFeature['FeatureType'], $FeatureTypes)) {
                        $CircuitFeature->delete();
                    }
                }
            }

            if ($request->has('Notes')) {
                $Circuit->Notes()->create([
                    'Note' => $request->get('Notes')
                ]);
            }
        } else {
            $BTNAccount->update($request->dataBTNAccount());

            if ($request->has('Note')) {
                $BTNAccountOrder->getAttribute('BTN')->Notes()->create($request->only(['Note']));
            }

            $addressesService->updateAddress($BTNAccount, $request);

            $BTNAccountOrder->update($request->dataOrder());
        }

        foreach ($request->file('OrderFiles') ?: [] as $file) {
            $newFile = uniqid() . '.' . ($file->getClientOriginalExtension() ?: $file->guessExtension());

            $file->move(
                storage_path('app\\CSR\\Pending'),
                $newFile
            );

            $BTNAccountOrder->Files()->create([
                'FilePath'      => 'app\\CSR\\Pending\\' . $newFile,
                'OriginalName'  => $file->getClientOriginalName(),
            ]);
        }

        return redirect()
            ->back()
            ->with('notification.success', 'Order information was successfully updated.');
    }

    /**
     * Approve an Order
     *
     * @param BTNAccount $BTNAccount
     * @param BTNAccountOrder $BTNAccountOrder
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(BTNAccountOrder $BTNAccountOrder, OrdersService $OrdersService)
    {
        $BTNAccount = BTNAccount::find($BTNAccountOrder->BTNAccountID);
        $Circuit = Circuit::find($BTNAccountOrder->CarrierCircuitID);

        //Validates that there is an account number for a BTN
        if (!$BTNAccount->AccountNum) {
            return redirect()
                ->back()
                ->with('notification.error', "Account # is required to approve an order.");
        }

        $BTNAccount->setAttribute('Status', BTNStatusType::STATUS_ACTIVE);

        if ($BTNAccountOrder->Files) {
            foreach ($BTNAccountOrder->Files as $File) {
                $BTNAccountCSR = $BTNAccount->CSRs()->create(
                    [
                        'AccountNum' => $BTNAccountOrder->ACEITOrderNum,
                        'PrintedDate' => $BTNAccountOrder->OrderDate
                    ]
                );

                $newFile = 'CSR\\ACE-IT_Order_' . $BTNAccountOrder->ACEITOrderNum . '_' . uniqid() . '.' . $File->getFileExtension();

                $BTNAccountCSR->FilePath = 'app\\' . $newFile;
                $BTNAccountCSR->save();

                Storage::move(
                    'CSR\\Pending\\' . basename($File->getFullPath()),
                    $newFile
                );
            }
        }
        $BTNAccount->save();

        if ($Circuit) {
            $Circuit->setAttribute('Status', BTNStatusType::STATUS_ACTIVE);
            $Circuit->save();
        }

        $BTNAccountOrder->approve();
        return redirect()
            ->route('dashboard.orders.index')
            ->with('notification.success', 'Order has been approved.');
    }

    /**
     * Download a File of Order
     *
     * @param BTNAccount $BTNAccount
     * @param BTNAccountOrder $BTNAccountOrder
     * @param BTNAccountOrderFile $BTNAccountOrderFile
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(BTNAccount $BTNAccount, BTNAccountOrder $BTNAccountOrder, BTNAccountOrderFile $BTNAccountOrderFile)
    {
        abort_unless($BTNAccountOrderFile->documentExists(), 404);
        return response()
            ->download($BTNAccountOrderFile->getFullPath());
    }

    /**
     * Decline single BTN Account Order
     *
     * @param BTNAccount $BTNAccount
     * @param BTNAccountOrder $BTNAccountOrder
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(BTNAccount $BTNAccount, BTNAccountOrder $BTNAccountOrder, Request $request)
    {
        //Delete any files from an order that was not approved
        if ($BTNAccountOrder->Files) {
            foreach ($BTNAccountOrder->Files as $file) {
                $a = explode("\\", $file['FilePath']);
                array_shift($a);
                Storage::delete(implode("\\", $a));
            }
        }

        $BTNAccountOrder->setAttribute('OrderStatus', OrderStatusType::STATUS_DECLINED);
        $BTNAccountOrder->save();

        return redirect()
            ->route('dashboard.orders.index', [
                'inventory' =>  $BTNAccount,
                'page'      =>  $request->get('page')
            ])
            ->with('notification.success', 'The order was successfully deleted.');
    }

    public function viewAttachment($file)
    {
        return response()->file(storage_path('app\\CSR\\Pending\\' . $file));
    }

    public function deleteAttachment(BTNAccountOrderFile $file)
    {

        //Get correct folder path
        $a = explode("\\", $file['FilePath']);
        array_shift($a);

        //remove file from Pending folder
        Storage::delete(implode("\\", $a));

        //delete file db record
        $file->delete();

        return redirect()
            ->back()
            ->with('notification.success', 'File was successfully removed.');
    }
}
