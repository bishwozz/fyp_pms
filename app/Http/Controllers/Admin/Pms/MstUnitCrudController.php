<?php

namespace App\Http\Controllers\Admin\Pms;

use App\Models\Pms\MstUnit;
use App\Base\BaseCrudController;


class MstUnitCrudController extends BaseCrudController
{
   public function setup()
    {
        $this->crud->setModel(MstUnit::class);
        $this->crud->setRoute('admin/mstunit');
        $this->crud->setEntityNameStrings(trans('Unit'), trans('MstUnit'));
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
            'name' => 'name_en',
            'label'=> trans('MstUnit.name_en')
          ],
          false,
          function($value) { // if the filter is active
            $this->crud->addClause('where', 'name_en', 'iLIKE', "%$value%");
          });
          $this->crud->addFilter([
            'type' => 'text',
            'name' => 'name_lc',
            'label'=> trans('MstUnit.name_lc')
          ],
          false,
          function($value) { // if the filter is active
            $this->crud->addClause('where', 'name_lc', 'iLIKE', "%$value%");
          });
    }

    protected function setupListOperation()
    {
        $col=[
            $this->addRowNumber(),
            $this->addCodeColumn(),
            [
                'name' => 'name_en',
                'label' => trans('Unit Name'),
            ],
            [
                'name' => 'count',
                'label' => 'Count',
            ],
            [
                'name' => 'name',
                'label' => 'Name',
            ],
            
        ];
        $this->crud->addColumns(array_filter($col));
    }

    protected function setupCreateOperation()
    {
        // $this->crud->setValidation(MstUnitRequest::class);
        $arr=[
            $this->addCodeField(),
            $this->addReadOnlyCodeField(),
            [
                'name' => 'name_lc',
                'type' => 'text',
                'label' => trans('PhrMstUnit.name_lc'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
            [
                'name' => 'name_en',
                'type' => 'text',
                'label' => trans('PhrMstUnit.name_en'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
            [
                'type' => 'custom_html',
                'name'=>'plain_html_1',
                'value' => '</br>',
            ],
            [
                'name' => 'count',
                'type' => 'number',
                'label' => trans('Count'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
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
