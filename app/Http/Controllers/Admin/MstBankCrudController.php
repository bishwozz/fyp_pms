<?php

namespace App\Http\Controllers\Admin;

use App\Models\MstBank;
use App\Base\BaseCrudController;
use App\Http\Requests\MstBankRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MstBankCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MstBankCrudController extends BaseCrudController
{
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(MstBank::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mst-bank');
        CRUD::setEntityNameStrings('Bank', 'Banks');
        $this->crud->clearFilters();
        $this->setFilters();
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
                'type' => 'text',
                'name' => 'name',
                'label' => 'Bank Name'
            ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'name', 'ilike', "%$value%");
            }
        );
       
    }
    protected function setupListOperation()
    {
        $col = [
            $this->addRowNumber(),
            [
                'name' => 'code',
                'type' => 'text',
                'label' => "Code",
            ],

            [
                'name' => 'name',
                'type' => 'text',
                'label' => 'Name',
            ],
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
        CRUD::setValidation(MstBankRequest::class);
        $arr=[
            $this->addReadOnlyCodeField(),
           [
               'name' => 'name',
               'type' => 'text',
               'label' => 'Name',
                'wrapperAttributes' => [
                       'class' => 'form-group col-md-6',
                ],
                'attributes'=>[
                   'required' => 'Required',
                   'maxlength' => '200',
                ],
           ],
           $this->addDisplayOrderField(),

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
}
