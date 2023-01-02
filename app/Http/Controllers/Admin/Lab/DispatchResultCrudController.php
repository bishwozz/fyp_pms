<?php

namespace App\Http\Controllers\Admin\Lab;

use Carbon\Carbon;
use App\Models\LabBill;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Base\BaseCrudController;
use App\Models\Lab\LabMstCategories;
use App\Models\Lab\LabPatientTestData;
use App\Models\HrMaster\HrMstEmployees;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DispatchResultCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DispatchResultCrudController extends BaseCrudController
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
        CRUD::setRoute(config('backpack.base.route_prefix') . '/lab/dispatch-result');
        CRUD::setEntityNameStrings('Result Dispatch', 'Result Dispatch');
        // $this->crud->addButtonFromModelFunction('line','labBillingPrint','labBillingPrint','beginning');
        $this->crud->addClause('where','client_id',$this->user->client_id);
        $this->checkPermission([
            'dispatchResult'=>'create'
        ]);

    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->removeButtons(['create','update','delete']);
        $this->crud->addButtonFromView('line','dispatchBtn','dispatchBtn','beginning');
        $this->crud->addButtonFromModelFunction('line','printTestReport','printTestReport','beginning');


        $col=[
            $this->addRowNumber(),
            [
                'label'=>'Order No',
                'type'=>'text',
                'name'=>'order_no',
                // 'function_name'=>'orderNoResult',
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
                'label'=>trans('Lab Technician'),
                'type' => 'select',
                'name' => 'lab_technician_id', 
                'entity' => 'labTechnicianEntity', 
                'attribute' => 'full_name', 
                'model' => HrMstEmployees::class,
                'orderable'=>false,
            ],
            [
                'label'=>trans('Approver'),
                'type' => 'select',
                'name' => 'doctor_id', 
                'entity' => 'doctorEntity', 
                'attribute' => 'full_name', 
                'model' => HrMstEmployees::class,
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
        $this->crud->addColumns(array_filter($col));
        $cancelled_bills = LabBill::where('is_cancelled',true)->pluck('id')->toArray();
        $this->crud->query->whereNotIn('bill_id',$cancelled_bills)->where('approve_status','<>',0)->orderBy('dispatch_status',"ASC");
        // $this->crud->query->orderBy('dispatch_status','DESC');
        // dd($this->crud->query->toSql());
    }
   

    //update result dispatch status
    public function dispatchResult(Request $request,$id)
    {
        if(isset($request) && $request->status)
        {
            $object = LabPatientTestData::find($id);
            $object->dispatch_status = 1;
            $object->dispatched_datetime = Carbon::now()->toDateTimeString();
            $object->save();

            return response()->json(['status'=>'success']);
        }else{
            return response()->json(['status'=>'fail','message'=>'Error occured !']);
        }
    }
}
