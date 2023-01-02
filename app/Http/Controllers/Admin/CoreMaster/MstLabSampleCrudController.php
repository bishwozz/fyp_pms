<?php

namespace App\Http\Controllers\Admin\CoreMaster;

use App\Base\BaseCrudController;
use App\Models\CoreMaster\MstLabSample;
use App\Http\Requests\CoreMaster\MstLabSampleRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;


class MstLabSampleCrudController extends BaseCrudController
{
    public function setup()
    {
        CRUD::setModel(MstLabSample::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mst-lab-sample');
        CRUD::setEntityNameStrings(trans('menu.labsample'), trans('menu.labsample'));
        $this->crud->clearFilters();
        $this->setFilters();
    }

    private function setFilters(){

        $this->crud->addFilter(
            [ // simple filter
                'type' => 'text',
                'name' => 'name',
                'label' => 'Sample Name'
            ],
            false,
            function($value) { 
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
        CRUD::setValidation(MstLabSampleRequest::class);

        $arr = [
            $this->addCodeField(),
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
