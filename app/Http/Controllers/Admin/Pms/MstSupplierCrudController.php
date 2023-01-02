<?php

namespace App\Http\Controllers\Admin\Pms;

use App\Models\Pms\MstSupplier;
use App\Base\BaseCrudController;

class MstSupplierCrudController extends BaseCrudController
{
   public function setup()
    {
        $this->crud->setModel(MstSupplier::class);
        $this->crud->setRoute('admin/mstsupplier');
        $this->crud->setEntityNameStrings(trans('menu.MstSupplier'), trans('menu.MstSupplier'));
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
            'name' => 'name',
            'label'=> trans('MstSupplier.title_lc')
          ], 
          false, 
          function($value) { // if the filter is active
            $this->crud->addClause('where', 'name', 'iLIKE', "%$value%");
          });
    }

    protected function setupListOperation()
    {
        $col=[
            $this->addCodeColumn(),
            $this->addRowNumber(),
            [
                'name' => 'name',
                'label' => trans('MstSupplier.title_en'),
            ],
        
            [
                'name' => 'address',
                'label' => trans('MstSupplier.address')
            ],
            [
                'name' => 'email',
                'label' => trans('MstSupplier.email')
            ],
            [
                'name' => 'contact_person',
                'label' => trans('MstSupplier.contact_person')
            ],
            [
                'name' => 'phone_number',
                'label' => trans('MstSupplier.phone_number')
            ],
            [
                'name' => 'website',
                'label' => trans('MstSupplier.website')
            ],
        ];
        $this->crud->addColumns(array_filter($col));
    }

    protected function setupCreateOperation()
    {
        // $this->crud->setValidation(MstSupplierRequest::class);
        $arr=[
            $this->addCodeField(),
            $this->addClientIdField(),
            [
                'name' => 'name',
                'type' => 'text',
                'label' => trans('MstSupplier.title_lc'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
           
            [
                'name' => 'address',
                'type' => 'text',
                'label' => trans('MstSupplier.address'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'email',
                'type' => 'text',
                'label' => trans('MstSupplier.email'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'contact_person',
                'type' => 'text',
                'label' => trans('MstSupplier.contact_person'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'phone_number',
                'type' => 'text',
                'label' => trans('MstSupplier.phone_number'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'website',
                'type' => 'text',
                'label' => trans('MstSupplier.website'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'description',
                'type' => 'textarea',
                'label' => trans('MstSupplier.description_en'),
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
