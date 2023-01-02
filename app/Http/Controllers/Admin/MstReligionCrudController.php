<?php

namespace App\Http\Controllers\Admin;

use App\Models\MstReligion;
use App\Base\BaseCrudController;

/**
 * Class MstReligionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MstReligionCrudController extends BaseCrudController
{
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\MstReligion::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/mst-religion');
        $this->crud->setEntityNameStrings('Religion', 'Religions');
        $this->crud->clearFilters();
        $this->setFilters();
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
                'name' => 'name',
                'label' => trans('menu.religion')
            ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'name', 'ilike', "%$value%");
            }
        );
       
    }
    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $col = [
            $this->addRowNumber(),
            $this->addCodeColumn(),
            [
                'name' => 'name',
                'type' => 'text',
                'label' => trans('menu.religion'),
            ],
        ];
        $this->crud->addColumns(array_filter($col));
        $this->crud->orderBy('display_order');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        // $this->crud->setValidation(MstReligionsRequest::class);

        $arr = [
            $this->addCodeField(),
            [
                'type' => 'plain_html',
                'name'=>'plain_html_1',
                'value' => '<div class="form-group col-md-6 "></div>',
            ],
            [
                'name' => 'name',
                'type' => 'text',
                'label' => trans('menu.religion'),
                'wrapperAttributes' => [
                  'class' => 'form-group col-md-4',
                 ],
                'attributes'=>[
                  'required' => 'Required',
                  'maxlength' => '200',
              ],
            ],
            $this->addDisplayOrderField(),
            $this->addRemarksField(),
        ];

        $this->crud->addFields($arr);
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
