<?php

namespace App\Http\Controllers\Admin\CoreMaster;

use App\Base\BaseCrudController;
use App\Models\CoreMaster\MstFedLocalLevelType;
use App\Models\CoreMaster\MstFedProvince;
use App\Models\CoreMaster\MstFedLocalLevel;
use App\Http\Requests\CoreMaster\MstFedLocalLevelRequest;
use App\Models\CoreMaster\MstFedDistrict;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MstFedLocalLevelCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MstFedLocalLevelCrudController extends BaseCrudController
{
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {

        CRUD::setModel(\App\Models\CoreMaster\MstFedLocalLevel::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mst-fed-local-level');
        CRUD::setEntityNameStrings(trans('menu.localLevel'), trans('menu.localLevel'));
        $this->crud->clearFilters();
        $this->setFilters();
    }

    // public function setFilters()
    // {
    //     return  $this->crud->addFilter(
    //         [ 
    //             'name'        => 'province_id',
    //             'type'        => 'select2',
    //             'label'       => trans('Province'),
    //             'placeholder' => '-select province--',
    //         ],
    //         function () {
    //             return (new MstFedProvince())->getFilterComboOptions();
    //         },
    //         function ($value) { // if the filter is active
    //             $district_ids = MstFedDistrict::whereProvinceId($value)->pluck('id')->toArray();
    //             $this->crud->addClause('whereIn', 'district_id',$district_ids);
    //         }
    //     );
    // }

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
                'label'=> 'Local level Name'
            ], 
            false, 
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'name', 'iLIKE', "%$value%");
            }
        );
    }
    protected function setupListOperation()
    {
        $cols=[
            $this->addRowNumberColumn(),
            $this->addDistrictColumn(),
            $this->addLocalLevelColumn(),
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
        CRUD::setValidation(MstFedLocalLevelRequest::class);

        $arr = [
            $this->addReadOnlyCodeField(),
            $this->addPlainHtml(),
            [
                'name' => 'district_id',
                'type' => 'select2',
                'entity' => 'districtEntity',
                'attribute' => 'name',
                'model' => MstFedDistrict::class,
                'label' => 'District',
                'options'   => (function ($query) {
                    return (new MstFedDistrict())->getFieldComboOptions($query);
                }),
                'wrapper' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
            [
                'name' => 'level_type_id',
                'type' => 'select2',
                'entity' => 'levelTypeEntity',
                'attribute' => 'name',
                'model' => MstFedLocalLevelType::class,
                'label' => 'Local Level',
                'options'   => (function ($query) {
                    return (new MstFedLocalLevelType())->getFieldComboOptions($query);
                }),
                'wrapper' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
            
            [
                'name' => 'name',
                'label' => trans('common.name'),
                'type' => 'text',
                'attributes' => [
                    'id' => 'name',
                    'required' => 'required',
                    'max-length' => 200,
                ],
                'wrapper' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
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
