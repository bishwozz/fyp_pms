<?php

namespace App\Http\Controllers\Admin;

// use App\Base\Traits\ParentData;
use App\Models\Patient;
use App\Models\Referral;
use App\Base\BaseCrudController;
use App\Models\HrMaster\HrMstDepartments;
use App\Models\HrMaster\HrMstSubDepartments;
use App\Http\Requests\ReferralRequest;

class ReferralCrudController extends BaseCrudController
{
    // use ParentData;
   public function setup()
    {
        $this->crud->setModel(Referral::class);
        $this->crud->setRoute('admin/referral');
        $this->crud->setEntityNameStrings('Referral', 'Referral');
        $this->checkPermission();
        $this->crud->clearFilters();
        $this->setFilters();
    }

    private function setFilters(){

        $this->crud->addFilter(
            [ 
                'type' => 'text',
                'name' => 'name',
                'label' => 'Name'
            ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'name', 'iLIKE', "%$value%");
            }
        );

        $this->crud->addFilter(
            [ 
                'type' => 'text',
                'name' => 'phone',
                'label' => 'Phone',
            ],
            false,
            function ($value) { 
                $this->crud->addClause('where', 'phone', 'iLIKE', "%$value%");
            }
        );

        $this->crud->addFilter(
            [ 
                'type' => 'select2',
                'name' => 'referral_type',
                'label' => 'Referral Type',
            ],
            function() {
                return Referral::$referral_type;
            },
            function($value) { 
                $this->crud->addClause('where', 'referral_type', $value);
            }
        );
    }

    protected function setupListOperation()
    {
        $col = [
            $this->addRowNumber(),
            [
                'name' => 'code',
                'type' => 'text',
                'label' => trans('common.code'),
            ],
            [
                'name' => 'name',
                'type' => 'text',
                'label' => 'Name',
            ],
            [
                'name' => 'referral_type',
                'label' => 'Referral Type',
                'type' => 'select_from_array',
                'options'=> Referral::$referral_type,
            ],
      

        ];
            $this->crud->addColumns($col);
            // $this->crud->orderBy('display_order');
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ReferralRequest::class);
        $arr = [
                $this->addReadOnlyCodeField(),
                $this->addClientIdField(),

                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => trans('hrdepartments.title_en'),
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-4',
                    ],
                    'attributes'=>[
                        'required' => 'Required',
                        'maxlength' => '200',
                     ],
                ],

                [
                    'name' => 'referral_type',
                    'label' => 'Referral Type',
                    'type' => 'select_from_array',
                    'options'=> Referral::$referral_type,
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-4',
                    ],
                    'attributes'=>[
                        'required' => 'Required',
                     ],
                ],

                [
                    'name' => 'contact_person',
                    'type' => 'text',
                    'label' => 'Contact Person',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-4',
                    ],
                    'attributes'=>[
                        'required' => 'Required',
                        'maxlength' => '200',
                     ],
                ],
                [
                    'name' => 'phone',
                    'type' => 'number',
                    'label' => 'phone',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-4',
                    ],
                    'attributes'=>[
                        'required' => 'Required',
                        'maxlength' => '10',
                     ],
                ],
                [
                    'name' => 'email',
                    'type' => 'text',
                    'label' => 'email',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-4',
                    ],
                    'attributes'=>[
                        'maxlength' => '200',
                     ],
                ],

                [
                    'name' => 'address',
                    'type' => 'text',
                    'label' => 'Address',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-4',
                    ],
                ],
                [
                    'name' => 'discount_percentage',
                    'type' => 'number',
                    'label' => 'Discount Percentage (%)',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-4',
                    ],
                    'attributes'=>[
                        'required' => 'Required',
                        'maxlength' => '100',
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
