<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\AppClient;
use App\Base\BaseCrudController;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use App\Models\CoreMaster\AppSetting;
use App\Http\Requests\AppClientRequest;
use App\Models\CoreMaster\MstFedLocalLevel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AppClientCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AppClientCrudController extends BaseCrudController
{
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\AppClient::class);
        CRUD::setRoute('admin/appclient');
        CRUD::setEntityNameStrings(trans('appClient.title_text'), trans('appClient.title_text'));
        $this->crud->clearFilters();
        $this->setFilters();
    }

    protected function setFilters(){
        $this->crud->addFilter(
            [ // simple filter
                'type' => 'text',
                'name' => 'code',
                'label' => 'Code'
            ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'code', '=', "$value");
            }
        );
        $this->crud->addFilter(
            [
                'type' => 'text',
                'name' => 'name',
                'label'=> 'Client Name'
            ], 
            false, 
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'name', 'iLIKE', "%$value%");
            }
        );
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
            $this->addCodeColumn(),
            [
                'name'=>'name',
                'label'=>trans('appClient.name'),
            ],
            [
                'label'=>trans('appClient.fed_local_level'),
                'type' => 'select',
                'name' => 'fed_local_level_id', 
                'entity' => 'fed_local_level', 
                'attribute' => 'name', 
                'model' => MstFedLocalLevel::class,
            ],
            [
                'name'=>'admin_email',
                'label'=>trans('appClient.admin_email'),
            ],
            [
                'name'=>'short_name',
                'label'=>trans('appClient.short_name'),
            ],
            [
                'name'=>'prefix_key',
                'label'=>trans('appClient.prefix_key'),
            ],
            // [
            //     'name' => 'remarks',
            //     'label' => trans('appClient.remarks')
            // ]
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
        $this->crud->setValidation(AppClientRequest::class);
        $arr=[
            $this->addCodeField(),
            [
                'name'=>'name',
                'type'=>'text',
                'label'=>trans('appClient.name'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name'=>'short_name',
                'type'=>'text',
                'label'=>trans('appClient.short_name'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name'=>'prefix_key',
                'type'=>'text',
                'label'=>trans('appClient.prefix_key'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'label'=>trans('appClient.fed_local_level'),
                'type' => 'select2',
                'name' => 'fed_local_level_id', 
                'entity' => 'fed_local_level', 
                'attribute' => 'name', 
                'model' => MstFedLocalLevel::class,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name'=>'admin_email',
                'type'=>'text',
                'label'=>trans('appClient.admin_email'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            $this->addRemarksField(),
            $this->addIsActiveField(),
        ];
        $this->crud->addFields($arr); 
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
        $user = backpack_user();

        $request = $this->crud->validateRequest();

        $request->request->set('created_by', $user->id);
        $request->request->set('updated_by', $user->id);
        $current_date = Carbon::now()->todatetimestring();

        DB::beginTransaction();
        try {
                $item = $this->crud->create($request->except(['save_action', '_token', '_method', 'http_referrer']));  
                $user = User::create([
                    'name' => $request->get('name') . '_admin',
                    'email' =>  $request->get('admin_email'),
                    'password' => bcrypt('Admin@1234'),
                    'client_id' => $item->id
                ]);

                AppSetting::create([
                    'client_id'=>$item->id,
                    'office_name'=>$request->name,
                    'address_name'=>'office address',
                    'created_by'=>$user->id,
                    'updated_by'=>$user->id,
                    'created_at'=>$current_date,
                    'updated_at'=>$current_date,
                ]);
            
                $hospitaladmin_id = DB::table('users')->where('id', $user->id)->pluck('id')->first();
                $user->assignRoleCustom("clientadmin",$hospitaladmin_id);
                
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
}
