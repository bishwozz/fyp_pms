<?php

namespace App\Base;

use App\Models\AppClient;
use App\Base\Traits\ParentData;
use App\Base\Traits\CheckPermission;
use App\Base\Traits\UserLevelFilter;
use App\Base\Operations\ListOperation;
use App\Base\Operations\ShowOperation;
use App\Base\Traits\ActivityLogTraits;
use App\Base\Operations\CreateOperation;
use App\Base\Operations\DeleteOperation;
use App\Base\Operations\UpdateOperation;
use App\Models\CoreMaster\MstFiscalYear;
use App\Models\CoreMaster\MstFedDistrict;
use App\Models\CoreMaster\MstFedProvince;
use App\Models\CoreMaster\MstFedLocalLevel;
use App\Models\CoreMaster\MstFedLocalLevelType;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Base\Operations\InlineCreateOperation;




class BaseCrudController extends CrudController
{
    use ListOperation;
    use CreateOperation;
    use UpdateOperation;
    use DeleteOperation;
    use ShowOperation;
    use ParentData;
    use CheckPermission;
    use InlineCreateOperation;
    use ActivityLogTraits;


    protected $activity = ['index','create','edit','update','store','show','destroy'];

    public function __construct()
    {

        if ($this->crud) {
            return;
        }
        $this->middleware(function ($request, $next) {
            $this->crud = app()->make('crud');
            // ensure crud has the latest request
            $this->crud->setRequest($request);
            $this->enableDialog(false);
            $this->request = $request;
            $this->setupDefaults();
            $this->setup();
            $this->checkPermission();
            $this->setLogs();
            // $this->isAllowed(['show' => 'list']);
            $this->crud->denyAccess('show');
            $this->setupConfigurationForCurrentOperation();
            return $next($request);
        });
        // parent::__construct();
    }

    
    protected function addCodeField()
    {
        return [
            'name' => 'code',
            'label' => trans('common.code'),
            'type' => 'text',
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ];
    }

    protected function addRowNumber()
    {
        return [
            'name' => 'row_number',
            'type' => 'row_number',
            'label' => 'S.N.',
        ];
    }

    protected function addReadOnlyCodeField()
    {
        return [
            'name' => 'code',
            'label' => trans('common.code'),
            'type' => 'text',
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
            'attributes' => [
                'id' => 'code',
                'readonly' => true,
            ],
        ];
    }

    protected function addClientIdField()
    {
        $user = backpack_user();
        if(!$user->isSystemUser())
        {
            return null;
        }
        else{
            return [  // Select
                'label'  => 'Client',
                'type' => 'select2',
                'name' => 'client_id', // the db column for the foreign key
                'entity' => 'clientEntity', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model' => AppClient::class,
                'options'   => (function ($query) {
                    return $query->selectRaw("name as name, id")
                        ->orderBy('name', 'ASC')
                        ->get();
                }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ];
        }
       
    }
    protected function addPlainHtml()
    {
        return   [
            'type' => 'custom_html',
            'name' => 'plain_html_1',
            'value' => '<br>',
        ];
    }

    protected function addNameEnField()
    {
        return [
            'name' => 'name_en',
            'label' => trans('common.name_en'),
            'type' => 'text',
            'attributes' => [
                'id' => 'name-en',
                'max-length' => 200,
            ],
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ];
    }
    protected function addNameField()
    {
        return [
            'name' => 'name',
            'label' => trans('common.name_en'),
            'type' => 'text',
            'attributes' => [
                'id' => 'name',
                'max-length' => 200,
            ],
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
            'attributes'=>[
                'required' => 'Required',
                'maxlength' => '200',
            ],
        ];
    }

    protected function addNameLcField()
    {
        return [
            'name' => 'name_lc',
            'label' => trans('common.name_lc'),
            'type' => 'text',
            'attributes' => [
                'id' => 'name-lc',
                'required' => 'required',
                'max-length' => 200,
            ],
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ];
    }

    protected function addSettingNameField()
    {
        return [
            'name' => 'office_name',
            'label' => trans('common.name_en'),
            'type' => 'text',
            'attributes' => [
                'id' => 'office_name',
                'required' => 'required',
                'max-length' => 200,
            ],
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ];
    }

    protected function addSettingNameLcField()
    {
        return [
            'name' => 'office_name_lc',
            'label' => trans('common.name_lc'),
            'type' => 'text',
            'attributes' => [
                'id' => 'name-lc',
                'required' => 'required',
                'max-length' => 200,
            ],
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ];
    }
 
    protected function addProvinceField()
    {
        return [
            'name' => 'province_id',
            'type' => 'select2',
            'entity' => 'provinceEntity',
            'attribute' => 'name',
            'model' => MstFedProvince::class,
            'label' => trans('common.province'),
            'options'   => (function ($query) {
                return (new MstFedProvince())->getFieldComboOptions($query);
            }),
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
            'attributes' => [
                'required' => 'required',
            ],
        ];
    }

    protected function addDistrictField()
    {
        return  [
            'name' => 'district_id',
            'type' => 'select2',
            'entity' => 'districtEntity',
            'attribute' => 'name_en',
            'model' => MstFedDistrict::class,
            'label' => 'District',
            'options'   => (function ($query) {
                return (new MstFedDistrict())->getFieldComboOptions($query);
            }),
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ];
    }

    protected function addLocalLevelTypeField()
    {
        return  [
            'name' => 'level_type_id',
            'type' => 'select2',
            'entity' => 'levelTypeEntity',
            'attribute' => 'name_en',
            'model' => MstFedLocalLevelType::class,
            'label' => 'Local Level',
            'options'   => (function ($query) {
                return (new MstFedLocalLevelType())->getFieldComboOptions($query);
            }),
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ];
    }

    protected function addDateBsField()
    {
        return  [
            'name' => 'date_bs',
            'type' => 'nepali_date',
            'label' => trans('common.date_bs'),
            'attributes' => [
                'id' => 'date_bs',
                'relatedId' => 'date_ad',
                'maxlength' => '10',
            ],
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ];
    }
    protected function addDateAdField()
    {
        return [
            'name' => 'date_ad',
            'type' => 'date',
            'label' => trans('common.date_ad'),
            'attributes' => [
                'id' => 'date_ad',
            ],
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ];
    }

    protected function addRemarksField()
    {
        return [
            'name' => 'remarks',
            'label' => trans('common.remarks'),
            'type' => 'textarea',
            'wrapper' => [
                'class' => 'form-group col-md-12',
            ],
        ];
    }
    protected function addDescriptionField()
    {
        return [
            'name' => 'description',
            'label' => trans('common.description'),
            'type' => 'textarea',
            'wrapper' => [
                'class' => 'form-group col-md-12',
            ],
        ];
    }

    protected function addDescriptionColumn()
    {
        return [
            'name' => 'description',
            'label' => trans('common.description'),
            'type' => 'textarea',
        ];
    }



    public function addIsActiveField()
    {
        return [
            'name' => 'is_active',
            'label' => trans('common.is_active'),
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
        ];
    }

    public function addDisplayOrderField()
    {
        return [
            'name' => 'display_order',
            'type' => 'number',
            'label' => trans('common.display_order'),
            'default' => 0,
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ];
    }


    // common columns

    protected function addRowNumberColumn()
    {
        return [
            'name' => 'row_number',
            'type' => 'row_number',
            'label' => trans('common.row_number'),
        ];
    }

    protected function addCodeColumn()
    {
        return [
            'name' => 'code',
            'label' => trans('common.code'),
            'type' => 'text',
            'orderable'=>false
        ];
    }


    protected function addNameEnColumn()
    {
        return [
            'name' => 'name_en',
            'label' => trans('common.name_en'),
            'type' => 'text',
        ];
    }

    protected function addNameLcColumn()
    {
        return [
            'name' => 'name_lc',
            'label' => trans('common.name_lc'),
            'type' => 'text',
        ];
    }

    protected function addNameColumn()
    {
        return [
            'name' => 'name',
            'label' => trans('common.name'),
            'type' => 'text',
        ];
    }

    protected function addSettingNameEnColumn()
    {
        return [
            'name' => 'office_name_en',
            'label' => trans('common.office_name_en'),
            'type' => 'text',
        ];
    }

    protected function addSettingNameLcColumn()
    {
        return [
            'name' => 'office_name_lc',
            'label' => trans('common.office_name_lc'),
            'type' => 'text',
        ];
    }


    protected function addProvinceColumn()
    {
        return [
            'name' => 'province_id',
            'type' => 'select',
            'entity' => 'provinceEntity',
            'attribute' => 'name',
            'model' => MstFedProvince::class,
            'label' => trans('common.province'),
        ];
    }
    

    
    protected function addDistrictColumn()
    {
        return [
            'name' => 'district_id',
            'type' => 'select',
            'entity' => 'districtEntity',
            'attribute' => 'name',
            'model' => MstFedDistrict::class,
            'label' => trans('common.district'),
        ];
    }

    protected function addLocalLevelColumn()
    {
        return [
            'name' => 'level_type_id',
            'type' => 'select',
            'entity' => 'levelTypeEntity',
            'attribute' => 'name',
            'model' => MstFedLocalLevelType::class,
            'label' => trans('common.localLevelType'),
        ];
    }

    
   
    protected function addDateBsColumn()
    {
        return  [
            'name' => 'date_bs',
            'type' => 'nepali_date',
            'label' => trans('common.date_bs'),
        ];
    }
    protected function addDateAdColumn()
    {
        return [
            'name' => 'date_ad',
            'type' => 'date',
            'label' => trans('common.date_ad'),
        ];
    }



    public function addIsActiveColumn()
    {
        return [
            'name' => 'is_active',
            'label' => trans('common.is_active'),
            'type' => 'check',
            'options' =>
            [
                1 => 'Yes',
                0 => 'No',
            ],
            'orderable'=>false
        ];
    }

    public function addDisplayOrderColumn()
    {
        return [
            'name' => 'display_order',
            'type' => 'number',
            'label' => trans('common.display_order'),
        ];
    }
    public function addRemarksColumn()
    {
        return [
            'name' => 'remarks',
            'type' => 'text',
            'label' => trans('common.remarks'),
        ];
    }

    protected function addClientIdColumn()
    {
        $user = backpack_user();
        if(!$user->isSystemUser())
        {
            return null;
        }
        else{
            return [  // Select
                'label'  => 'Client',
                'type' => 'select',
                'name' => 'client_id', // the db column for the foreign key
                'entity' => 'clientEntity', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model' => AppClient::class,
                'orderable'=>false
        ];
        }
       
    }

   

    //common filters

    public function addNameEnFilter()
    {
        return $this->crud->addFilter(

            [
                'label' => trans('common.name_en'),
                'type' => 'text',
                'name' => 'name_en',
            ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'name_en', 'iLIKE', '%' . $value . '%');
            }
        );
    }

    public function addNameLcFilter()
    {
        return $this->crud->addFilter(

            [
                'label' => trans('common.name_lc'),
                'type' => 'text',
                'name' => 'name_lc',
            ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'name_lc', 'iLIKE', '%' . $value . '%');
            }
        );
    }

    protected function readonlyOrDisableFields($fields, $data, $attribute)
    {
        $res = [];
        foreach ($data as $key => $arr) {
            if (isset($arr['name']) && in_array($arr['name'], $fields)) {
                $arr['attributes'][$attribute] = $attribute;
            }
            $res[] = $arr;
        }
        return $res;
    }
}
