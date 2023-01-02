<?php

namespace App\Http\Controllers\Admin\Lab;

use App\Models\Lab\LabMstItems;
use App\Base\BaseCrudController;
use App\Models\Lab\LabMstCategories;
use App\Http\Requests\LabMstItemsRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class LabMstItemsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LabMstItemsCrudController extends BaseCrudController
{

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(LabMstItems::class);
        CRUD::setRoute('/admin/lab/lab-mst-categories/'.$this->parent('lab_category_id').'/lab-mst-items');
        CRUD::setEntityNameStrings(trans('menu.lab_mst_items'), trans('menu.lab_mst_items'));

        // $this->crud->clearFilters();
        // $this->setFilters();
        $this->setUpLinks(['index']);

        $this->data['script_js']= $this->getScriptJs();
        $mode = $this->crud->getActionMethod();
        if(in_array($mode,['index','edit'])){
            $category = LabMstCategories::find($this->parent('lab_category_id'));
            $this->data['custom_title'] =$category->title;
        } 
        $this->crud->clearFilters();
        $this->setFilters();
    }

    public function getScriptJs(){
        return "
        $(document).ready(function(){
            if($('#result_field_type').val() == '2'){
                $('.result_field_options').show();
            }else{
                $('.result_field_options').hide();   
            }
            $('#result_field_type').change(function() {
                if($('#result_field_type').val() == '2'){
                    $('.result_field_options').show();
                }else{
                    $('.result_field_options').hide();   
                }
            });
        });
        ";
    }
    public function tabLinks()
    {
        $links = [];
        $links[] = ['label' => trans('menu.labcategories'), 'href' => backpack_url('lab/lab-mst-categories/'.$this->parent('lab_category_id').'/edit')];
        $links[] = ['label' => trans('menu.lab_mst_items'), 'href' => $this->crud->route];
        return $links;
    }


    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    private function setFilters(){

        $this->crud->addFilter(
            [ // simple filter
                'type' => 'text',
                'name' => 'name',
                'label' => 'Item Name'
            ],
            false,
            function($value) { 
                $this->crud->addClause('where', 'name', 'iLIKE', "%$value%");
            }
        );
    }
    protected function setupListOperation()
    {
        $col=[
            $this->addRowNumber(),
            $this->addCodeColumn(),
            [
                'name' => 'name',
                'type' => 'text',
                'label' => trans('lab.lab_items_name'),
            ],

            [
                'name' => 'unit',
                'type' => 'text',
                'label' => trans('lab.unit'),
            ],
            [
                'name' => 'price',
                'type' => 'text',
                'label' => trans('lab.price'),
            ],
        ];
        $this->crud->addColumns(array_filter($col));
        if ($this->parent('lab_category_id')== null) {
            abort(404);
        } else {
            $this->crud->addClause('where', 'lab_category_id', $this->parent('lab_category_id'));
        }
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(LabMstItemsRequest::class);

        $arr = [
            $this->addCodeField(),
            $this->addClientIdField(),
            [
                'type' => 'custom_html',
                'name'=>'plain_html_1',
                'value' => '<br />',
            ],
            [
                'type' => 'hidden',
                'name' => 'lab_category_id',       
                'value' => $this->parent('lab_category_id'),      
            ],
            [
                'name' => 'name',
                'type' => 'text',
                'label' => trans('lab.lab_items_name'),
                'wrapperAttributes' => [
                  'class' => 'form-group col-md-4',
                 ],
                'attributes'=>[
                  'required' => 'Required',
                  'maxlength' => '200',
              ],
            ],
            [
                'label' => 'Sample',
                'type' => 'select2',
                'name' => 'sample_id',
                'entity' => 'sample',
                'attribute' => 'name',
                'model' => 'App\Models\CoreMaster\MstLabSample',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ]
            ],
            [
                'label' => 'Method',
                'type' => 'select2',
                'name' => 'method_id',
                'entity' => 'method',
                'attribute' => 'name',
                'model' => 'App\Models\CoreMaster\MstLabMethod',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ]
            ],
            [ //Toggle
                'name' => 'is_special_reference',
                'label' => trans('Is Special Reference?'),
                'type' => 'toggle',
                'options'     => [ 
                    0 => 'No',
                    1 => 'Yes'
                ],
                'inline' => true,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' =>[
                    'id' => 'is_special_reference',
                ],
                'hide_when' => [
                    0 => ['special_reference'],
                    1 => ['reference_from_value','reference_from_to'],
                ],
                'default' => 0,
            ],
            [
                'name' => 'special_reference',
                'type' => 'summernote',
                'label' => trans('Special Reference'),
                'attributes' =>[
                    'id' => 'special_reference',
                    'rows'=>10,
                ],
                 'wrapperAttributes' => [
                     'class' => 'form-group col-md-12',
                 ],
                 'options' => [
                    'height' => 200
                ]
            ],
            [
                'name' => 'reference_from_value',
                'type' => 'text',
                'label' => trans('Reference From'),
                'attributes' =>[
                    'id' => 'reference_from_value',
                ],
                 'wrapperAttributes' => [
                     'class' => 'form-group col-md-4',
                 ]
            ],
            [
                'name' => 'reference_from_to',
                'type' => 'text',
                'label' => trans('Reference To'),
                'attributes' =>[
                    'id' => 'reference_from_to',
                ],
                 'wrapperAttributes' => [
                     'class' => 'form-group col-md-4',
                 ]
            ],
            [
                'name' => 'unit',
                'type' => 'text',
                'label' => trans('lab.unit'),
                 'wrapperAttributes' => [
                     'class' => 'form-group col-md-4',
                 ],
                 'attributes'=>[
                    'maxlength' => '50',
                 ],
            ],
            [
                'name' => 'price',
                'type' => 'number',
                'label' => trans('lab.price'),
                'attributes'=>[
                    'maxlength' => '100',
                    "step" => "any"
                 ],
                 'wrapperAttributes' => [
                     'class' => 'form-group col-md-3',
                 ],
                 'default'=>0

            ],
            [
                'name'=>'result_field_type',
                'label'=>'Result Field Type',
                'type'=>'select_from_array',
                'options'=>LabMstItems::$result_field_types,
                'validationRules' => 'required',
                'validationMessages' => [
                    'required' => ' The result field type is required.',
                ],
                'wrapper'=>[
                    'class' => 'form-group col-md-3 required',
                ],
                'attributes'=>[
                    'id' => 'result_field_type'
                ]
            ],
            [
                'name' => 'result_field_options',
                'type' => 'repeatable',
                'label' => 'Options',
                'show_individually' => true,
                'new_item_label'  => 'New Option',
                'fields' => [
                    [
                        'name' => 'result_field_options',
                        'type' => 'text',
                        'label' => 'Option',
                        'wrapper' => [
                            'class' => 'form-group col-md-8',
                        ],
                    ],
                ],
                'wrapper' => [
                    'class' => 'form-group col-md-8 result_field_options',
                ],
                'attributes'=>[
                    'id' => 'result_field_options'
                ]
            ],
            [
                'name' => 'description',
                'type' => 'textarea',
                'label' => trans('lab.description'),
                'wrapperAttributes' => [
                  'class' => 'form-group col-md-12',
                 ],
                'attributes'=>[
                  'maxlength' => '500',
              ],
            ],
            [
                'name' => 'is_testable',
                'label' => 'Is Testable?',
                'type' => 'radio',
                'default' => 1,
                'inline' => true,
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'options' =>
                [
                    1 => 'Yes',
                    0 => 'No',
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
    public function store()
    {
        $this->crud->hasAccessOrFail('create');
        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();
        if($request->result_field_type!=2){
            $request->request->set('result_field_options', '[]');
        }
        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();

        // insert item in the db
        $item = $this->crud->create($this->crud->getStrippedSaveRequest($request));
        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }
    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();
        if($request->result_field_type!=2){
            $request->request->set('result_field_options', '[]');
        }
        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();

        // update the row in the db
        $item = $this->crud->update(
            $request->get($this->crud->model->getKeyName()),
            $this->crud->getStrippedSaveRequest($request)
        );
        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }
}
