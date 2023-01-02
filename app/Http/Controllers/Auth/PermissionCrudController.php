<?php

namespace App\Http\Controllers\Auth;

use App\Models\Permission;
use App\Base\BaseCrudController;
use Doctrine\Inflector\Inflector;
use Illuminate\Support\Facades\DB;
use app\Base\Operations\CreateOperation;
use app\Base\Operations\UpdateOperation;
use Backpack\PermissionManager\app\Http\Requests\PermissionStoreCrudRequest as StoreRequest;
use Backpack\PermissionManager\app\Http\Requests\PermissionUpdateCrudRequest as UpdateRequest;

class PermissionCrudController extends BaseCrudController
{
    use CreateOperation { store as traitStore; }
    use UpdateOperation { update as traitUpdate; }

    public function setup()
    {
        $this->role_model = $role_model = config('backpack.permissionmanager.models.role');
        $this->permission_model = $permission_model = config('backpack.permissionmanager.models.permission');

        $this->crud->setModel($permission_model);
        $this->crud->setEntityNameStrings(trans('menu.permission'), trans('menu.permission'));
        $this->crud->setRoute(backpack_url('permission'));

        $this->crud->setListView('custom_permission_list');

        // deny access according to configuration file
        if (config('backpack.permissionmanager.allow_permission_create') == false) {
            $this->crud->denyAccess('create');
        }
        if (config('backpack.permissionmanager.allow_permission_update') == false) {
            $this->crud->denyAccess('update');
        }
        if (config('backpack.permissionmanager.allow_permission_delete') == false) {
            $this->crud->denyAccess('delete');
        }
    }

    public function setupListOperation()
    {
   
        $this->crud->addColumn($this->addRowNumberColumn());
        $this->crud->addColumn([
            'name'  => 'name',
            'label' => trans('backpack::permissionmanager.name'),
            'type'  => 'text',
        ]);

        if (config('backpack.permissionmanager.multiple_guards')) {
            $this->crud->addColumn([
                'name'  => 'guard_name',
                'label' => trans('backpack::permissionmanager.guard_type'),
                'type'  => 'text',
            ]);
        }
        $this->crud->orderBy('created_at','DESC');
    }

    public function setupCreateOperation()
    {
        // $this->addFields();
        // $this->crud->setValidation(StoreRequest::class);

        //otherwise, changes won't have effect
        \Cache::forget('spatie.permission.cache');
    }

    public function setupUpdateOperation()
    {
        // $this->addFields();
        $this->crud->setValidation(UpdateRequest::class);

        //otherwise, changes won't have effect
        \Cache::forget('spatie.permission.cache');
    }

    private function addFields()
    {
        $this->crud->addField([
            'name'=>'entity',
            'label'=>'Create Permissions For',
            'type'=>'select_from_array',
            'options'=>modelCollection(),
            'validationRules' => 'required',
            'validationMessages' => [
                'required' => ' The Create Permission For field is required.',
            ],
            'wrapper'=>[
                'class' => 'form-group col-md-6 required',
            ]
        ]);
        
        if (config('backpack.permissionmanager.multiple_guards')) {
            $this->crud->addField([
                'name'    => 'guard_name',
                'label'   => trans('backpack::permissionmanager.guard_type'),
                'type'    => 'select_from_array',
                'options' => $this->getGuardTypes(),
            ]);
        }
        $this->crud->setValidation();
    }

    /*
     * Get an array list of all available guard types
     * that have been defined in app/config/auth.php
     *
     * @return array
     **/
    private function getGuardTypes()
    {
        $guards = config('auth.guards');

        $returnable = [];
        foreach ($guards as $key => $details) {
            $returnable[$key] = $key;
        }

        return $returnable;
    }

    public function store(){
        $this->crud->hasAccessOrFail('create');
        $request = $this->crud->validateRequest();

        $menu_items = modelCollection();
        $menu = $menu_items[$request->entity];

        $link = Inflector::tableize($menu);        
        $link = \str_replace('_','-',$link);

        DB::beginTransaction();
        try {
            $items = $request->except(['save_action', '_token', '_method', 'http_referrer']);

            $array_value = ['list','create','update','delete'];

            foreach ($array_value as $value) {
                //check if permission already exists, if not then create 
                $item = Permission::firstOrCreate(
                    ['name'=>$value . ' ' . $items['entity'],
                    'guard_name'=>'backpack'],
                );
            }

            \Alert::success(trans('backpack::crud.insert_success'))->flash();

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
        }

        $this->crud->setSaveAction();

        // return redirect(backpack_url('permission'));
        return $this->crud->performSaveAction($item->getKey());
    }
}
