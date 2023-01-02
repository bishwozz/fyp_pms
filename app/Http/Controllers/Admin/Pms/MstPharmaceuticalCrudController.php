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
        $this->crud->setEntityNameStrings(trans('menu.MstPharmaceutical'), trans('menu.MstPharmaceutical'));
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
            'label'=> trans('MstPharmaceutical.name')
          ], 
          false, 
          function($value) { // if the filter is active
            $this->crud->addClause('where', 'name', 'iLIKE', "%$value%");
          });
          $this->crud->addFilter([
            'type' => 'text',
            'name' => 'address',
            'label'=> trans('MstPharmaceutical.address')
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
                'label' => trans('MstPharmaceutical.name'),
            ],
            [
                'name' => 'address',
                'label' => trans('MstPharmaceutical.address'),
            ],
            [
                'name' => 'contact_person',
                'label' => trans('MstPharmaceutical.contact_person'),
            ],
            [
                'name' => 'email',
                'label' => trans('MstPharmaceutical.email'),
            ],
            [
                'name' => 'contact_number',
                'label' => trans('MstPharmaceutical.contact_phone'),
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
                'label'=>trans('MstPharmaceutical.name'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'address',
                'label'=>trans('MstPharmaceutical.address'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'contact_person',
                'label' => trans('MstPharmaceutical.contact_person'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'email',
                'label'=>trans('MstPharmaceutical.email'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'contact_number',
                'label' => trans('MstPharmaceutical.contact_phone'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'website',
                'label' => trans('MstPharmaceutical.website'),
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
