<?php

namespace App\Http\Controllers\Admin\CoreMaster;

use App\Base\BaseCrudController;
use App\Models\CoreMaster\MstCountry;
use App\Http\Requests\CoreMaster\MstCountryRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;



class MstCountryCrudController extends BaseCrudController
{
    public function setup()
    {
        $this->crud->setModel(MstCountry::class);
        $this->crud->setRoute('admin/mstcountry');
        $this->crud->setEntityNameStrings(trans('country.title_text'), trans('country.title_text'));
        $this->crud->clearFilters();
        $this->crud->addFilter(
            [
                'type' => 'text',
                'name' => 'name',
                'label' => 'Name'
            ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'name', 'ILIKE', "%$value%");
            }
        );
    }

    protected function setupListOperation()
    {
        $col = [
            $this->addRowNumber(),
            $this->addCodeColumn(),
          
            [
                'name' => 'name',
                'type' => 'text',
                'label' => trans('country.name_en'),
            ],
        ];
        $this->crud->addColumns($col);
        $this->crud->orderBy('display_order'); 
    }


    protected function setupCreateOperation()
    {
        $this->crud->setValidation(MstCountryRequest::class);

        $arr=[
         
            $this->addCodeField(),
            [
                'name' => 'name',
                'type' => 'text',
                'label' => trans('country.name_en'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
            ],

            $this->addDisplayOrderField(),
        ];
        $this->crud->addFields($arr);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

}
