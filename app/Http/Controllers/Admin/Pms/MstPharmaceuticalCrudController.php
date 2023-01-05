<?php

namespace App\Http\Controllers\Admin\Pms;
use App\Base\BaseCrudController;
use App\Models\Pms\MstPharmaceutical;


class MstPharmaceuticalCrudController extends BaseCrudController
{
   public function setup()
    {
        $this->crud->setModel(MstPharmaceutical::class);
        $this->crud->setRoute('admin/mstpharmaceutical');
        $this->crud->setEntityNameStrings(trans('Pharmaceutical'), trans('MstPharmaceutical'));
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
            'name' => 'name',
            'label'=> trans('Name')
          ], 
          false, 
          function($value) { // if the filter is active
            $this->crud->addClause('where', 'name', 'iLIKE', "%$value%");
          });
          $this->crud->addFilter([
            'type' => 'text',
            'name' => 'address',
            'label'=> trans('Address')
          ], 
          false, 
          function($value) { // if the filter is active
            $this->crud->addClause('where', 'address', 'iLIKE', "%$value%");
          });
    }

    protected function setupListOperation()
    {
        $col=[
            $this->addCodeColumn(),
            $this->addRowNumber(),
            [
                'name' => 'name',
                'label' => trans('Pharmaceutical Name'),
            ],
            [
                'name' => 'address',
                'label' => trans('Address'),
            ],
            [
                'name' => 'contact_person',
                'label' => trans('Contact Person'),
            ],
            [
                'name' => 'email',
                'label' => trans('Email'),
            ],
            [
                'name' => 'contact_number',
                'label' => trans('Contact Phone'),
            ],
        ];
        $this->crud->addColumns(array_filter($col));
    }

    protected function setupCreateOperation()
    {
        // $this->crud->setValidation(MstPharmaceuticalRequest::class);
        $arr=[
            $this->addCodeField(),
            $this->addClientIdField(),
            [
                'name' => 'name',
                'text' => 'text',
                'label'=>trans('Pharmaceutical Name'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'address',
                'label'=>trans('Address'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'contact_person',
                'label' => trans('Contact Person'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'email',
                'label'=>trans('Email'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'contact_number',
                'label' => trans('Contact Phone'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'website',
                'label' => trans('Website'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
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
