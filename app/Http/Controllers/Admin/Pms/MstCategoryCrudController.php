<?php

namespace App\Http\Controllers\Admin\Pms;

use App\Models\Pms\MstCategory;
use App\Base\BaseCrudController;
use App\Http\Requests\Pms\MstCategoryRequest;

class MstCategoryCrudController extends BaseCrudController
{
   public function setup()
    {
        $this->crud->setModel(MstCategory::class);
        $this->crud->setRoute('admin/mstcategory');
        $this->crud->setEntityNameStrings('Category', 'MstCategory');
        $this->crud->clearFilters();
 $this->setFilters();
    }

    protected function setFilters(){
        $this->crud->addFilter(
            [ // simple filter
                'type' => 'text',
                'name' => 'code',
                'label' => 'Code',
            ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'code', '=', "$value");
            }
        );

        $this->crud->addFilter([
            'type' => 'text',
            'name' => 'title',
            'label'=> 'Title'
          ], 
          false, 
          function($value) { // if the filter is active
            $this->crud->addClause('where', 'title', 'iLIKE', "%$value%");
          });
    }

    protected function setupListOperation()
    {
        $col=[
            $this->addRowNumber(),
            $this->addCodeColumn(),
            [
                'name'=>'title',
                'label'=> 'Title ',
            ],
        ];
        $this->crud->addColumns(array_filter($col));
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(MstCategoryRequest::class);
        $arr=[
            $this->addCodeField(),
            $this->addClientIdField(),
            [
                'name' => 'title_en',
                'type' => 'text',
                'label' => 'Title',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
            
            [
                'name' => 'description',
                'type' => 'textarea',
                'label' => 'Description',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-12',
                ],
            ],
            $this->addIsActiveField(),
        ];
        $arr = array_filter($arr);
        $this->crud->addFields($arr); 
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
