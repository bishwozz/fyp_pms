<?php

namespace App\Http\Controllers\Admin\Lab;

use App\Base\BaseCrudController;
use App\Models\Lab\LabMstCategories;
use App\Http\Requests\LabMstCategoriesRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class LabMstCategoriesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LabMstCategoriesCrudController extends BaseCrudController
{
 
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(LabMstCategories::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/lab/lab-mst-categories');
        CRUD::setEntityNameStrings(trans('menu.labcategories'), trans('menu.labcategories'));

        $this->crud->clearFilters();
        $this->setFilters();

        $this->setUpLinks(['edit']);

        $mode = $this->crud->getActionMethod();
        if(in_array($mode,['edit'])){
            $category = LabMstCategories::find($this->parent('id'));
            $this->data['custom_title'] =$category->title;
        }
    }

    public function tabLinks()
    {
        $links = [];
            $links[] = ['label' => trans('menu.labcategories'), 'href' => backpack_url('lab/lab-mst-categories/'.$this->parent('id').'/edit')];
            $links[] = ['label' => trans('menu.lab_mst_items'), 'href' => backpack_url('lab/lab-mst-categories/'.$this->parent('id').'/lab-mst-items')];
        return $links;
    }

    private function setFilters(){

        $this->crud->addFilter(
            [ // simple filter
                'type' => 'text',
                'name' => 'title',
                'label' => 'Category Name'
            ],
            false,
            function($value) { 
                $this->crud->addClause('where', 'title', 'iLIKE', "%$value%");
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
        $this->crud->addButtonFromModelFunction('line','labItems','labItems','beginning');

        $col = [
            $this->addRowNumber(),
            $this->addCodeColumn(),
            [
                'name' => 'title',
                'type' => 'text',
                'label' => trans('lab.lab_category_title_en'),
            ],
            $this->addIsActiveColumn()
          
    ];
    $this->crud->addColumns(array_filter($col));
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(LabMstCategoriesRequest::class);

        $arr = [
            $this->addClientIdField(),
            $this->addCodeField(),
            [
                'type' => 'custom_html',
                'name'=>'plain_html_1',
                'value' => '<br />',
            ],

            [
                'name' => 'title',
                'type' => 'text',
                'label' => trans('lab.lab_category_title_en'),
                 'wrapperAttributes' => [
                     'class' => 'form-group col-md-6',
                 ],
                 'attributes'=>[
                    'required' => 'Required',
                    'maxlength' => '200',
                 ],
            ],

            [
                'name' => 'description',
                'type' => 'textarea',
                'label' => trans('Description'),
                 'wrapperAttributes' => [
                     'class' => 'form-group col-md-12',
                 ],
                 'attributes'=>[
                    'maxlength' => '500',
                 ],
            ],
            $this->addIsActiveField(),
        ];
        $arr = array_filter($arr);
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
