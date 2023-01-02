<?php

namespace App\Http\Controllers\Admin\Lab;

use Carbon\Carbon;
use App\Models\LabBill;
use App\Models\Patient;
use App\Utils\PdfPrint;
use Illuminate\Support\Str;
use App\Models\Lab\LabPanel;
use Illuminate\Http\Request;
use App\Base\BaseCrudController;
use App\Models\Lab\Interpretation;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use App\Models\Lab\LabMstCategories;
use App\Models\CoreMaster\AppSetting;
use App\Models\Lab\LabPatientTestData;
use App\Models\HrMaster\HrMstEmployees;
use App\Models\Lab\LabPatientTestResult;
use App\Http\Requests\ResultEntryRequest;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ResultEntryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ResultEntryCrudController extends BaseCrudController
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
        CRUD::setModel(LabPatientTestData::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/lab/result-entry/'.$this->parent('custom_param'));
        CRUD::setEntityNameStrings('result entry', 'result entries');
        $this->crud->addClause('where','client_id',$this->user->client_id);
        $this->crud->denyAccess(['create','delete']);
        if($this->crud->getActionMethod()=='edit'){
            $this->getEditData();
        }
        CRUD::setEditView('sample_collection.lab_patient_test_results',$this->data);
        $this->setCustomTabLinks();

        $this->crud->clearFilters();
        $this->setFilters();
        $this->processCustomParams();
        $this->checkPermission([
            'storeResult'=>'create',
        ]);

    }
    private function setFilters(){

        $this->crud->addFilter(
            [ // simple filter
                'type' => 'text',
                'name' => 'order_no',
                'label' => 'Order Number'
            ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'order_no', 'iLIKE', "%$value%");
            }
        );
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
            'name'  => 'reported_datetime',
            'label' => 'Date range'
          ],
          false,
          function ($value) {
            $dates = json_decode($value);
            if($dates!= null)
            {
                $this->crud->query->whereDate('reported_datetime', '>=', $dates->from);
                $this->crud->query->whereDate('reported_datetime', '<=', $dates->to);
            }
        });

        if($this->parent('custom_param') == 'reported_orders'){

            $this->crud->addFilter(
                [ // simple filter
                    'type' => 'select2',
                    'name' => 'approve_status',
                    'label' => 'Approve Status'
                ],
                function() {
                    return LabPatientTestData::$approve_status;
                },
                function($value) { 
                    $this->crud->addClause('where', 'approve_status', $value);
                }
            );
        };

    }

    protected function setCustomTabLinks()
    {
        $this->data['list_tab_header_view'] = 'tab.custom_tab_links';

        $links[] = ['label' => 'Pending Orders', 'icon' => 'la la-cogs', 'href' => backpack_url('lab/result-entry/pending_orders')];
        $links[] = ['label' => 'Reported Orders', 'icon' => 'la la-cogs', 'href' => backpack_url('lab/result-entry/reported_orders')];

        $this->data['links'] = $links;
    }

    protected function processCustomParams()
    {
        $custom_param = $this->parent('custom_param');
            
        $cancelled_bills = LabBill::where('is_cancelled',true)->pluck('id')->toArray();
        $this->crud->query->whereNotIn('bill_id',$cancelled_bills);
        $this->crud->query->where('collection_status',1);
    
        switch ($custom_param) {
            case 'pending_orders':
                $this->crud->query->where('reported_status',0);
                $this->crud->orderBy('created_at','DESC');
            break;
            case 'reported_orders':
                $this->crud->query->where('reported_status',1);
                $this->crud->addButtonFromModelFunction('line','printTestReport','printTestReport','beginning');
                $this->crud->orderBy('reported_datetime','DESC');
            break;
            default:
                $this->crud->query->where('reported_status',0);
                $this->crud->orderBy('created_at','DESC');
            break;
        }

    }

    public function getEditData(){
        $currentEntry = $this->crud->getCurrentEntry();
        $test_id = $currentEntry->id;
        $lab_test_detail = DB::table('lab_patient_test_data')
            ->select('id', 'bill_id', 'patient_id', 'category_id', 'order_no', 'collection_datetime', 'reported_datetime', 'lab_technician_id', 'doctor_id')
            ->where('id', $test_id)
            ->first();
        $this->data['lab_test_detail'] = $lab_test_detail;
        $this->data['patient_test_data_id'] = $currentEntry->id;

        // if($lab_test_detail->lab_technician_id){
        //     $lab_technican = HrMstEmployees::findOrFail($lab_test_detail->lab_technician_id)->first()->full_name;
        //     $this->data['lab_technican'] = $lab_technican;
        // }

        // if($lab_test_detail->doctor_id){
        //     $docotor_detail = HrMstEmployees::findOrFail($lab_test_detail->doctor_id)->first()->full_name;
        //     $this->data['docotor_detail'] = $docotor_detail;
        // }

        $this->data['patient_detail'] = Patient::findOrFail($lab_test_detail->patient_id);
        $labPatientTestData = LabPatientTestData::find($test_id);

        $panels = [];
        $items = [];
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


        //   dd($panels,$items_order,$items);

        $this->data['panels'] = $panels;
        $this->data['items'] = $items;
        $this->data['items_order'] = $items_order;

        $this->data['sample_no'] = $labPatientTestData->sample_no;
        $this->data['order_no'] = $labPatientTestData->order_no;
        $this->data['comment'] = $labPatientTestData->comment;
        $this->data['patient_test_data_id'] = $currentEntry->id;
        $this->data['patient'] = null;
        $this->data['lab_technicians'] = HrMstEmployees::whereIn('role_id',[6,7,8])->get();
        $this->data['doctors'] = HrMstEmployees::where('is_result_approver', true)->get();
        $this->data['patient'] = null;
        if($this->user->isClientUser()){
            $this->data['interpretations'] = Interpretation::where('client_id',$this->user->client_id)->get();
        }else{
            $this->data['interpretations'] = Interpretation::all();
        }
        if($currentEntry->bill->patient_id){
            $this->data['patient'] = $currentEntry->bill->patient;
        }

        $this->data['flag_options'] = LabPatientTestResult::$flag_options;
        return $this->data;
    }

   

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {

        $col=[
            $this->addRowNumber(),
            [
                'label'=>'Order No',
                'type'=>'model_function',
                'name'=>'order_no',
                'function_name'=>'orderNoResult',
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
                'orderable'=>false,
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
                'name' => 'reported_datetime',
                'label' => trans('Reported Date'),
                'type'=>'datetime',
                'orderable'=>false,
            ],
            [
                'name' => 'reported_status',
                'label' => trans('Reported Status'),
                'type' => 'select_from_array',
                'options' =>LabPatientTestData::$reported_status,
                'orderable'=>false,
            ],
            [
                'name' => 'approved_datetime',
                'label' => trans('Approved Date'),
                'type' => 'datetime',
                'orderable'=>false,
            ],
            [
                'name' => 'approve_status',
                'label' => trans('Approve Status'),
                'type' => 'select_from_array',
                'options' =>LabPatientTestData::$approve_status,
                'orderable'=>false,
            ]
        ];
        $cancelled_bills = LabBill::where('is_cancelled',true)->pluck('id')->toArray();
        $this->crud->addColumns(array_filter($col));
        $this->crud->query->whereNotIn('bill_id',$cancelled_bills)->where('collection_status','<>',0);
        $this->crud->orderBy('reported_datetime','DESC');
    }

    public function storeResult(Request $request){

        DB::beginTransaction();
        try{
            foreach ($request->results as $key => $result) {
                $resultEntry = LabPatientTestResult::find($key);
                $resultEntry->result_value=$request->results[$key];
                $resultEntry->flag=$request->flags[$key];
                // $resultEntry->methodology=$request->methodologies[$key];
                $resultEntry->save();
            }
            $labPatientTestData = LabPatientTestData::find($request->patient_test_data_id);
            $labPatientTestData->comment = $request->comment;

            if(isset($request->reported_datetime)){
                $labPatientTestData->reported_datetime = $request->reported_datetime;
            }else{
                $labPatientTestData->reported_datetime = (isset($labPatientTestData->reported_datetime) && $labPatientTestData->reported_datetime != '') ? $labPatientTestData->reported_datetime :Carbon::now()->toDateTimeString();
            }

            $labPatientTestData->reported_status = 1;
            $labPatientTestData->lab_technician_id = $request->lab_technician_id;
            //update approve status
            if(isset($request->approve_status) && $request->approve_status == '1' ){
                $labPatientTestData->approve_status =1;
                $labPatientTestData->doctor_id =$request->doctor_id;
                if(isset($request->approved_datetime)){
                    $labPatientTestData->approved_datetime = $request->approved_datetime;
                }else{
                    $labPatientTestData->approved_datetime =(isset($labPatientTestData->approved_datetime) && $labPatientTestData->approved_datetime != '') ? $labPatientTestData->approved_datetime :Carbon::now()->toDateTimeString();
                }
            }
            $labPatientTestData->save();
            DB::commit();
            Alert::success('Result Saved Successfully')->flash();
            return response()->json([
                'status' => true,
                'url' => backpack_url('lab/result-entry/pending_orders'),
            ]);
        }catch(Throwable $th){
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 404);
        }
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ResultEntryRequest::class);

        

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
