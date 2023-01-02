<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Utils\PdfPrint;
use App\Models\Lab\LabPanel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CoreMaster\AppSetting;
use App\Models\Lab\LabPatientTestData;
use App\Models\HrMaster\HrMstEmployees;
use App\Models\Lab\LabPatientTestResult;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReportPrintController extends Controller
{
    public function printTestReport($test_id)
    {
        $panels = [];
        $items = [];
        $currentEntry = LabPatientTestData::find($test_id);
        $test_id = $currentEntry->id;
        $lab_test_detail = DB::table('lab_patient_test_data')
            ->select('id', 'bill_id', 'patient_id', 'category_id', 'order_no','sample_no', 'collection_datetime', 'reported_datetime', 'lab_technician_id', 'doctor_id','comment')
            ->where('id', $test_id)
            ->first();
        $data['lab_test_detail'] = $lab_test_detail;
        $data['patient_detail'] = Patient::findOrFail($lab_test_detail->patient_id);

        if($lab_test_detail->lab_technician_id){
            $lab_technican = HrMstEmployees::findOrFail($lab_test_detail->lab_technician_id);
            $data['lab_technican'] = $lab_technican;
        }

        if($lab_test_detail->doctor_id){
            $doctor_detail = HrMstEmployees::findOrFail($lab_test_detail->doctor_id);
            $data['doctor_detail'] = $doctor_detail;
        }

        // for covid patient photo
        $patient_photo_encoded = '';
        $patient_photo = '';
        
        $patient = Patient::find($lab_test_detail->patient_id);
        if($patient->photo_name){
            $patient_photo = $patient->photo_name;
            if($patient_photo){
                $logo_path = public_path('storage/'.$patient_photo);
                // Read image path, convert to base64 encoding
                $logoData = base64_encode(file_get_contents($logo_path));
                    // Format the image SRC:  data:{mime};base64,{data};
                $patient_photo_encoded = 'data: '.mime_content_type($logo_path).';base64,'.$logoData;
            }
        }
        $data['patient_photo_encoded'] = $patient_photo_encoded;


        $labPatientTestData = LabPatientTestData::find($test_id);

        if(count($labPatientTestData->labPatientTestResults)){

            foreach($labPatientTestData->labPatientTestResults as $testResult){

                if($testResult->lab_panel_id){
                    if(!array_key_exists($testResult->panel->name, $panels)){
                        $panels[$testResult->panel->name] = [];
                    }
                    if($testResult->lab_group_id){
                        if(!array_key_exists("groups", $panels[$testResult->panel->name])){
                            $panels[$testResult->panel->name]['groups'] = [];
                        }
                        if(!array_key_exists($testResult->group->name, $panels[$testResult->panel->name]['groups'])){
                            $panels[$testResult->panel->name]['groups'][$testResult->group->name] = [];
                        }
                        if($testResult->lab_item_id){
                            if(!array_key_exists($testResult->lab_item_id, $panels[$testResult->panel->name]['groups'][$testResult->group->name])){
                                $panels[$testResult->panel->name]['groups'][$testResult->group->name][$testResult->lab_item_id] = [];
                            }
                            $panels[$testResult->panel->name]['groups'][$testResult->group->name][$testResult->lab_item_id]['item'] = $testResult->item;
                            $panels[$testResult->panel->name]['groups'][$testResult->group->name][$testResult->lab_item_id]['result'] = $testResult;
                        }
                    }else if($testResult->lab_item_id){
                        if(!array_key_exists('items', $panels[$testResult->panel->name])){
                            $panels[$testResult->panel->name]['items'] = [];
                        }
                        if(!array_key_exists($testResult->lab_item_id, $panels[$testResult->panel->name]['items'])){
                            $panels[$testResult->panel->name]['items'][$testResult->lab_item_id] = [];
                        }
                        $panels[$testResult->panel->name]['items'][$testResult->lab_item_id]['item'] = $testResult->item;
                        $panels[$testResult->panel->name]['items'][$testResult->lab_item_id]['result'] = $testResult;
                    }
                }else if($testResult->lab_item_id){
                    if(!array_key_exists($testResult->lab_item_id, $items)){
                        $items[$testResult->lab_item_id] = [];
                    }
                    $items[$testResult->lab_item_id]['item'] = $testResult->item;
                    $items[$testResult->lab_item_id]['result'] = $testResult;
                }
            }
        }

        //for ordering
        $items_order=[];
        $panel_count = $currentEntry->labPatientTestResults->pluck('lab_panel_id')->toArray();
        $panel_count = array_unique(array_filter($panel_count));
        foreach($panel_count as $pc)
        {
            $temp_panel =LabPanel::find($pc);
            $lab_groups_items = $temp_panel->panelGroupsItems();
            $lab_groups_items = $lab_groups_items->orderby('display_order')->get();
            foreach($lab_groups_items as $lgi)
            {
                if($lgi->lab_group_id)
                {
                    $items_order[$temp_panel->name][$lgi->display_order]['type']='group';
                    $items_order[$temp_panel->name][$lgi->display_order]['group_name']=$lgi->labGroup->name;
                    
                    // for display order of lab group item
                    $labGroupsItems = $lgi->labGroup->labGroupsItems->sortBy('display_order');
                    //insert group items in array
                    foreach($labGroupsItems as $i)
                    {
                        $i = $i->item;
                        $items_order[$temp_panel->name][$lgi->display_order]['group_items'][$i->id]['item']=$panels[$temp_panel->name]['groups'][$lgi->labGroup->name][$i->id]['item'];
                        $items_order[$temp_panel->name][$lgi->display_order]['group_items'][$i->id]['result']=$panels[$temp_panel->name]['groups'][$lgi->labGroup->name][$i->id]['result'];
                    }
                }else{
                    $items_order[$temp_panel->name][$lgi->display_order]['type']='item';
                    $items_order[$temp_panel->name][$lgi->display_order]['item']=$lgi->labItem;
                    $items_order[$temp_panel->name][$lgi->display_order]['result']=$panels[$temp_panel->name]['items'][$lgi->lab_item_id]['result'];
                }
            }
        }
        $data['panels'] = $panels;
        $data['items'] = $items;
        $data['items_order'] = $items_order;

        $data['flag_options'] = LabPatientTestResult::$flag_options_short;


        $client_details = AppSetting::with('client')->where('client_id', $currentEntry->client_id)->first();
        $data['client_details'] = $client_details;


        $report_header = AppSetting::where("client_id", $currentEntry->client_id)->first();
        $data['report_header'] = $report_header;

        // dd($data);

        $app_setting = AppSetting::where("client_id", $currentEntry->client_id)->first();
        if(isset($app_setting->client_logo)){

            $logo_path = public_path('storage/uploads/'.$app_setting->client_logo);
            // Read image path, convert to base64 encoding
            $logoData = base64_encode(file_get_contents($logo_path));
                // Format the image SRC:  data:{mime};base64,{data};
            $logo_encoded = 'data: '.mime_content_type($logo_path).';base64,'.$logoData;

        }else{

            $logo_path = public_path('images/sample_logo.jpg');
            $logoData = base64_encode(file_get_contents($logo_path));
            $logo_encoded = 'data: '.mime_content_type($logo_path).';base64,'.$logoData;

        }

        if($app_setting->client_stamp){

            $stamp_path = public_path('storage/uploads/'.$app_setting->client_stamp);
            // Read image path, convert to base64 encoding
            $stampData = base64_encode(file_get_contents($stamp_path));
                // Format the image SRC:  data:{mime};base64,{data};
            $stamp_encoded = 'data: '.mime_content_type($stamp_path).';base64,'.$stampData;
            
        }else{

            // $stamp_path = public_path('images/sample-stamp.jpg');
            // $stampData = base64_encode(file_get_contents($stamp_path));
            // $stamp_encoded = 'data: '.mime_content_type($stamp_path).';base64,'.$stampData;
            $stamp_encoded = null;

        }
        $sign1_encoded='';
        $sign2_encoded='';

        //get lab technician signature and doctor signature
        if($lab_test_detail->lab_technician_id && $lab_technican->signature){
            $sign1Path = public_path('storage/uploads/'.$lab_technican->signature);
            $sign1Data = base64_encode(file_get_contents($sign1Path));
            $sign1_encoded = 'data: '.mime_content_type($sign1Path).';base64,'.$sign1Data;

        }
        if($lab_test_detail->doctor_id && $doctor_detail->signature){
            $sign2Path = public_path('storage/uploads/'.$doctor_detail->signature);
            $sign2Data = base64_encode(file_get_contents($sign2Path));
            $sign2_encoded = 'data: '.mime_content_type($sign2Path).';base64,'.$sign2Data;

        }

        $data['stamp_encoded'] = $stamp_encoded;
        $data['logo_encoded'] = $logo_encoded;
        $data['tech_sign_encoded'] = $sign1_encoded;
        $data['doc_sign_encoded'] = $sign2_encoded;
        $data['currentEntry'] = $currentEntry;
        $qr_code = QrCode::size(80)->generate(url('/lab-patient-test-data/'.$test_id.'/print-test-report'));

       $data['qr_code']=$qr_code;

        $html = view('lab.test_result_report', $data)->render();
        PdfPrint::printPortrait($html, "test_result_report.pdf");
    }
}
