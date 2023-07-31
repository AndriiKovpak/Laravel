<?php
/**
 * Created by PhpStorm.
 * User: bcooper
 * Date: 6/8/2017
 * Time: 2:51 PM
 */

namespace App\Services;

use App\Models\Circuit;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class OrdersService
{

    /*
     * Creates pdf of Order form and returns the pdf location to be saved for the CSR
     * This is not currently being used. Design changes.
     * If we use this again we must include https://github.com/barryvdh/laravel-dompdf
     */
    public function createOrderPDF($BTNAccount, $BTNAccountOrder, $Circuit,$BTNAccountCSRID){
        $CircuitCategory = null;
        $Features        = null;
        if($Circuit){
            $CircuitCategory = $Circuit->{Circuit::getCategoryMethod($Circuit->getAttribute('CategoryID'))};
            $CategoryInfo = [
                'Category'              =>      $Circuit->Category['CategoryName'],
                'Start Date'            =>      $Circuit->BillingStartDate,
                'Installation Date'     =>      $Circuit->InstallationDT,
                'Bill Under BTN'        =>      $Circuit->BillUnderBTN,
                'Service Type'          =>      $Circuit->Service['ServiceTypeName'],
                'Cost'                  =>      $Circuit->Cost,
                'District'              =>      $BTNAccount->DivisionDistrict['DivisionDistrictName'],
            ];

            foreach ($Circuit['Features'] as $CircuitFeature) {
                $Features[] = [
                    'FeatureName'   =>  $CircuitFeature['Feature']['FeatureName'],
                    'Amount'        =>  $CircuitFeature['FeatureCost']
                ];
            }

            switch($Circuit->CategoryID){
                case Category::VOICE:
                    $CategoryInfo = array_merge($CategoryInfo,[
                        'Description ID'        =>      $CircuitCategory->Description['Description'],
                        'Circuit ID Phone'      =>      $CircuitCategory->CarrierCircuitID,
                        'SPID_Phone1'           =>      $CircuitCategory->SPID_Phone1,
                        'SPID_Phone2'           =>      $CircuitCategory->SPID_Phone2,
                        'Email'                 =>      $CircuitCategory->Email,
                        'LD_PIC'                =>      $CircuitCategory->LD_PIC,

                        'Point To Number'       =>      $CircuitCategory->PointToNumber,
                        'ILEC_ID1'              =>      $CircuitCategory->ILEC_ID1,
                        'ILEC_ID2'              =>      $CircuitCategory->ILEC_ID2,

                        /*'Service Address'       =>      $CircuitCategory->ServiceAddress['SiteName']
                                                        . ' ' . $CircuitCategory->ServiceAddress['Address1']
                                                        . ' ' . $CircuitCategory->ServiceAddress['Address2']
                                                        . ' ' . $CircuitCategory->ServiceAddress['City']
                                                        . ' ' . $CircuitCategory->ServiceAddress['State']
                                                        . ' ' . $CircuitCategory->ServiceAddress['Zip'],*/
                        'Location A Address'    =>      $CircuitCategory->LocationAAddress['SiteName']
                                                        . ' ' . $CircuitCategory->LocationAAddress['Address1']
                                                        . ' ' . $CircuitCategory->LocationAAddress['Address2']
                                                        . ' ' . $CircuitCategory->LocationAAddress['City']
                                                        . ' ' . $CircuitCategory->LocationAAddress['State']
                                                        . ' ' . $CircuitCategory->LocationAAddress['Zip'],
                        'Location Z Address'    =>      $CircuitCategory->LocationZAddress['SiteName']
                                                        . ' ' . $CircuitCategory->LocationZAddress['Address1']
                                                        . ' ' . $CircuitCategory->LocationZAddress['Address2']
                                                        . ' ' . $CircuitCategory->LocationZAddress['City']
                                                        . ' ' . $CircuitCategory->LocationZAddress['State']
                                                        . ' ' . $CircuitCategory->LocationZAddress['Zip'],
                    ]);
                    break;
                case Category::SATELLITE:
                    $CategoryInfo = array_merge($CategoryInfo,[
                        'Circuit ID Phone'      =>      $CircuitCategory->CarrierCircuitID,
                        'Email'                 =>      $CircuitCategory->Email,
                        'Name'                  =>      $CircuitCategory->AssignedToName,
                        'ILEC_ID1'              =>      $CircuitCategory->ILEC_ID1,
                        'ILEC_ID2'              =>      $CircuitCategory->ILEC_ID2,


                        'Device Type'           =>      $CircuitCategory->DeviceType,
                        'Devise Make'           =>      $CircuitCategory->DeviseMake,
                        'Devise Model'          =>      $CircuitCategory->DeviseModel,
                        'IMEI#/DevoceID'        =>      $CircuitCategory->IMEI,
                        'SIM#'                  =>      $CircuitCategory->SIM,
                    ]);
                    break;
                case Category::DATA:
                    $CategoryInfo = array_merge($CategoryInfo,[
                        'Description ID'        =>      $CircuitCategory->Description['Description'],
                        'Circuit ID Phone'      =>      $CircuitCategory->CarrierCircuitID,
                        'Email'                 =>      $CircuitCategory->Email,
                        'ILEC_ID1'              =>      $CircuitCategory->ILEC_ID1,
                        'ILEC_ID2'              =>      $CircuitCategory->ILEC_ID2,

                        /*'Service Address'       =>      $CircuitCategory->ServiceAddress['SiteName']
                                                        . ' ' . $CircuitCategory->ServiceAddress['Address1']
                                                        . ' ' . $CircuitCategory->ServiceAddress['Address2']
                                                        . ' ' . $CircuitCategory->ServiceAddress['City']
                                                        . ' ' . $CircuitCategory->ServiceAddress['State']
                                                        . ' ' . $CircuitCategory->ServiceAddress['Zip'],*/
                        'Location A Address'    =>      $CircuitCategory->LocationAAddress['SiteName']
                                                        . ' ' . $CircuitCategory->LocationAAddress['Address1']
                                                        . ' ' . $CircuitCategory->LocationAAddress['Address2']
                                                        . ' ' . $CircuitCategory->LocationAAddress['City']
                                                        . ' ' . $CircuitCategory->LocationAAddress['State']
                                                        . ' ' . $CircuitCategory->LocationAAddress['Zip'],
                        'Location Z Address'    =>      $CircuitCategory->LocationZAddress['SiteName']
                                                        . ' ' . $CircuitCategory->LocationZAddress['Address1']
                                                        . ' ' . $CircuitCategory->LocationZAddress['Address2']
                                                        . ' ' . $CircuitCategory->LocationZAddress['City']
                                                        . ' ' . $CircuitCategory->LocationZAddress['State']
                                                        . ' ' . $CircuitCategory->LocationZAddress['Zip'],

                        'QoS_CIR'               =>      $CircuitCategory->QoS_CIR,
                        'Port Speed'            =>      $CircuitCategory->PortSpeed,
                        'Mileage'               =>      $CircuitCategory->Mileage,
                        'Network IP Address'    =>      $CircuitCategory->NetworkIPAddress,
                    ]);
                    break;
            }
            $Notes = $Circuit->Notes;
            $OrderInfo = $CategoryInfo;
        }else{
            $OrderInfo =
                [
                    'District'                  =>          $BTNAccount->DivisionDistrict['DivisionDistrictName'],
                    'Carrier'                   =>          $BTNAccount->Carrier['CarrierName'],
                    'Address'                   =>          $BTNAccount->SiteAddress['SiteName']
                                                            . ' ' . $BTNAccount->SiteAddress['Address1']
                                                            . ' ' . $BTNAccount->SiteAddress['Address2']
                                                            . ' ' . $BTNAccount->SiteAddress['City']
                                                            . ' ' . $BTNAccount->SiteAddress['State']
                                                            . ' ' . $BTNAccount->SiteAddress['Zip'],
                ];
            $Notes = $BTNAccount->Notes;
        }

        $view = view('dashboard.orders.pdf', [
            'BTNAccount'            =>  $BTNAccount,
            'Circuit'               =>  Circuit::find($BTNAccountOrder->CarrierCircuitID),
            'CircuitCategory'       =>  $CircuitCategory,
            'Order'                 =>  $BTNAccountOrder,
            'OrderInfo'             =>  $OrderInfo,
            'message'               =>  'No data',
            'Notes'                 =>  $Notes,
            'Features'              =>  $Features,

        ])->render();
        $directory = storage_path('app\CSR\\' . $BTNAccount->BTNAccountID . '\\');

        //Create Directory if it does not exist
        if(!is_dir($directory)){
            mkdir($directory);
        }
        $fileName = $directory . 'ACE-IT_Order_' . $BTNAccountOrder->ACEITOrderNum . '_' . $BTNAccountCSRID . '.pdf';

        $pdf = App('dompdf.wrapper');
        $pdf->loadHTML($view);
        file_put_contents($fileName, $pdf->output());
        return 'app\CSR\\' . $BTNAccount->BTNAccountID . '\\' . 'ACE-IT_Order_' . $BTNAccountOrder->ACEITOrderNum . '_' . $BTNAccountCSRID . '.pdf';
    }

}