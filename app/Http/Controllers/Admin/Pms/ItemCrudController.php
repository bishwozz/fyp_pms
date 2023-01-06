<?php

namespace App\Http\Controllers\Admin\Pms;

use Carbon\Carbon;

use App\Models\Pms\Item;
use App\Models\Pms\MstUnit;
use App\Models\Pms\ItemUnit;
use App\Models\Pms\MstBrand;
use Illuminate\Http\Request;
use App\Models\Pms\ItemStock;
use App\Base\Traits\ParentData;
use App\Models\Pms\MstCategory;
use App\Models\Pms\MstSupplier;
use App\Base\BaseCrudController;
use App\Models\Pms\MstGenericName;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use App\Models\Pms\MstPharmaceutical;
use App\Http\Requests\Pms\ItemRequest;
use App\Base\Operations\FetchOperation;


class ItemCrudController extends BaseCrudController
{
    use ParentData;
    use FetchOperation;

   public function setup()
    {
       
        $this->crud->setModel(Item::class);
        $this->crud->setRoute('admin/item');
        $this->crud->setEntityNameStrings(trans('Item'), trans('Item Details'));
        $this->crud->clearFilters();
        $this->setFilters();

    }

    // public function index()
    // {
    //     $this->crud->hasAccessOrFail('list');
    //     $this->getData();
    //     $this->data['items'] = Item::all();
    //     return view('pharmacy::pharmacy._item_list', $this->data)->with('no',1);
    // }

    protected function setFilters(){
        $this->crud->addFilter(
            [ // simple filter
                'type' => 'text',
                'name' => 'code',
                'label' => 'Code',
            ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'code', 'iLIKE', "%$value%");
            }
        );
  
          $this->crud->addFilter([
            'type' => 'text',
            'name' => 'brand_name',
            'label'=> trans('Brand Name')
          ], 
          false, 
          function($value) { // if the filter is active
            $this->crud->addClause('where', 'name', 'iLIKE', "%$value%");
          });

          $this->crud->addFilter([
            'type' => 'text',
            'name' => 'name',
            'label'=> trans('Generic Name')
          ], 
          false, 
          function($value) { // if the filter is active
            $this->crud->addClause('where', 'name', 'iLIKE', "%$value%");
          });

          $this->crud->addFilter(
            [ // Name(en) filter
                'label' => trans('Category'),
                'type' => 'select2',
                'name' => 'category_id', // the db column for the foreign key
            ],
            function () {
                // return false;
                return (new MstCategory())->getFilterTitleComboOptions();
            },
            function ($value) { // if the filter is active
            
                $this->crud->addClause('where', 'category_id', $value);
            }
        );

          $this->crud->addFilter(
            [ // Name(en) filter
                'label' => trans('Supplier'),
                'type' => 'select2',
                'name' => 'supplier_id', // the db column for the foreign key
            ],
            function () {
                // return false;
                return (new MstSupplier())->getFilterNameComboOptions();
            },
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'supplier_id', $value);
            }
        );
          $this->crud->addFilter(
            [ // Name(en) filter
                'label' => trans('Pharmaceutical'),
                'type' => 'select2',
                'name' => 'pharmaceutical_id', // the db column for the foreign key
            ],
            function () {
                // return false;
                return (new MstPharmaceutical())->getFilterNameComboOptions();
            },
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'pharmaceutical_id', $value);
            }
        );
    }

    protected function setupListOperation()
    {
        $col=[
            $this->addRowNumber(),
            $this->addCodeColumn(),
            [
                'label'=>trans('Item Name'),
                'type' => 'text',
                'name' => 'name', 
            ],
            [
                'label'=>trans('Supplier'),
                'type' => 'select',
                'name' => 'supplier_id', 
                'entity' => 'supplier', 
                'attribute' => 'name', 
                'model' => MstSupplier::class,
            ],
            [
                'label'=>trans('Brand Name'),
                'type' => 'select',
                'name' => 'brand_id', 
                'entity' => 'mstbrand', 
                'attribute' => 'name_en', 
                'model' => MstBrand::class,
            ],
            
        ];
        $this->crud->addColumns(array_filter($col));
        $this->crud->orderBy('created_at',"DESC");
    }



    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ItemRequest::class);
        $arr=[
            $this->addCodeField(),
            $this->addClientIdField(),
            [
                'label'=>trans('Generic Name'),
                'type' => 'text',
                'name' => 'name', 
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'label'=>trans('Supplier'),
                'type' => 'select2',
                'name' => 'supplier_id', 
                'entity' => 'supplier', 
                'attribute' => 'name', 
                'model' => MstSupplier::class,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'label'=>trans('Category'),
                'type' => 'select2',
                'name' => 'category_id', 
                'entity' => 'category', 
                'attribute' => 'title_en', 
                'model' => MstCategory::class,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],

            [
                'name'=>'brand_id',
                'type'=>'relationship',
                'label'=>trans('Brand'),
                'entity'=>'mstbrand',
                'model'=>MstBrand::class,
                'attribute'=>'name_en',
                'inline_create'=>[
                    'entity'=>'/mstbrand',
                    'modal_class' => 'modal-dialog modal-xl',
                ],
                'data_source' => '/admin/item/fetch/mstbrand',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
         
            [
                'label'=>trans('Unit'),
                'type' => 'select2',
                'name' => 'unit_id', 
                'entity' => 'mstunit', 
                'attribute' => 'name_en', 
                'model' => MstUnit::class,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'stock_alert_minimun',
                'type' => 'number',
                'label' => 'Stock Alert Minimum',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
     
            [
				'name' => 'tax_vat',
				'type' => 'number',
				'label' => trans('Tax Vat'),
				'wrapper' => [
					'class' => 'form-group col-md-4',
				],
				'attributes' => [
					'id' => 'tax_vat',
				],

			],
            [
				'name' => 'is_taxable',
				'label' => trans('Is Taxable?'),
				'type' => 'radio',
				'default' => 1,
				'inline' => true,
				'options' =>
				[
					1 => 'Yes',
					0 => 'No',
				],
				'wrapper' => [
					'class' => 'form-group col-md-4',
				],
				'attributes' => [
					'id' => 'is_taxable',
					'onChange' => 'LMS.setIsTaxableField()',
				],
			],
            [
				'name' => 'is_free',
				'label' => trans('Is Free ?'),
				'type' => 'radio',
				'default' => 1,
				'inline' => true,
				'options' =>
				[
					1 => 'Yes',
					0 => 'No',
				],
				'wrapper' => [
					'class' => 'form-group col-md-4',
				],
			],
            [
				'name' => 'is_deprecated',
				'label' => trans('Is Deprecated?'),
				'type' => 'radio',
				'default' => 1,
				'inline' => true,
				'options' =>
				[
					1 => 'Yes',
					0 => 'No',
				],
				'wrapper' => [
					'class' => 'form-group col-md-4',
				],
			],
            $this->addIsActiveField(),
            [
                'name' => 'description',
                'type' => 'textarea',
                'label' => 'Description',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-12',
                ],
            ],
        ];
        $arr = array_filter($arr);
        $this->crud->addFields($arr); 
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }


    public function fetchMstUnit()
    {   
        return $this->fetch(['model'=>MstUnit::class,'searchable_attributes' => ['name_en']]);
    }
    public function fetchMstbrand()
    {   
        return $this->fetch(['model'=>MstBrand::class,'searchable_attributes' => ['name_en']]);
    }

   
}
