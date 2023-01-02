<?php

namespace App\Http\Controllers\Admin\CoreMaster;

use App\Base\BaseCrudController;
use App\Http\Requests\CoreMaster\MstGenderRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MstGenderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MstGenderCrudController extends BaseCrudController
{
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\CoreMaster\MstGender::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mst-gender');
        CRUD::setEntityNameStrings(trans('menu.gender'), trans('menu.gender'));
        $this->crud->clearFilters();
        $this->setFilters();
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setFilters(){

        $this->crud->addFilter(
            [
                'type' => 'text',
                'name' => 'name',
                'label'=> 'Gender'
            ], 
            false, 
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'name', 'iLIKE', "%$value%");
            }
        );
    }
    protected function setupListOperation()
    {
        $cols =  [
            $this->addRowNumberColumn(),
            $this->addNameColumn(),
         ];
         
         $this->crud->addColumns($cols);
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
        CRUD::setValidation(MstGenderRequest::class);
        $fields =  [
            $this->addCodeField(),
            $this->addPlainHtml(),
            $this->addNameField(),
        ];
        
        $this->crud->addFields($fields);
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
