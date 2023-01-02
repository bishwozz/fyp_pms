<?php

namespace App\Http\Controllers\Admin\Lab;

use App\Models\Interpretation;
use App\Base\BaseCrudController;
use App\Http\Requests\InterpretationRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class InterpretationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class InterpretationCrudController extends BaseCrudController
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
        CRUD::setModel(\App\Models\Lab\Interpretation::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/lab/interpretation');
        CRUD::setEntityNameStrings('interpretation', 'interpretations');
        $this->crud->addClause('where','client_id',$this->user->client_id);
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
                'name' => 'name',
                'type' => 'text',
                'label' => trans('lab.group_name'),
            ],
            [
                'name' => 'description',
                'label' => trans('Description'),
                'type' => 'text',
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
        CRUD::setValidation(InterpretationRequest::class);
        $arr = [
            $this->addClientIdField(),
            [
                'name' => 'name',
                'type' => 'text',
                'label' => 'Name',
                 'wrapperAttributes' => [
                     'class' => 'form-group col-md-12',
                 ],
                 'attributes'=>[
                    'required' => 'Required',
                    'maxlength' => '100',
                 ],
            ],
            [
                'name' => 'description',
                'type' => 'summernote',
                'label' => 'Description',
                 'wrapperAttributes' => [
                     'class' => 'form-group col-md-6',
                 ],
                 'attributes'=>[
                    'required' => 'Required',
                    'minlength' => '3',
                 ],
            ]
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
        if($this->user->isClientUser()){
            $request->request->set('client_id',$this->user->client_id);
        }

        $request= $request->except('_token','_save_and_back','_http_referrer');
        // insert item in the db
        $item = $this->crud->create($request);
        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }
}
