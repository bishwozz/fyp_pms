<?php

namespace App\Http\Controllers\Admin\Lab;

use Carbon\Carbon;
use App\Models\LabBill;
use App\Models\Patient;
use App\Utils\PdfPrint;
use Illuminate\Http\Request;
use App\Base\BaseCrudController;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use App\Models\Lab\LabMstCategories;
use App\Models\CoreMaster\AppSetting;
use App\Models\Lab\LabPatientTestData;
use App\Models\HrMaster\HrMstEmployees;
use App\Models\Lab\LabPatientTestResult;
use App\Http\Requests\LabPatientTestDataRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;


class LabPatientTestDataCrudController extends BaseCrudController
{

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    protected $user;
    public function setup()
    {
        $this->user = backpack_user();
        CRUD::setModel(\App\Models\Lab\LabPatientTestData::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/lab-patient-test-data/'.$this->parent('custom_param'));
        CRUD::setEntityNameStrings('Sample Collection', 'Sample Collection');
        $this->crud->addClause('where','client_id',$this->user->client_id);
        $this->crud->denyAccess(['create','delete']);
        if($this->crud->getActionMethod()=='edit'){
            $this->getEditData();
        }
        CRUD::setEditView('sample_collection.lab_patient_test_data',$this->data);
        $this->setCustomTabLinks();

        $this->crud->clearFilters();
        $this->setFilters();

        $this->processCustomParams();
        $this->checkPermission([
            'collectSample'=>'create',
        ]);
    }


    protected function setCustomTabLinks()
    {
        $this->data['list_tab_header_view'] = 'tab.custom_tab_links';

        $links[] = ['label' => 'Pending Orders', 'icon' => 'la la-cogs', 'href' => backpack_url('lab-patient-test-data/pending_orders')];
        $links[] = ['label' => 'Collected Orders', 'icon' => 'la la-cogs', 'href' => backpack_url('lab-patient-test-data/collected_orders')];

        $this->data['links'] = $links;
    }

    protected function processCustomParams()
    {
        $custom_param = $this->parent('custom_param');
        $cancelled_bills = LabBill::where('is_cancelled',true)->pluck('id')->toArray();

        switch ($custom_param) {
                case 'pending_orders':
                    $this->crud->query->where('collection_status',0);
                    $this->crud->orderBy('created_at','DESC');
                break;
                case 'collected_orders':
                    $this->crud->query->where('collection_status',1);
                    $this->crud->orderBy('collection_datetime','DESC');
                break;
                default:
                    $this->crud->query->where('collection_status',0);
                    $this->crud->orderBy('created_at','DESC');
                break;
            }
            $this->crud->query->whereNotIn('bill_id',$cancelled_bills);

    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */

    private function setFilters(){

        $this->crud->addFilter(
            [ // simple filter
                'type' => 'select2',
                'name' => 'title',
                'label' => 'Lab Category'
            ],
            function() {
                return LabMstCategories::all()->pluck('title', 'id')->toArray();
            },
            function($value) { 
                $this->crud->addClause('where', 'category_id', $value);
            }
        );

        $this->crud->addFilter([
            'type'  => 'date_range',
            'name'  => 'collection_datetime',
            'label' => 'Date range'
          ],
          false,
          function ($value) {
            $dates = json_decode($value);
            if($dates!= null)
            {
                $this->crud->query->whereDate('collection_datetime', '>=', $dates->from);
                $this->crud->query->whereDate('collection_datetime', '<=', $dates->to);
            }
          });
       
    }


    protected function getEditData(){
        $currentEntry = $this->crud->getCurrentEntry();
        $panels = [];
        $items = [];
        if(count($currentEntry->labPatientTestResults)){
            foreach($currentEntry->labPatientTestResults as $testResult){
                if($testResult->lab_panel_id){
                    if(!array_key_exists($testResult->panel->id, $panels)){
                        $panels[$testResult->panel->id]['panel']=$testResult->panel;
                        $panels[$testResult->panel->id]['barcode']=$testResult->barcode;
                    }
                }else{
                    if(!array_key_exists($testResult->item->id,$items)){
                        $items[$testResult->item->id]['item']=$testResult->item;
                        $items[$testResult->item->id]['barcode']=$testResult->barcode;
                    }
                }
            }
        }
        //for ordering
        $items_order=[];

        if($currentEntry->labPatientTestResults->first()->panel){

            $lab_groups_items = $currentEntry->labPatientTestResults->first()->panel->panelGroupsItems();
            $lab_groups_items = $lab_groups_items->orderby('display_order')->get();

            foreach($lab_groups_items as $lgi)
            {
                if($lgi->lab_group_id)
                {
                    $items_order[$lgi->display_order]['type']='group';
                    $items_order[$lgi->display_order]['detail']=$lgi->labGroup;
                }else{
                $items_order[$lgi->display_order]['type']='item';
                $items_order[$lgi->display_order]['detail']=$lgi->labItem;

                }
            }
        } 



        $this->data['panels'] = $panels;
        $this->data['items'] = $items;
        $this->data['items_order'] = $items_order;
        $this->data['order_no'] = $currentEntry->order_no;
        $this->data['patient'] = null;
        $this->data['patient_test_data_id'] = $currentEntry->id;
        $this->data['comment'] = $currentEntry->comment;
        $this->data['collection_status'] = LabPatientTestData::$collection_status[$currentEntry->collection_status];
        if($currentEntry->bill->patient_id){
            $this->data['patient'] = $currentEntry->bill->patient;
        }
        return $this->data;
    }
    protected function collectSample(Request $request){
        DB::beginTransaction();
        try{
            $currentEntry = LabPatientTestData::find($request->patient_test_data_id);
            $sample_no=null;
            $sample = DB::table('lab_patient_test_data')->where('client_id',$this->user->client_id)->max('sample_no');
            $sample_prefix_key = appSetting()->sample_seq_key;

            if($currentEntry->sample_no){
                $sample_no = $currentEntry->sample_no;
            }else if($sample != null){
                $explode = explode('-',$sample);
                $num = end($explode);
                $sample_no = $sample_prefix_key.(intval($num) + 1);
            }else{
                $sample_no = $sample_prefix_key.'100000';
            }

            $patientTestData=LabPatientTestData::find($request->patient_test_data_id);
            if(isset($request->collection_datetime)){
                $patientTestData->collection_datetime = $request->collection_datetime;
            }else{
                $patientTestData->collection_datetime=Carbon::now()->toDateTimeString();
            }
            $patientTestData->collection_status=1;
            $patientTestData->sample_no=$sample_no;
            $patientTestData->save();
            if(isset($request->panel_barcodes)){
                foreach($request->panel_barcodes as $key => $panel_barcode){
                    $labResults=LabPatientTestResult::where('patient_test_data_id',$patientTestData->id)->where('lab_panel_id',$key)->get();
                    foreach($labResults as $labResult){
                        $labResult->barcode = $request->panel_barcodes[$key];
                        $labResult->save();
                    }
                }
            }
            if(isset($request->item_barcodes)){
                foreach($request->item_barcodes as $key => $item_barcode){
                    $labResults=LabPatientTestResult::where('patient_test_data_id',$patientTestData->id)->whereNull('lab_panel_id')->where('lab_item_id',$key)->get();
                    foreach($labResults as $labResult){
                        $labResult->barcode = $request->item_barcodes[$key];
                        $labResult->save();
                    }
                }
            }
            DB::commit();
            Alert::success('Sample Collected Successful')->flash();
            return response()->json([
                'status' => true,
                'url' => backpack_url('lab-patient-test-data/pending_orders'),
            ]);
        }catch(Throwable $th){
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 404);
        }
      
    }

    protected function setupListOperation()
    {
        $col=[
            $this->addRowNumber(),
            [
                'label'=>'Order No',
                'type'=>'model_function',
                'name'=>'order_no',
                'function_name'=>'orderNo',
                'orderable'=>false,
            ],
            [
                'label'=>trans('Patient Name'),
                'type' => 'select',
                'name' => 'patient_id', 
                'entity' => 'patient', 
                'attribute' => 'name', 
                'model' => Patient::class,
                'orderable'=>false,
            ],
            [
                'label'=>trans('Patient No'),
                'type' => 'select',
                'name' => 'patient_no', 
                'entity' => 'patient', 
                'attribute' => 'patient_no', 
                'model' => Patient::class,
                'orderable'=>false,
            ],
            [
                'label'=>trans('Category'),
                'type' => 'select',
                'name' => 'category_id', 
                'entity' => 'category', 
                'attribute' => 'title', 
                'orderable'=>false,
                'model' => LabMstCategories::class,
            ],
            [
                'label'=>trans('Bill Id'),
                'type' => 'select',
                'name' => 'bill_id', 
                'entity' => 'bill', 
                'attribute' => 'bill_no', 
                'model' => LabBill::class,
                'orderable'=>false,
            ],
            [
                'name' => 'sample_no',
                'label' => trans('Sample No'),
                'type' => 'text',
                'orderable'=>false,
            ],
            [
                'name' => 'collection_datetime',
                'label' => trans('Collection Datetime'),
                'type' => 'datetime',
                'orderable'=>false,
            ],
            [
                'name' => 'collection_status',
                'label' => trans('Status'),
                'type' => 'select_from_array',
                'options' =>LabPatientTestData::$collection_status,
                'orderable'=>false,
            ]
        ];
        $this->crud->addColumns(array_filter($col));
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(LabPatientTestDataRequest::class);
    }


    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
