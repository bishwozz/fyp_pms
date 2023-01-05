<?php

namespace App\Http\Controllers\Admin\Pms;

use App\Models\Pms\MstSupplier;
use App\Base\BaseCrudController;
use App\Http\Requests\Pms\MstSupplierRequest;

class MstSupplierCrudController extends BaseCrudController
{
   public function setup()
    {
        $this->crud->setModel(MstSupplier::class);
        $this->crud->setRoute('admin/mstsupplier');
        $this->crud->setEntityNameStrings('Supplier', 'MstSupplier');
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
            'label'=> 'Suppiler Name',
          ], 
          false, 
          function($value) { // if the filter is active
            $this->crud->addClause('where', 'name', 'iLIKE', "%$value%");
          });
    }

    protected function setupListOperation()
    {
        $col=[
            $this->addRowNumber(),
            $this->addCodeColumn(),
            [
                'name' => 'name',
                'label' => 'Suppiler Name',
            ],
        
            [
                'name' => 'address',
                'label' => trans('Address')
            ],
            [
                'name' => 'email',
                'label' => trans('Email')
            ],
            [
                'name' => 'phone_number',
                'label' => trans('Phone Number')
            ],
            [
                'name' => 'website',
                'label' => trans('Website url')
            ],
        ];
        $this->crud->addColumns(array_filter($col));
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(MstSupplierRequest::class);
        $arr=[
            $this->addCodeField(),
            $this->addClientIdField(),
            [
                'name' => 'name',
                'type' => 'text',
                'label' => 'Supplier Name',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
           
            [
                'name' => 'address',
                'type' => 'text',
                'label' => trans('Address'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'email',
                'type' => 'text',
                'label' => trans('Email'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'contact_person',
                'type' => 'text',
                'label' => trans('Contact Person'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'phone_number',
                'type' => 'text',
                'label' => trans('Phone Number'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'website',
                'type' => 'text',
                'label' => trans('Website url'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'description',
                'type' => 'textarea',
                'label' => trans('Description'),
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
