<?php

namespace App\Http\Controllers\Admin\CoreMaster;

use App\Base\BaseCrudController;
use App\Models\CoreMaster\MstLabMethod;
use App\Http\Requests\CoreMaster\MstLabMethodRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;


class MstLabMethodCrudController extends BaseCrudController
{
    public function setup()
    {
        CRUD::setModel(MstLabMethod::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mst-lab-method');
        CRUD::setEntityNameStrings(trans('menu.labmethod'), trans('menu.labmethod'));
        $this->crud->clearFilters();
        $this->setFilters();
    }

    private function setFilters(){

        $this->crud->addFilter(
            [ // simple filter
                'type' => 'text',
                'name' => 'name',
                'label' => 'Method Name'
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
        CRUD::setValidation(MstLabMethodRequest::class);

        $arr = [
            $this->addCodeField(),
            $this->addNameField(),
        ];
        $this->crud->addFields($arr);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
