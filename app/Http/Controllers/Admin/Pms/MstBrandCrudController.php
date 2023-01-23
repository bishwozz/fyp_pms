<?php

namespace App\Http\Controllers\Admin\Pms;
use App\Base\Traits\UserLevelFilter;


use App\Models\MstBrand;
use App\Base\BaseCrudController;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MstBrandCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MstBrandCrudController extends BaseCrudController
{


    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Pms\MstBrand::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mstbrand');
        CRUD::setEntityNameStrings('', 'Brands');
        $this->user = backpack_user();
        $this->crud->clearFilters();
        $this->setFilters();
    }

    protected function setFilters(){
        $this->crud->addFilter(
            [ // simple filter
                'type' => 'text',
                'name' => 'name_en',
                'label' => 'Btand Name'
            ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'name_en', '=', "$value");
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

        $cols =  [
            $this->addRowNumberColumn(),
            $this->addNameEnColumn(),
            [
                'name' => 'description',
                'type' => 'textarea',
                'label' => 'Description',


            ],
            $this->addIsActiveColumn()

        ];

        $this->crud->addColumns(array_filter($cols));
        if(!$this->user->isSystemUser()){
            $this->crud->addButtonFromView('top', 'fetchMasterData', 'fetchMasterData', 'end');
        }




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
        // CRUD::setValidation(MstBrandRequest::class);

        $fields =  [
            $this->addReadOnlyCodeField(),
            $this->addPlainHtml(),
            $this->addNameEnField(),
            $this->addNameLcField(),
            $this->addClientIdField(),
            [
                'name' => 'description',
                'type' => 'textarea',
                'label' => 'Description',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-9'
                ]

            ],
            $this->addIsActiveField(),

        ];
		$this->crud->addFields(array_filter($fields));
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
