<?php

namespace App\Http\Controllers\Admin\CoreMaster;

use App\Base\BaseCrudController;
use App\Http\Requests\CoreMaster\MstFiscalYearRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MstFiscalYearCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MstFiscalYearCrudController extends BaseCrudController
{
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\CoreMaster\MstFiscalYear::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mst-fiscal-year');
        CRUD::setEntityNameStrings(trans('menu.fiscalYear'), trans('menu.fiscalYear'));
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
                'name' => 'code',
                'label'=> 'Fiscal Year Code'
            ], 
            false, 
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'code', 'iLIKE', "%$value%");
            }
        );
    }
    protected function setupListOperation()
    {
        $col = [
            $this->addRowNumberColumn(),
            [
                'name' => 'code',
                'type' => 'text',
                'label' => trans('common.code'),
            ],
            [
                'name'=>'from_date_bs',
                'label'=> trans('common.date_from_bs'),
            ],

            [
                'name'=>'to_date_bs',
                'label'=> trans('common.date_to_bs'),
            ],

            ];
            $this->crud->addColumns($col);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(MstFiscalYearRequest::class);

        $arr = [
            [
                'name' => 'code',
                'label' => trans('common.code'),
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes'=>[
                    'placeholder' => '20_ _/_ _'
                ]
            ],
            $this->addPlainHtml(),
            [
                'name' => 'from_date_bs',
                'type' => 'nepali_date',
                'label' => trans('common.date_from_bs'),
                'attributes'=>
                [
                    'id'=>'from_date_bs',
                    'relatedId' =>'from_date_ad',
                    'maxlength' =>'10',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
            ],

            [
                'name' => 'from_date_ad',
                'type' => 'date',
                'label' => trans('common.date_from_ad'),
                'attributes'=>
                [
                'id'=>'from_date_ad',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
            ],
            [
                'name' => 'to_date_bs',
                'type' => 'nepali_date',
                'label' => trans('common.date_to_bs'),
                'attributes'=>
                [
                    'id'=>'to_date_bs',
                    'relatedId' => 'to_date_ad',
                    'maxlength' =>'10',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
            ],
            [
                'name' => 'to_date_ad',
                'type' => 'date',
                'label' => trans('common.date_to_ad'),
                'attributes'=>[
                    'id'=>'to_date_ad'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
            ],

    ];

    $this->crud->addFields($arr);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
