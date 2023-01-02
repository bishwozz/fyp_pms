<?php

namespace App\Http\Controllers\Admin\HrMaster;

// use App\Base\Traits\ParentData;
use App\Base\BaseCrudController;
use App\Models\HrMaster\HrMstDepartments;
use App\Models\HrMaster\HrMstSubDepartments;
use App\Http\Requests\HrMaster\HrMstSubDepartmentsRequest;

class HrMstSubDepartmentsCrudController extends BaseCrudController
{
    // use ParentData;
   public function setup()
    {
        $this->crud->setModel(HrMstSubDepartments::class);
        $this->crud->setRoute('admin/hrmstdepartments/'.$this->parent('department_id').'/hrmstsubdepartments');
        $this->crud->setEntityNameStrings('Sub Departments', 'Sub Departments');
        $this->crud->addClause('where','department_id',$this->parent('department_id'));
        $this->checkPermission();
        $this->crud->clearFilters();
        $this->setFilters();

        $this->setUpLinks(['index']);
        $mode = $this->crud->getActionMethod();

        // if(in_array($mode,['index','edit'])){
        //     $sub_department = HrMstSubDepartments::find($this->parent('department_id'));
        //     $this->data['custom_title'] =$sub_department->title;
        // } 
    }

    public function tabLinks()
    {
        $links = [];
            $links[] = ['label' => 'Department', 'href' => backpack_url('/hrmstdepartments/'.$this->parent('department_id').'/edit')];
            $links[] = ['label' => 'Sub department', 'href' => $this->crud->route];
        return $links;
    }

    private function setFilters(){

        $this->crud->addFilter(
            [ // simple filter
                'type' => 'text',
                'name' => 'code',
                'label' => 'Code'
            ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'code', '=', "$value");
            }
        );

        $this->crud->addFilter(
            [ // simple filter
                'type' => 'text',
                'name' => 'title',
                'label' => 'Sub Department Name'
            ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'title', 'iLIKE', "%$value%");
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
                'label' => 'Department',
                'type' => 'select',
                'name' => 'department_id',
                'entity' => 'department',
                'attribute' => 'title',
                'model' => HrMstDepartments::class,
            ],

            [
                'name' => 'title',
                'type' => 'text',
                'label' => trans('hrdepartments.title_en'),
            ],

        ];
            $this->crud->addColumns($col);
            $this->crud->orderBy('display_order');
    }

    protected function setupCreateOperation()
    {
        if($this->parent('department_id')){
            $department_id = [
                    'type' => 'hidden',
                    'name' => 'department_id',       
                    'value' => $this->parent('department_id'),
            ];
        }else{
            $department_id = [
                'label' => 'Department',
                'type' => 'select2',
                'name' => 'department_id',
                'entity' => 'department',
                'attribute' => 'title',
                'model' => HrMstDepartments::class,
                'attributes' => [
                    'required' => 'required',
                    'id' => 'department_id',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ];
        }
        $this->crud->setValidation(HrMstSubDepartmentsRequest::class);
        $arr = [
            $this->addReadOnlyCodeField(),
                $this->addClientIdField(),
                [
                    'name' => 'title',
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
                $department_id,
                [
                    'name' => 'legend1',
                    'type' => 'custom_html',
                    'value' => '<br>',
                ],
                $this->addDisplayOrderField(),
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
