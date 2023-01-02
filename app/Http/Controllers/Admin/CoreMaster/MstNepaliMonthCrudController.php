<?php

namespace App\Http\Controllers\Admin\CoreMaster;

use App\Base\BaseCrudController;
use App\Http\Requests\CoreMaster\MstNepaliMonthRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MstNepaliMonthCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MstNepaliMonthCrudController extends BaseCrudController
{
    public function setup()
    {
        CRUD::setModel(\App\Models\CoreMaster\MstNepaliMonth::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mst-nepali-month');
        CRUD::setEntityNameStrings(trans('menu.nepalimonth'), trans('menu.nepalimonth'));
        $this->crud->clearFilters();
        $this->setFilters();
    }
    protected function setFilters(){

        $this->crud->addFilter(
            [
                'type' => 'text',
                'name' => 'name',
                'label'=> 'Month Name'
            ], 
            false, 
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'name', 'iLIKE', "%$value%");
            }
        );
    }
    protected function setupListOperation()
    {
        $cols = [
            $this->addRowNumberColumn(),
            $this->addCodeColumn(),
            $this->addNameColumn(),
            ];
            $this->crud->addColumns($cols);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(MstNepaliMonthRequest::class);

        $arr = [
            $this->addReadOnlyCodeField(),
            $this->addPlainHtml(),
            $this->addNameField(),
        ];
        $this->crud->addFields($arr);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
