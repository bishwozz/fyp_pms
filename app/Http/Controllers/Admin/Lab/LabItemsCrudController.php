<?php

namespace App\Http\Controllers\Admin\Lab;

use App\Models\Lab\LabMstItems;
use App\Base\BaseCrudController;
use App\Models\Lab\LabMstCategories;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class LabItemsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LabItemsCrudController extends BaseCrudController
{
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(LabMstItems::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/lab/lab-items');
        CRUD::setEntityNameStrings('Lab Items', 'Lab Items');
        $this->crud->clearFilters();
        $this->setFilters();

        $this->crud->denyAccess(['create','update','delete']);
    }

    private function setFilters(){

        $this->crud->addFilter(
            [ // simple filter
                'type' => 'select2',
                'name' => 'is_testable',
                'label' => 'Test Status'
            ],
            function() {
                return [0=>'Not Testable',1=>'Testable'];
            },
            function($value) { 
                $this->crud->addClause('where', 'is_testable', $value);
            }
        );


    }

    protected function setupListOperation()
    {
        $this->crud->removeAllButtonsFromStack('line');
        $this->crud->fixRightColumn=0;
        $cols = [
            $this->addRowNumber(),
            $this->addCodeColumn(),
            $this->addClientIdColumn(),
            [
                'label' => 'Category',
                'type' => 'select',
                'name' => 'category_id',
                'entity' => 'lab_category',
                'attribute' => 'title',
                'model' => LabMstCategories::class,
                'orderable'=>false
            ],
            [
                'name' => 'name',
                'type' => 'text',
                'label' => trans('lab.lab_items_name'),
                'orderable'=>false
            ],
   
            [
                'label' => 'Sample',
                'type' => 'select',
                'name' => 'sample_id',
                'entity' => 'sample',
                'attribute' => 'name',
                'model' => 'App\Models\CoreMaster\MstLabSample',
                'orderable'=>false
            ],
            [
                'label' => 'Method',
                'type' => 'select',
                'name' => 'method_id',
                'entity' => 'method',
                'attribute' => 'name',
                'model' => 'App\Models\CoreMaster\MstLabMethod',
                'orderable'=>false
            ],
            [
                'name' => 'reference_from_value',
                'type' => 'text',
                'label' => trans('Reference From'),
                'orderable'=>false
            ],
            [
                'name' => 'reference_from_to',
                'type' => 'text',
                'label' => trans('Reference To'),
                'orderable'=>false
            ],
            [
                'name' => 'unit',
                'type' => 'text',
                'label' => trans('lab.unit'),
                'orderable'=>false
            ],
            [
                'name' => 'price',
                'type' => 'number',
                'label' => trans('lab.price'),
                'orderable'=>false
            ],
            [
                'name'=>'result_field_type',
                'label'=>'Result Field Type',
                'type'=>'select_from_array',
                'options'=>LabMstItems::$result_field_types,
                'orderable'=>false
            ],
            [
                'name' => 'is_testable',
                'label' => trans('Testable'),
                'type' => 'check',
                'options' =>
                [
                    1 => 'Yes',
                    0 => 'No',
                ],
                'orderable'=>false
            ],
            $this->addIsActiveColumn(),
        ];
        $cols = array_filter($cols);
        $this->crud->addColumns($cols);  
    }
   
}
