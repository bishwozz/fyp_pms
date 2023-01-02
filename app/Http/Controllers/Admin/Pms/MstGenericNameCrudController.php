<?php

namespace App\Http\Controllers\Admin\Pms;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Base\BaseCrudController;
use App\Models\Pms\MstGenericName;
use Illuminate\Routing\Controller;


class MstGenericNameCrudController extends BaseCrudController
{
   public function setup()
    {
        $this->crud->setModel(MstGenericName::class);
        $this->crud->setRoute('admin/mstgenericname');
        $this->crud->setEntityNameStrings('Generic Name', trans('Generic Name'));
    }

    protected function setupListOperation()
    {
        $col=[
            $this->addRowNumber(),
            [
                'name' => 'name',
                'label' => trans('Name'),
            ],
            $this->addIsActiveColumn(),
      
        ];
        $this->crud->addColumns(array_filter($col));
    }

    protected function setupCreateOperation()
    {
        // $this->crud->setValidation(MstGenericNameRequest::class);
        $arr=[
            $this->addClientIdField(),
            [
                'name' => 'name',
                'type' => 'text',
                'label'=>'Name',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
                'attributes' => [
                    'required' => 'required'
                ]
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
