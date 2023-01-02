<?php

namespace App\Http\Controllers\Admin\HrMaster;

use App\Models\Role;

use App\Models\User;
use Illuminate\Http\Request;
use App\Base\Traits\ParentData;
use App\Base\BaseCrudController;
use Illuminate\Support\Facades\DB;
use App\Models\CoreMaster\MstGender;
use App\Models\CoreMaster\MstCountry;
use App\Base\Operations\FetchOperation;
use App\Models\HrMaster\HrMstEmployees;
use App\Models\CoreMaster\MstFedDistrict;

use App\Models\CoreMaster\MstFedProvince;

use App\Models\HrMaster\HrMstDepartments;
use App\Models\CoreMaster\MstFedLocalLevel;
use App\Models\HrMaster\HrMstSubDepartments;
use App\Http\Requests\HrMaster\HrMstEmployeesRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;


class HrMstEmployeesCrudController extends BaseCrudController
{
    use ParentData;
    use FetchOperation;

   public function setup()
    {
        CRUD::setModel(HrMstEmployees::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/hrmstemployees');
        CRUD::setEntityNameStrings(trans('hremployees.add_text'), trans('hremployees.title_text'));
        $this->crud->clearFilters();
        $this->setFilters();
        $this->checkPermission(); 
    }

    private function setFilters(){
        $this->crud->addFilter(
            [ // simple filter
                'type' => 'text',
                'name' => 'full_name',
                'label' => trans('hremployees.full_name')
            ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'full_name', 'iLIKE', "%$value%");
            }
        );
        $this->crud->addFilter(
            [ // simple filter
                'type' => 'text',
                'name' => 'emp_no',
                'label' => 'Employee No'
            ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'emp_no', 'iLIKE', "%$value%");
            }
        );
        $this->crud->addFilter(
            [ // simple filter
                'type' => 'select2',
                'name' => 'department_id',
                'label' => 'Department'
            ],
            function() {
                return HrMstDepartments::all()->pluck('title', 'id')->toArray();
            },
            function($value) { 
                $this->crud->addClause('where', 'department_id', $value);
            }
        );
    }

    protected function setupListOperation()
    {
        $cols = [
            $this->addRowNumber(),
            [
                'name' => 'photo_name',
                'type' => 'image',
                'label' => trans('Image'),
                'disk'=>'uploads',
            ],
            [
                'name' => 'salutation_id',
                'type' => 'select_from_array',
                'label' => trans('Title'),
                 'options'=>HrMstEmployees::$salutation_options
            ],
            [
                'name' => 'full_name',
                'type' => 'text',
                'label' => trans('hremployees.full_name'),
                
            ],
            [
                'name'=>'gender_id',
                'type'=>'select',
                'label'=>trans('hremployees.gender'),
                'entity'=>'gender',
                'model'=>MstGender::class,
                'attribute'=>'name',
            ],
            [
                'name'=>'department_id',
                'type'=>'select',
                'label'=>trans('hremployees.department'),
                'entity'=>'department',
                'model'=>HrMstDepartments::class,
                'attribute'=>'title',
            ],
            [
                'name'=>'sub_department_id',
                'type'=>'select',
                'label'=>trans('Sub Department'),
                'entity'=>'subDepartment',
                'model'=>HrMstSubDepartments::class,
                'attribute'=>'title',
            ],
            [
                'name'=>'role_id',
                'type'=>'select',
                'label'=>trans('Role'),
                'entity'=>'roleEntity',
                'model'=>Role::class,
                'attribute'=>'field_name',
            ],
            [
                'name' => 'is_discount_approver',
                'type' => 'check',
                'label' => trans('Discount Approver'),
                'options' => [1 => 'Yes', 0 => 'No']
            ],
            [
                'name' => 'is_credit_approver',
                'type' => 'check',
                'label' => trans('Credit Approver'),
                'options' => [1 => 'Yes', 0 => 'No']
            ],
            [
                'name' => 'is_result_approver',
                'type' => 'check',
                'label' => trans('Result Approver'),
                'options' => [1 => 'Yes', 0 => 'No']
            ],
          
        ];
        $this->crud->addColumns($cols);
    }



    protected function setupCreateOperation()
    {
        $this->crud->setValidation(HrMstEmployeesRequest::class);
        $arr = [
                $this->addClientIdField(),
                [
                    'name' => 'emp_no',
                    'label' => trans('hremployees.empno'),
                    'type' => 'hidden',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-12',
                    ],
                    'attributes' => [
                        'readonly'=>'readonly'                        
                    ],
                ],
                [   // Upload
                    'name' => 'photo_name',
                    'label' => 'Photo',
                    'type' => 'image',
                    'upload' => true,
                    'disk' => 'uploads', 
                    'aspect_ratio' => 1,
                    'wrapper' => [
                        'class' => 'form-group col-md-2',
                    ],
                ],
                [   // Upload
                    'name' => 'signature',
                    'label' => 'Signature',
                    'type' => 'image',
                    'upload' => true,
                    'disk' => 'uploads',
                    'crop'=>true,
                    'aspect_ratio' => 1,
                    'wrapper' => [
                        'class' => 'form-group col-md-2',
                    ],
                   
                ],
          
                [
                    'name' => 'salutation_id',
                    'type' => 'select_from_array',
                    'label' => trans('Title'),
                    'wrapper' => [
                        'class' => 'form-group col-md-3',
                    ],
                    'attributes'=>[
                        'required' => 'Required',
                        'maxlength' => '200',
                     ],
                     'options'=>HrMstEmployees::$salutation_options
                ],
                
                [
                    'name' => 'full_name',
                    'type' => 'text',
                    'label' => trans('hremployees.full_name'),
                    'wrapper' => [
                        'class' => 'form-group col-md-5',
                    ],
                    'attributes'=>[
                        'required' => 'Required',
                        'maxlength' => '200',
                     ],
                ],
                [
                    'name'=>'gender_id',
                    'type'=>'select2',
                    'label'=>trans('hremployees.gender'),
                    'entity'=>'gender',
                    'model'=>MstGender::class,
                    'attribute'=>'name',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-4',
                    ],
                ],

                [
                    'name' => 'date_of_birth_bs',
                    'type' => 'nepali_date',
                    'label' => trans('hremployees.date_bs'),
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
                    'name' => 'date_of_birth_ad',
                    'type' => 'date',
                    'label' => trans('hremployees.date_ad'),
                    'attributes' => [
                        'id' => 'date-ad',
                    ],
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-4',
                    ],
                ],
   
                // [
                //     'name' => 'nmc_nhpc_number',
                //     'type' => 'text',
                //     'label' => trans('NMC/NHPC no.'),
                //      'wrapper' => [
                //          'class' => 'form-group col-md-3',
                //      ],
                //      'attributes'=>[
                //         'maxlength' => '32',
                //      ],
                // ],
               
                [
                    'name' => 'qualification',
                    'type' => 'text',
                    'label' => trans('hremployees.qualification'),
                     'wrapper' => [
                         'class' => 'form-group col-md-4',
                     ],
                     'attributes'=>[
                        'required' => 'Required',
                        'maxlength' => '200',
                     ],
                ],
       
                [
                    'name'=>'department_id',
                    'type'=>'relationship',
                    'label'=>trans('hremployees.department'),
                    'entity'=>'department',
                    'model'=>HrMstDepartments::class,
                    'attribute'=>'title',
                    'inline_create'=>[
                        'entity'=>'hrmstdepartments',
                        'modal_class' => 'modal-dialog modal-xl',
                    ],
                    'data_source' => '/admin/hrmstemployees/fetch/hrmstdepartments',
                    'wrapper' => [
                        'class' => 'form-group col-md-4',
                    ],         
                ],

                [
                    'name'=>'sub_department_id',
                    'type'=>'relationship',
                    'label'=>'Sub Department',
                    'entity'=>'subDepartment',
                    'model'=>HrMstSubDepartments::class,
                    'minimum_input_length' => 0,
                    'attribute'=>'title',
                    'dependencies'   => ['department_id'],
                    'inline_create'=>[
                        'entity'=>'hrmstdepartments/{department_id}/hrmstsubdepartments',
                        'modal_class' => 'modal-dialog modal-xl',
                    ],
                    'data_source' => url("api/department/department_id"),
                    'wrapper' => [
                        'class' => 'form-group col-md-4',
                    ],
                ],

                [ //Toggle
                    'name' => 'is_other_country',
                    'label' => trans('Are you from Nepal? (Please specify city if selected other)'),
                    'type' => 'toggle',
                    'options'     => [ 
                        0 => 'Nepal',
                        1 => 'Others'
                    ],
                    'inline' => true,
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-10',
                    ],
                    'attributes' =>[
                        'id' => 'is_other_country',
                    ],
                    'hide_when' => [
                        0 => ['country_id'],
                        1 => ['province_id','district_id','local_level_id','ward_no'],
                    ],
                    'default' => 0,
                ],
                [
                    'name'=>'province_id',
                    'type'=>'select2',
                    'label'=>trans('hremployees.province'),
                    'entity'=>'province',
                    'model'=>MstFedProvince::class,
                    'attribute'=>'name',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3',
                    ],
                ],
                [
                    'name'=>'district_id',
                    'label'=>trans('hremployees.district'),
                    'type'=>'select2_from_ajax',
                    'model'=>MstFedDistrict::class,
                    'entity'=>'district',
                    'attribute'=>'name',
                    'method'=>'post',
                    // 'include_all_form_fields'=>false,
                    'data_source' => url("api/district/province_id"),
                    'minimum_input_length' => 0,
                    'dependencies'=> ['province_id'],
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3',
                    ],
                    'attributes' => [
                        'id' => 'district_id',
                        'placeholder' => "Select a District",
                    ],
                ],
                [
                    'name'=>'local_level_id',
                    'label'=>trans('hremployees.locallevel'),
                    'type'=>'select2_from_ajax',
                    'entity'=>'locallevel',
                    'model'=>MstFedLocalLevel::class,
                    'attribute'=>'name',
                    'method'=>'post',
                    // 'include_all_form_fields'=>false,
                    'data_source' => url("api/locallevel/district_id"),
                    'minimum_input_length' => 0,
                    'dependencies'         => ['district_id'],
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3',
                    ],
                    'attributes' => [
                        'id' => 'local_level_id',
                        'placeholder' => "Select a Local Level",
                    ],

                ],

                [
                    'name'=>'country_id',
                    'type'=>'relationship',
                    'label'=>trans('hremployees.country'),
                    'entity'=>'country',
                    'model'=>MstCountry::class,
                    'attribute'=>'name',
                    'inline_create'=>[
                        'entity'=>'mstcountry',
                        'modal_class' => 'modal-dialog modal-xl',
                    ],
                    'data_source' => '/admin/hrmstemployees/fetch/mstcountry',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3',
                    ],
                ],
                [
                    'name'=>'ward_no',
                    'type'=>'number',
                    'label'=>trans('Ward No.'),
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3',
                    ],
                    'attributes'=> [
                        'id' => 'ward_no',
                    ]
                ],
    
                [
                    'name'=>'address',
                    'type'=>'text',
                    'label'=>trans('hremployees.address'),
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-4',
                    ],
                    'attributes'=> [
                        'id' => 'address',
                    ]
                ],
      
                [
                    'name' => 'mobile',
                    'type' => 'text',
                    'label' => trans('hremployees.home_phone'),
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-4',
                    ],
                    'attributes'=>[
                        'maxlength' => '10',
                     ],
                ],
                [
                    'name' => 'email',
                    'type' => 'text',
                    'label' => trans('hremployees.email'),
                    'attributes'=>[
                        'required'=>'required'
                    ],
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-4',
                    ],
                ],
            [
                'name' => 'document',
                'label' => trans('Documents'),
                'type' => 'upload_multiple',
                'upload' => true,
                'disk' => 'uploads',
                'wrapper' => [
                    'class' => 'form-group col-md-12',
                ],
            ],
            [
                'name' => 'legend3',
                'type' => 'custom_html',
                'value' => '<h4 class="bg-secondary p-2" style="color:blue; width:150px;">Authority</h4>',
            ],
            [
                'name' => 'is_discount_approver',
                'type' => 'checkbox',
                'label' => trans('<span class="font-weight-bold pl-1">Discount Approver</span>'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'is_credit_approver',
                'type' => 'checkbox',
                'label' => trans('<span class="font-weight-bold pl-1">Credit Approver</span>'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'is_result_approver',
                'type' => 'checkbox',
                'label' => trans('<span class="font-weight-bold pl-1">Result Approver</span>'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'legend4',
                'type' => 'custom_html',
                'value' => '<br>',
            ],
            [ //Toggle
                'name' => 'allow_user_login',
                'label' => trans('<b>Allow Application Login Access</b>'),
                'type' => 'toggle',
                'options'     => [ 
                    0 => 'No',
                    1 => 'Yes'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'hide_when' => [
                    0 => ['role_id'],
                    1 => [],
                ],
                'default' => 0,
                'inline' => true,

            ],
            [
                'name'=>'role_id',
                'type'=>'select2',
                'label'=>trans('Role'),
                'entity'=>'roleEntity',
                'model'=>Role::class,
                'attribute'=>'field_name',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'legend5',
                'type' => 'custom_html',
                'value' => '<br>',
            ],
            [
                'name' => 'display_order',
                'type' => 'number',
                'label' => trans('common.display_order'),
                'default' => 0,
                'wrapper' => [
                    'class' => 'form-group col-md-3',
                ],
            ],
   
            $this->addIsActiveField(),
        ];

        $arr = array_filter($arr);
        $this->crud->addFields($arr);

    }

    public function store()
    {
        $this->crud->hasAccessOrFail('create');
        $user = backpack_user();

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();
        
        if ($request->has('emp_no')) {
            $query = $this->crud->model->latest('emp_no')->first();
            $emp_no = 1;
            if ($query != null) {
                $emp_no = $query->emp_no + 1;
            }
            $request->request->set('emp_no', $emp_no);
        }

        $user_id = backpack_user()->id;
        $request->request->set('created_by', $user_id);
        // dd($request);

        // insert item in the db
        DB::beginTransaction();
        try {
            $item = $this->crud->create($request->except(['save_action', '_token', '_method', 'http_referrer']));
            $this->data['entry'] = $this->crud->entry = $item;

            if($request->allow_user_login == "1"){
                $user = User::updateOrCreate(
                        ['employee_id'=>$item->id],
                        ['client_id' => backpack_user()->client_id,
                        'name' => $request->full_name,
                        'email' => isset($request->email) ?$request->email : str_replace(' ','_',mb_strtolower($request->full_name)).'@gmail.com' ,
                        'password' => bcrypt('User@1234'),
                        ]);
                        //insert or update role after user creation
                        DB::table('model_has_roles')->updateOrInsert([
                            'role_id' => $request->role_id,
                            'model_type' => 'App\Models\User',
                            'model_id' => $user->id,
                        ]);

            }
            DB::commit();

        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
        }

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();
        return redirect(backpack_url('hrmstemployees'));
    }

    public function update()
    {
        $this->crud->hasAccessOrFail('update');
        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();
        // insert item in the db
        DB::beginTransaction();
        try {
            $item = $this->crud->update(
                $request->get($this->crud->model->getKeyName()),
                $request->except(['save_action', '_token', '_method', 'http_referrer'])
            );
            $this->data['entry'] = $this->crud->entry = $item;

            // if($request->allow_user_login == "1"){
            //     $user = User::updateOrCreate(
            //             ['employee_id'=>$item->id],
            //             ['client_id' => backpack_user()->client_id,
            //             'name' => $request->full_name,
            //             'email' => isset($request->email) ?$request->email : str_replace(' ','_',mb_strtolower($request->full_name)).'@gmail.com' ,
            //             'password' => bcrypt('User@1234'),
            //             ]);
            //             //insert or update role after user creation
            //             DB::table('model_has_roles')->updateOrInsert([
            //                 'role_id' => $request->role_id,
            //                 'model_type' => 'App\Models\User',
            //                 'model_id' => $user->id,
            //             ]);

            // }
            DB::commit();

        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
        }

        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();
        return redirect(backpack_url('hrmstemployees'));
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function fetchEmail(Request $request)
    {
        $employeeId = $request->employeeId;
        
        $employee =DB::table('hr_mst_employees')->select(['full_name','email'])->where('id',$employeeId)->get()->first();
        return response()->json([
            'message'=>'success',
            'user' => $employee,
        ]);

    }

    public function fetchHrmstdepartments()
    {   
        return $this->fetch(['model'=>HrMstDepartments::class,'searchable_attributes' => ['title']]);
    }

    public function fetchMstcountry()
    {   
        return $this->fetch(['model'=>MstCountry::class,'searchable_attributes' => ['name']]);
    }

    public function fetchHrmstsubdepartments()
    {   
        return $this->fetch(['model'=>HrMstSubDepartments::class,'searchable_attributes' => ['name']]);
    }

}
