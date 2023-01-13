<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use App\Base\BaseCrudController;
use App\Base\Traits\CheckPermission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\UserCreateRequest;
use App\Http\Requests\Auth\UserUpdateRequest;
use App\Models\HrMaster\HrMstEmployees;

// 

class UserCrudController extends BaseCrudController
{
    use CheckPermission;
    public function setup()
    {
        $this->crud->setModel(config('backpack.permissionmanager.models.user'));
        $this->crud->setEntityNameStrings(trans('menu.user'), trans('menu.user'));
        $this->crud->setRoute('admin/user/'.$this->parent('custom_param'));
        $this->checkPermission();
        $this->setCustomTabLinks();

        /*
            To list the user lower than its heirarchy
        */
        if(!backpack_user()->hasRole('superadmin')){
            if(backpack_user()->hasRole('clientadmin')){

                $this->crud->query->where('id','<>',1);
                $ids = DB::table('model_has_roles')
                            ->where('role_id',2)
                            ->where('model_id','<>',backpack_user()->id)
                            ->pluck('model_id');
                $this->crud->query->where('id','<>',1);
                $this->crud->query->whereNotIn('id',$ids);
            }else{
                if(backpack_user()->hasRole('admin')){
                    $this->crud->query->whereNotIn('id',[1,2]);

                    // $this->crud->addClause('where', 'id','<>',1);
                    // $this->crud->addClause('where', 'id','<>',2);
                }elseif(backpack_user()->hasRole('lab_admin')){
                    $this->crud->query->whereNotIn('id',[1,2,3,4]);
                    // $this->crud->addClause('where', 'id','<>',1);
                    // $this->crud->addClause('where', 'id','<>',2);
                    // $this->crud->addClause('where', 'id','<>',3);
                    // $this->crud->addClause('where', 'id','<>',4);
                }
                else{
                    $this->crud->query->whereNotIn('id',[1,2,3,4,5]);
                    // $this->crud->addClause('where', 'id','<>',1);
                    // $this->crud->addClause('where', 'id','<>',2);
                    // $this->crud->addClause('where', 'id','<>',3);
                    // $this->crud->addClause('where', 'id','<>',4);
                    // $this->crud->addClause('where', 'id','<>',5);
                }
            }
        }
        $this->crud->clearFilters();
        $this->setFilters();
        $this->processCustomParams();

    }

    protected function setCustomTabLinks()
    {
        $this->data['list_tab_header_view'] = 'tab.custom_tab_links';

        $links[] = ['label' => 'Main', 'icon' => 'la la-cogs', 'href' => backpack_url('user/main')];

        $this->data['links'] = $links;
    }
    protected function processCustomParams()
    {
            
        $custom_param = $this->parent('custom_param');
    
        switch ($custom_param) {
            case 'main':
            break;

            case 'patients':
                $this->crud->query->whereNotNull('patient_id');
            break;

            default:
            break;


        }
        // $this->crud->orderby('created_at','DESC');


    }

    public function setFilters()
    {
        $this->crud->addFilter(
            [ // Name(en) filter`
                'label' => trans('Role'),
                'type' => 'select2',
                'name' => 'roles', // the db column for the foreign key
            ],
            function () {
                return Role::where('id','<>',1)->pluck('field_name', 'id')->toArray();
            },
            function ($value) { 
                if($value){
                    $user_ids = DB::table('model_has_roles')->where('role_id',$value)->pluck('model_id');
                }
                $this->crud->addClause('whereIn', 'id',$user_ids);
            }
        );
    }
    public function setupListOperation()
    {
        $cols = [
            $this->addRowNumberColumn(),
            $this->addClientIdColumn(),
            [
                'label'=>trans('Employee'),
                'type' => 'select',
                'name' => 'empoyee_id', 
                'entity' => 'employeeEntity', 
                'attribute' => 'full_name', 
                'model' => HrMstEmployees::class,
            ],
          
            [
                'name'  => 'name',
                'label' => trans('backpack::permissionmanager.name'),
                'type'  => 'text',
            ],
            [
                'name'  => 'username',
                'label' => trans('Username'),
                'type'  => 'text',
            ],
            [
                'name'  => 'email',
                'label' => trans('backpack::permissionmanager.email'),
                'type'  => 'email',
            ],
            [ // n-n relationship (with pivot table)
                'label'     => trans('backpack::permissionmanager.roles'), // Table column heading
                'type'      => 'select_multiple',
                'name'      => 'roles', // the method that defines the relationship in your Model
                'entity'    => 'roles', // the method that defines the relationship in your Model
                'attribute' => 'field_name', // foreign key attribute that is shown to user
                'model'     => config('permission.models.role'), // foreign key model
            ],
            // [ 
            //     'label'     => trans('Last Login'), // Table column heading
            //     'type'      => 'text',
            //     'name'      => 'last_login', // the method that defines the relationship in your Model
                
            // ],
        ];

        $cols = array_filter($cols);

        $this->crud->addColumns($cols);
    }

    public function addFields()
    {
      $arr = [
            $this->addClientIdField(),
            [
                'label'=>trans('Employee'),
                'type' => 'select2',
                'name' => 'empoyee_id', 
                'entity' => 'employeeEntity', 
                'attribute' => 'full_name', 
                'model' => HrMstEmployees::class,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],

            [
                'type' => 'custom_html',
                'name'=>'custom_html_1',
                'value' => '<br/>',
            ],

            [
                'name'  => 'name',
                'label' => trans('backpack::permissionmanager.name'),
                'type'  => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
                'attributes'=>[
                    'id'=>'full_name',
                ], 
            ],
            
           
            [
                'name'  => 'email',
                'label' => trans('backpack::permissionmanager.email'),
                'type'  => 'email',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
                'attributes'=>[
                    'id'=>'email',
                ], 
            ],
            [
                'name'  => 'password',
                'label' => trans('backpack::permissionmanager.password'),
                'type'  => 'password',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
            [
                'name'  => 'password_confirmation',
                'label' => trans('backpack::permissionmanager.password_confirmation'),
                'type'  => 'password',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
            [
                'type' => 'custom_html',
                'name' => 'custom_html_2',
                'value' => '<br/>',
            ],
            [
                'label' => 'Is Discount approver',
                'type' => 'checkbox',
                'name' => 'is_discount_approver',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-2',
                ],
            ],
            [
                'label' => 'Is Due approver',
                'type' => 'checkbox',
                'name' => 'is_due_approver',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-2',
                ],
            ],

            [
                'label' => 'Is Stock approver',
                'type' => 'checkbox',
                'name' => 'is_stock_approver',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-2',
                ],
            ],

            [
                'label' => 'Is Po approver',
                'type' => 'checkbox',
                'name' => 'is_po_approver',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-2',
                ],
            ],
            [
                'type' => 'custom_html',
                'name'=>'custom_html_2',
                'value' => '<br/>',
            ],
            [
                // two interconnected entities
                'label'             => trans('backpack::permissionmanager.user_role_permission'),
                'field_unique_name' => 'user_role_permission',
                'type'              => 'checklist_dependency_custom',
                'name'              => ['roles', 'permissions'],
                'subfields'         => [
                    'primary' => [
                        'label'            => trans('backpack::permissionmanager.roles'),
                        'name'             => 'roles', // the method that defines the relationship in your Model
                        'entity'           => 'roles', // the method that defines the relationship in your Model
                        'entity_secondary' => 'permissions', // the method that defines the relationship in your Model
                        'attribute'        => 'field_name', // foreign key attribute that is shown to user
                        'model'            => Role::class, // foreign key model
                        'pivot'            => true, // on create&update, do you need to add/delete pivot table entries?]
                        'number_columns'   => 4, //can be 1,2,3,4,6
                        'option' => $this->getPrivateRoles(), // to get custom roles that it is allowed to see

                    ],
                    'secondary' => [
                        'label'          => ucfirst(trans('backpack::permissionmanager.permission_singular')),
                        'name'           => 'permissions', // the method that defines the relationship in your Model
                        'entity'         => 'permissions', // the method that defines the relationship in your Model
                        'entity_primary' => 'roles', // the method that defines the relationship in your Model
                        'attribute'      => 'name', // foreign key attribute that is shown to user
                        'model'          => Permission::class, // foreign key model
                        'pivot'          => true, // on create&update, do you need to add/delete pivot table entries?]
                        'number_columns' => 4, //can be 1,2,3,4,6
                        'option' => $this->getPrivatePermissions(), //to get custom permissions that it is given

                    ],
                ],
            ],
        ];
        $arr = array_filter($arr);
        $this->crud->addFields($arr);
    }

    public function setupCreateOperation()
    {
        $this->crud->setValidation(UserCreateRequest::class);     
        $this->addFields();
    }
    
    public function setupUpdateOperation()
    {
        $this->crud->setValidation(UserUpdateRequest::class);
        $this->addFields();
    }

    // to fetch private roles
    public function getPrivateRoles()
    {
        if(backpack_user()->hasRole('superadmin')){
            return Role::all();
        }else{
            if(backpack_user()->hasRole('clientadmin')){
                return Role::where('id','<>',1)->get();
            }else{
                if(backpack_user()->hasRole('admin')){
                    return Role::where('id','<>',1)->where('id','<>',2)->get();
                }else{
                    return Role::where('id','<>',1)->where('id','<>',2)->where('id','<>',3)->get();
                }
            }
        }
    }

    // To fetch private permissions
    public function getPrivatePermissions(){
       $user = User::find(backpack_user()->id);
       
       if(backpack_user()->hasRole('superadmin')){
            return Permission::all();
       }else{
            $permissions = $user->getAllPermissions();
            return $permissions;
       }
    }
    


    public function store()
    {
        $this->crud->hasAccessOrFail('create');
        $user = backpack_user();

        $request = $this->crud->validateRequest();
        $request->request->set('created_by', $user->id);
        $request->request->set('updated_by', $user->id);
        if($user->isClientUser()){
            $request->request->set('client_id', $user->client_id);
        }
    
        //save full_name, email and password for sending email
        $email_details = [
            'full_name' => $request->name,
            'email' => $request->email,
            'password' =>$request->password,
        ];


        //encrypt password
        $request = $this->handlePasswordInput($request);

        DB::beginTransaction();
        try {
                $item = $this->crud->create($request->except(['save_action', '_token', '_method', 'http_referrer']));  
            
                if($item && env('SEND_MAIL_NOTIFICATION') == TRUE){
                    $this->send_mail($email_details);
                }

            // $this->client_user->notify(new TicketCreatedNotification($item));

            \Alert::success(trans('backpack::crud.insert_success'))->flash();

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
        }

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }


    public function update()
    {
        $this->crud->hasAccessOrFail('update');
        $user = backpack_user();

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();
        $request->request->set('updated_by', $user->id);

        //save full_name, email and password for sending email
        $email_details = [
            'full_name' => $request->name,
            'email' => $request->email,
            'password' =>$request->password,
        ];
        //encrypt password
        $request = $this->handlePasswordInput($request);

        DB::beginTransaction();
        try {
                $item = $this->crud->update($request->get($this->crud->model->getKeyName()),
                        $request->except(['save_action', '_token', '_method', 'http_referrer']));

                // if($item && env('SEND_MAIL_NOTIFICATION') == TRUE){
                //     $this->send_mail($email_details);
                // }
            \Alert::success(trans('backpack::crud.update_success'))->flash();

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
        }
        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    /**
     * Handle password input fields.
     */
    protected function handlePasswordInput($request)
    {
        // Encrypt password if specified.
        if ($request->input('password')) {
            $request->request->set('password', Hash::make($request->input('password')));
        } else {
            $request->request->remove('password');
        }

        return $request;
    }
   
}