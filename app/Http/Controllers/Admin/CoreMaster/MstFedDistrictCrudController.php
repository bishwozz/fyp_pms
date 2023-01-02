<?php

namespace App\Http\Controllers\Admin\CoreMaster;

use App\Base\BaseCrudController;
use App\Models\CoreMaster\MstFedProvince;
use App\Http\Requests\CoreMaster\MstFedDistrictRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MstFedDistrictCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MstFedDistrictCrudController extends BaseCrudController
{

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\CoreMaster\MstFedDistrict::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mst-fed-district');
        CRUD::setEntityNameStrings(trans('menu.district'), trans('menu.district'));
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
                'label'=> 'District Name'
            ], 
            false, 
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'name', 'iLIKE', "%$value%");
            }
        );
        $this->crud->addFilter(
            [ 
                'type' => 'select2',
                'name' => 'province_id',
                'label' => 'Province'
            ],
            function() {
                return MstFedProvince::all()->pluck('name', 'id')->toArray();
            },
            function($value) { 
                $this->crud->addClause('where', 'province_id', $value);
            }
        );
    }
    protected function setupListOperation()
    {
        $cols=[
            $this->addRowNumberColumn(),
            $this->addProvinceColumn(),
            $this->addNameColumn(),
        ];
        $this->crud->addColumns($cols);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(MstFedDistrictRequest::class);

        $arr = [
            $this->addReadOnlyCodeField(),
            $this->addPlainHtml(),
            $this->addProvinceField(),
            $this->addNameField(),
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
