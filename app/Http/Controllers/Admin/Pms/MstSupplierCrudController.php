<?php

namespace App\Http\Controllers\Admin\Pms;

use App\Models\Pms\MstSupplier;
use App\Base\BaseCrudController;
use App\Models\CoreMaster\MstGender;
use App\Models\CoreMaster\MstCountry;
use App\Models\Pms\MstPharmaceutical;
use App\Models\CoreMaster\MstFedDistrict;
use App\Models\CoreMaster\MstFedProvince;
use App\Models\CoreMaster\MstFedLocalLevel;
use App\Http\Requests\Pms\MstSupplierRequest;
use App\Base\Operations\InlineCreateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MstSupplierCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MstSupplierCrudController extends BaseCrudController
{
    use InlineCreateOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Pms\MstSupplier::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mstsupplier');
        CRUD::setEntityNameStrings('', 'Suppliers');
        // $this->isAllowed();
        $this->data['script_js'] = $this->getScripts();
        $this->user = backpack_user();
        $this->crud->clearFilters();
        $this->setFilters();
    }

    protected function setFilters(){
        $this->crud->addFilter(
            [ // simple filter
                'type' => 'text',
                'name' => 'name_en',
                'label' => 'Suppiler Name'
            ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'name_en', '=', "$value");
            }
        );
        $this->crud->addFilter(
            [ // simple filter
                'type' => 'text',
                'name' => 'contact_number',
                'label' => 'Phone Number'
            ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'contact_number', '=', "$value");
            }
        );

    }

    public function getScripts()
    {
        return
            "
            function showHideCoorporateFields(){
                var coorporate = $('#coorporate').val();
                if(coorporate == 1){
                    $('#company_id').show();
                    $('#pan_no').show();
                    $('#gender_id option[value=\"3\"]').attr('selected', 'selected');
                }else{
                    $('#company_id').hide();
                    $('#pan_no').hide();
                    $('#gender_id').val(null).trigger('change');
                }
            }
            $(document).ready(function() {
                showHideCoorporateFields();
                $('#coorporate').on('change', function(){
                    showHideCoorporateFields();
                });
            });
            ";
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $cols = [
            $this->addRowNumberColumn(),
            $this->addClientIdField(),
            $this->addNameEnColumn(),
            $this->addNameLcColumn(),
            [
                'name' => 'address',
                'type' => 'text',
                'label' => 'Address',
            ],
            [
                'name' => 'email',
                'type' => 'email',
                'label' => 'Email',
            ],
            [
                'name' => 'contact_number',
                'type' => 'text',
                'label' => 'Contact Number',
            ],
            [
                'name' => 'contact_person',
                'type' => 'text',
                'label' => 'Contact Person',
            ],

            $this->addIsActiveColumn(),
        ];
        $this->crud->addColumns(array_filter($cols));
        
        //Add this clause to only display Suppliers
        $this->crud->addClause('where','is_customer', false);
        $this->crud->addClause('orWhere','is_customer', null);

    }


    protected function setupCreateOperation()
    {
        CRUD::setValidation(MstSupplierRequest::class);

        $fields = [
            $this->addReadOnlyCodeField(),
            $this->addPlainHtml(),
            $this->addClientIdField(),
            $this->addNameEnField(),
            $this->addNameLcField(),
            [
                'name' => 'is_customer',
                'type' => 'hidden',
                'value' => false
            ],
            [  // Select
                'label'     => 'Supplier Type',
                'type' => 'select2_from_array',
                'name' => 'is_coorporate',
                'options'     => [false => 'Individual', true => 'Business / Coorporate'],
                'allows_null' => false,
                'default'     => false,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
                'attributes' => [
                    'id' => 'coorporate',
                    'required' => 'required'
                ],
            ],

            [
                'name'  => 'pan_no',
                'label' => 'PAN Number',
                'type'  => 'number',
                'wrapper' => [
                    'class' => 'form-group col-md-6',
                    'id' => 'pan_no'
                ],
            ],
            [
                'name'  => 'country_id',
                'label' => 'Country',
                'type' => 'select2',
                'entity' => 'countryEntity',
                'attribute' => 'name',
                'model' => MstCountry::class,
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name'=>'province_id',
                'type'=>'select2',
                'label'=>trans('hremployees.province'),
                'entity'=>'province',
                'model'=>MstFedProvince::class,
                'attribute'=>'name',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
            ],
            [
                'name'=>'district_id',
                'label'=>trans('hremployees.district'),
                'type'=>'select2_from_ajax',
                'model'=>MstFedDistrict::class,
                'entity'=>'district',
                'attribute'=>'name',
                'method'=>'post',
                // 'include_all_form_fields'=>false,
                'data_source' => url("api/district/province_id"),
                'minimum_input_length' => 0,
                'dependencies'=> ['province_id'],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
                'attributes' => [
                    'id' => 'district_id',
                    'placeholder' => "Select a District",
                ],
            ],
            [
                'name'=>'local_level_id',
                'label'=>trans('hremployees.locallevel'),
                'type'=>'select2_from_ajax',
                'entity'=>'locallevel',
                'model'=>MstFedLocalLevel::class,
                'attribute'=>'name',
                'method'=>'post',
                // 'include_all_form_fields'=>false,
                'data_source' => url("api/locallevel/district_id"),
                'minimum_input_length' => 0,
                'dependencies'         => ['district_id'],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
                'attributes' => [
                    'id' => 'local_level_id',
                    'placeholder' => "Select a Local Level",
                ],

            ],
            [
                'name'  => 'address',
                'label' => 'Address',
                'type'  => 'text',
            ],
            [
                'name'  => 'email',
                'label' => 'Email',
                'type'  => 'email',
            ],
            [
                'name'  => 'contact_person',
                'label' => 'Company Person',
                'type'  => 'text',
                'wrapper' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
            [
                'name'=>'company_id',
                'type'=>'select2',
                'label'=>trans('Company'),
                'entity'=>'company',
                'model'=>MstPharmaceutical::class,
                'attribute'=>'name',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
            ],
            
            [
                'name'  => 'contact_number',
                'label' => 'Contact Number',
                'type'  => 'number',

                'wrapper' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
            $this->addDescriptionField(),
            $this->addIsActiveField()
        ];
        $this->crud->addFields(array_filter($fields));


        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
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
