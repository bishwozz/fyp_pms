<?php

namespace App\Http\Controllers\Admin\HrMaster;

// use App\Base\Traits\ParentData;
use App\Base\BaseCrudController;
use App\Models\HrMaster\HrMstDepartments;
use App\Http\Requests\HrMaster\HrMstDepartmentsRequest;

class HrMstDepartmentsCrudController extends BaseCrudController
{
    // use ParentData;
   public function setup()
    {
        $this->crud->setModel(HrMstDepartments::class);
        $this->crud->setRoute('admin/hrmstdepartments');
        $this->crud->setEntityNameStrings(trans('hrdepartments.add_text'), trans('hrdepartments.title_text'));
        $this->checkPermission();
        $this->crud->clearFilters();
        $this->setFilters();
        $this->setUpLinks(['edit']);
        $mode = $this->crud->getActionMethod();
        // dd($mode);
        if(in_array($mode,['edit'])){
            $department = HrMstDepartments::findOrFail($this->parent('id'));
            $this->data['custom_title'] =$department->title;
        }
    }

    public function tabLinks()
    {
        $links = [];
            $links[] = ['label' =>  'Department', 'href' => backpack_url('hrmstdepartments/'.$this->parent('id').'/edit')];
            $links[] = ['label' => 'Sub department', 'href' => backpack_url('hrmstdepartments/'.$this->parent('id').'/hrmstsubdepartments')];
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
                'label' => 'Department Name'
            ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'title', 'iLIKE', "%$value%");
            }
        );
    }


    protected function setupListOperation()
    {
        $this->crud->addButtonFromModelFunction('line','subDepartment','subDepartment','beginning');

        $col = [
            $this->addRowNumber(),
            [
                'name' => 'code',
                'type' => 'text',
                'label' => trans('common.code'),
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
        $this->crud->setValidation(HrMstDepartmentsRequest::class);
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
