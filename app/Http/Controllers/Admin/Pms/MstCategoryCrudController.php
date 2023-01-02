<?php

namespace App\Http\Controllers\Admin\Pms;

use App\Models\Pms\MstCategory;
use App\Base\BaseCrudController;

class MstCategoryCrudController extends BaseCrudController
{
   public function setup()
    {
        $this->crud->setModel(MstCategory::class);
        $this->crud->setRoute('admin/mstcategory');
        $this->crud->setEntityNameStrings(trans('menu.mstcategory'), trans('menu.mstcategory'));
        $this->crud->clearFilters();
 $this->setFilters();
    }

    protected function setFilters(){
        $this->crud->addFilter(
            [ // simple filter
                'type' => 'text',
                'name' => 'code',
                'label' => trans('कोड')
            ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'code', '=', "$value");
            }
        );
        $this->crud->addFilter([
            'type' => 'text',
            'name' => 'title_en',
            'label'=> trans('MstCategory.title_en')
          ], 
          false, 
          function($value) { // if the filter is active
            $this->crud->addClause('where', 'title_en', 'iLIKE', "%$value%");
          });
          $this->crud->addFilter([
            'type' => 'text',
            'name' => 'name_lc',
            'label'=> trans('MstCategory.title_lc')
          ], 
          false, 
          function($value) { // if the filter is active
            $this->crud->addClause('where', 'title_lc', 'iLIKE', "%$value%");
          });
    }

    protected function setupListOperation()
    {
        $col=[
            $this->addRowNumber(),
            $this->addCodeColumn(),
            [
                'name'=>'title_en',
                'label'=>trans('MstCategory.title_en'),
            ],
            [
                'name'=>'title_lc',
                'label'=>trans('MstCategory.title_lc'),
            ],
        ];
        $this->crud->addColumns(array_filter($col));
    }

    protected function setupCreateOperation()
    {
        // $this->crud->setValidation(MstCategoryRequest::class);
        $arr=[
            $this->addCodeField(),
            $this->addClientIdField(),
            [
                'name' => 'title_en',
                'type' => 'text',
                'label' => trans('MstCategory.title_en'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
            [
                'name' => 'title_lc',
                'type' => 'text',
                'label' => trans('MstCategory.title_lc'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
            [
                'name' => 'description_en',
                'type' => 'textarea',
                'label' => trans('MstCategory.description_en'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-12',
                ],
            ],
            [
                'name' => 'description_lc',
                'type' => 'textarea',
                'label' => trans('MstCategory.description_ln'),
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
