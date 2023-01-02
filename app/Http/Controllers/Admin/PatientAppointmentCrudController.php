<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Base\BaseCrudController;
use App\Models\PatientAppointment;
use App\Models\CoreMaster\MstGender;
use App\Notifications\NewAppointment;
use App\Models\HrMaster\HrMstEmployees;
use App\Models\HrMaster\HrMstDepartments;
use Illuminate\Notifications\Notification;
use App\Http\Requests\PatientAppointmentRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PatientAppointmentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PatientAppointmentCrudController extends BaseCrudController
{
    
  
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\PatientAppointment::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/patient-appointment');
        CRUD::setEntityNameStrings('Patient Appointment', 'Patient Appointment');
        $this->crud->addButtonFromView('line','approveBtn','approveBtn','beginning');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $col = [
            $this->addRowNumber(),
            [
                'name' => 'full_name',
                'type' => 'text',
                'label' => 'Full Name',
            ],
            [
                'name'=>'gender_id',
                'type'=>'select',
                'label'=>'Gender',
                'entity'=>'genderEntity',
                'model'=>MstGender::class,
                'attribute'=>'name',
            ],
            [
                'name' => 'cell_phone',
                'type' => 'text',
                'label' => 'Phone',
            ],
            // [
            //     'name'=>'department_id',
            //     'type'=>'select',
            //     'label'=>'Department',
            //     'entity'=>'departmentEntity',
            //     'model'=>HrMstDepartments::class,
            //     'attribute'=>'title',
            // ],
            // [
            //     'name'=>'doctor_id',
            //     'type'=>'select',
            //     'label'=>'Doctor',
            //     'entity'=>'doctorEntity',
            //     'model'=>HrMstEmployees::class,
            //     'attribute'=>'full_name',
            // ],
            [
                'name' => 'appointment_date',
                'type' => 'date',
                'label' => 'Appointment Date',
            ],
            [
                'name' => 'appointment_status',
                'type' => 'boolean',
                'label' => 'Status',
                'options' => [1 => 'Approved', 0 => 'Pending']
            ],
        ];
        $this->crud->addColumns(array_filter($col));

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(PatientAppointmentRequest::class);
        $arr = [
            $this->addClientIdField(),
            // [
            //     'name' => 'legend1',
            //     'type' => 'custom_html',
            //     'value' => '<b><legend> Doctor Information: :</legend></b>',
            // ],
            // [
            //     'label'=>'Department',
            //     'type' => 'select2',
            //     'name' => 'department_id', 
            //     'entity' => 'departmentEntity', 
            //     'attribute' => 'title', 
            //     'model' => HrMstDepartments::class,
            //     'wrapper' => [
            //         'class' => 'form-group col-md-4',
            //     ],
            // ],
            // [
            //     'name'=>'doctor_id',
            //     'label'=>'Doctor',
            //     'type'=>'select2_from_ajax',
            //     'model'=>HrMstEmployees::class,
            //     'entity'=>'doctorEntity',
            //     'attribute'=>'full_name',
            //     'method'=>'post',
            //     'data_source' => url("api/doctor/department_id"),
            //     'minimum_input_length' => 0,
            //     'dependencies'=> ['department_id'],
            //     'wrapperAttributes' => [
            //         'class' => 'form-group col-md-4',
            //     ],
            //     'attributes' => [
            //         'placeholder' => "Select a Department First",
            //     ],
            // ],
            [
                'name' => 'legend2',
                'type' => 'custom_html',
                'value' => '<b><legend> Patient Information: :</legend></b>',
            ],
            [
                'name' => 'full_name',
                'type' => 'text',
                'label' => 'Full Name',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes'=>[
                    'required' => 'Required',
                    'maxlength' => '50',
                 ],
            ],
            [
                'label'=>'Gender',
                'type' => 'select2',
                'name' => 'gender_id', 
                'entity' => 'genderEntity', 
                'attribute' => 'name', 
                'model' => MstGender::class,
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
            ],

            [
                'name' => 'age',
                'type' => 'number',
                'label' => 'Age',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes'=>[
                    'required' => 'Required',
                 ],
            ],
            [
                'name' => 'city',
                'type' => 'text',
                'label' => 'City',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'cell_phone',
                'type' => 'number',
                'label' => 'Phone',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes'=>[
                    'required' => 'Required',
                    'maxlength' => '10',
                 ],
            ],
            [
                'name' => 'email',
                'type' => 'text',
                'label' => 'Email',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes'=>[
                    'maxlength' => '50',
                 ],
            ],
           
            [
                'name' => 'legend3',
                'type' => 'custom_html',
                'value' => '<b><legend>Schedule: :</legend></b>',
            ],
            
            [
                'name' => 'appointment_date',
                'type' => 'date',
                'label' => 'Appointment Date',
                'attributes' => [
                    'id' => 'date-ad',
                ],
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'appointment_date_bs',
                'type' => 'nepali_date',
                'label' => 'Appointment Date B.S',
                'attributes' => [
                    'id' => 'date-bs',
                    'maxlength'=> '10',
                    'relatedId' => 'date-ad'

                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name'        => 'appointment_status', 
                'label'       => 'Appointment Status',
                'type'        => 'toggle',
                'options'     => [
                    0 => 'Pending',
                    1 => 'Approved'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'hide_when' => [ 
                    0 => ['approved_by'],
                ],
                'inline' => true,
                'default' => 0 
            ],
            [
                'label'=>'Approved By',
                'type' => 'select2',
                'name' => 'approved_by', 
                'entity' => 'approvedEntity', 
                'attribute' => 'full_name', 
                'model' => HrMstEmployees::class,
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'id' => 'approved-by',

                ],
            ],

                $this->addRemarksField(),
    ];
    $arr = array_filter($arr);
    $this->crud->addFields($arr);
        

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


    public function store()
    {
        $this->crud->hasAccessOrFail('create');
        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();

        // insert item in the db
        $item = $this->crud->create($this->crud->getStrippedSaveRequest($request));
        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();
        // for notification
        $id = $this->data['entry']->id;
        $this->sendNotification($id);
        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    public function sendNotification($id){

        $details = [
            'appointment_id' => $id
        ];
        $user = PatientAppointment::find($id);
        $user->notify(new NewAppointment($details));
    }

    public function patientApprove($id){
        $this->data['entry_data'] = PatientAppointment::findOrFail($id);
        $this->data['approver'] = HrMstEmployees::all();
        return view('dialog.patient-approve', $this->data);
    }

    public function patientApproveSave(Request $request){

        $approve = PatientAppointment::find($request->patient_id);
        $approve->appointment_status = 1;
        $approve->approved_by = $request->approved_by;
        $approve->save();

        return response()->json(['status'=>'success']);
    }

}


