<?php

namespace App\Http\Controllers\Admin\CoreMaster;


use App\Base\BaseCrudController;
use App\Models\CoreMaster\MstFiscalYear;
use App\Models\CoreMaster\AppSetting;
use App\Http\Requests\CoreMaster\AppSettingRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AppSettingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AppSettingCrudController extends BaseCrudController
{

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\CoreMaster\AppSetting::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/app-setting');
        CRUD::setEntityNameStrings(trans('menu.appSetting'), trans('menu.appSetting'));
        $this->data['script_js'] = $this->getScriptJs();
    
        $this->checkPermission();
    }

    private function getScriptJs(){
        return "
        function appSetting_letter_head(){
            var title_1 = $('form input[name=letter_head_title_1]').val(),
            title_2 = $('form input[name=letter_head_title_2]').val(),
            title_3 = $('form input[name=letter_head_title_3]').val(),
            title_4 = $('form input[name=letter_head_title_4]').val();
            
            $('#letter_head_title_1_label').html(title_1);
            $('#letter_head_title_2_label').html(title_2);
            $('#letter_head_title_3_label').html(title_3);
            $('#letter_head_title_4_label').html(title_4);
        }
        $(document).ready(function(){
            appSetting_letter_head();
            $('form input[name=letter_head_title_1]').keyup(appSetting_letter_head);
            $('form input[name=letter_head_title_2]').keyup(appSetting_letter_head);
            $('form input[name=letter_head_title_3]').keyup(appSetting_letter_head);
            $('form input[name=letter_head_title_4]').keyup(appSetting_letter_head);
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
        $cols=[
            $this->addRowNumberColumn(),
            [
                'name' => 'office_name',
                'label' => trans('common.name_en'),
                'type' => 'text',
                'orderable'=>false,

            ],
            [   // Upload
                'name' => 'client_logo',
                'label' => 'Logo',
                'type' => 'image',
                'upload' => true,
                'disk' => 'uploads', 
                'orderable'=>false,
            ],
            [   // Upload
                'name' => 'client_stamp',
                'label' => 'Stamp',
                'type' => 'image',
                'upload' => true,
                'orderable'=>false,
                'disk' => 'uploads', 
            ],
            [
                'name' => 'address_name',
                'label' => trans('Address'),
                'type' => 'text',
                'orderable'=>false,
            ],
    
            [
                'name' => 'registration_number',
                'type' => 'text',
                'label' => 'Registration Number',
                'orderable'=>false,
            ],
            [
                'name' => 'pan_vat_no',
                'type' => 'text',
                'label' => 'PAN/VAT Number',
                'orderable'=>false,
            ],
            [
                'name' => 'phone',
                'label' => trans('common.phone_no'),
                'type' => 'text',
                'orderable'=>false,
            ],
            [
                'name' => 'fax',
                'label' => trans('common.fax'),
                'type' => 'text',
                'orderable'=>false,
            ],
            [
                'name' => 'email',
                'label' => trans('common.email'),
                'type' => 'email',
                'orderable'=>false,
            ],

        ];
        $this->crud->addColumns($cols);
        // $this->crud->removeButtons(['create','delete']);

    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(AppSettingRequest::class);

        $arr = [
            $this->addClientIdField(),
            [   // Upload
                'name' => 'client_logo',
                'label' => 'Logo',
                'type' => 'image',
                'upload' => true,
                'disk' => 'uploads', 
                'aspect_ratio' => 1,
                'wrapperAttributes' => [
                    'style'=>'max-width:300px',
                    'class' => 'form-group col-md-4',
                ],
            ],
            [   // Upload
                'name' => 'client_stamp',
                'label' => 'Stamp',
                'type' => 'image',
                'upload' => true,
                'disk' => 'uploads', 
                'aspect_ratio' => 1,
                'wrapperAttributes' => [
                    'style'=>'max-width:300px',
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'legend1',
                'type' => 'custom_html',
                'value' => '<b><legend>Office Description :</legend></b><br>',
            ],
            [
                'name' => 'code',
                'label' => trans('common.code'),
                'type' => 'text',
                'wrapper' => [
                    'class' => 'form-group col-md-2',
                ], 
            ],           
            [
                'name' => 'office_name',
                'label' => trans('common.name_en'),
                'type' => 'text',
                'attributes' => [
                    'id' => 'office_name',
                    'required' => 'required',
                    'max-length' => 200,
                ],
                'wrapper' => [
                    'class' => 'form-group col-md-6',
                ],
            ],
            [
                'name' => 'address_name',
                'type' => 'text',
                'label' => 'Address',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => 'required',
                ],
            ],
            [
                'name' => 'fiscal_year_id',
                'type' => 'select2',
                'label' => 'Fiscal Year',
                'entity'=>'fiscalYear',
                'attribute' => 'code',
                'modal'=> MstFiscalYear::class,
                'wrapper' => [
                    'class' => 'form-group col-md-2',
                ],
                'attributes' => [
                    'required' => 'required',
                ],
            ],
            [
                'name' => 'phone',
                'type' => 'number',
                'label' => 'phone',
                'wrapper' => [
                    'class' => 'form-group col-md-3',
                ],
            ],
            [
                'name' => 'fax',
                'type' => 'text',
                'label' => 'fax',
                'wrapper' => [
                    'class' => 'form-group col-md-3',
                ],
            ],
            [
                'name' => 'email',
                'type' => 'email',
                'label' => 'email',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'purchase_order_seq_key',
                'type' => 'text',
                'label' => 'Purchase Order Sequence Key',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => 'required',
                ],
            ],
            [
                'name' => 'bill_seq_key',
                'type' => 'text',
                'label' => 'Bill Sequence Key',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => 'required',
                ],
            ],
            [
                'name' => 'order_seq_key',
                'type' => 'text',
                'label' => 'Order Sequence Key',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => 'required',
                ],
            ],
            [
                'name' => 'sample_seq_key',
                'type' => 'text',
                'label' => 'Sample Sequence Key',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => 'required',
                ],
            ],
            [
                'name' => 'registration_number',
                'type' => 'text',
                'label' => 'Company Registration Number',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'pan_vat_no',
                'type' => 'text',
                'label' => 'PAN/VAT Number',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
          
            [
                'name' => 'legend12',
                'type' => 'custom_html',
                'value' => '<b><legend> Title of Letter: :</legend></b>',
            ],
            [
                'name' => 'div_1-0',
                'type' => 'plain_html',
                'value' => '<div class="row shakti col-md-12">',
            ],
            [
                'name' => 'div_1-2',
                'type' => 'plain_html',
                'value' => '<div class="col-md-6">',
            ],
            [
                'name' => 'letter_head_title_1',
                'label' =>  trans('common.title1'),
                'type' => 'text',
            ],
            [
                'name' => 'letter_head_title_2',
                'label' =>  trans('common.title2'),
                'type' => 'text',
            ],
            [
                'name' => 'letter_head_title_3',
                'label' =>  trans('common.title3'),
                'type' => 'text',
            ],
            [
                'name' => 'letter_head_title_4',
                'label' => trans('common.title4'),
                'type' => 'text',
            ],
            [ 
                'name' => 'div_1-2_close',
                'type' => 'plain_html',
                'value'=> '</div>',
            ],
            [
                'name' => 'div_1-2a',
                'type' => 'plain_html',
                'value' => '<div class="col-md-6">',
            ],
            [
                'name' => 'div_1-2ac',
                'type' => 'plain_html',
                'value' => '<div class="col-md-12">
                <style>
                    .head-address{
                        text-align: center;
                    }
                </style>
                <h3 class="head-address" style="color:red; margin-left:30px;text-decoration: underline">
                <span id=""> Demo of a letter heading </span>
                </h3>
                <br/>
                <h2 class="head-address" id="letter_head_title_label">
                    <span id="letter_head_title_1_label">-</span><br/> 
                    <span style="font-size: 18px;" id="letter_head_title_2_label">-</span><br/> 
                    <span style="font-size: 18px;" id="letter_head_title_3_label">-</span><br/> 
                    <span style="font-size: 16px;" id="letter_head_title_4_label">-</span>
                </h2>
                </div>',
            ],
            [ 
                'name' => 'div_1-2a_close',
                'type' => 'plain_html',
                'value'=> '</div>',
            ],

            [ 
                'name' => 'div_1-0_close',
                'type' => 'plain_html',
                'value'=> '</div>',
            ],
            $this->addRemarksField(),
            [
                'name'=>'is_active',
                'label'=>'Is Active ?',
                'type'=>'radio',
                'default'=>0,
                'inline' => true,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'options'=>
                [
                    1=>'Yes',
                    0=>'No',
                ],
            ],


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
