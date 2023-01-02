<?php

namespace App\Http\Controllers\Admin\Pms;


use Carbon\Carbon;
use App\Models\Pms\Item;
use Illuminate\Http\Request;
use App\Models\Pms\Inventory;
use App\Models\Pms\MstSupplier;
use App\Base\BaseCrudController;
use Illuminate\Support\Facades\DB;
use App\Base\Helpers\JsReportPrint;
use Illuminate\Support\Facades\URL;
use App\Models\CoreMaster\AppSetting;
use App\Models\Pms\MstPharmaceutical;



class InventoryCrudController extends BaseCrudController
{
   public function setup()
    {
        $this->user = backpack_user();
        $this->crud->setModel(Inventory::class);
        $this->crud->setRoute('admin/inventory');
        $this->crud->setEntityNameStrings(trans('Inventory'), trans('Inventory Management'));
        $this->crud->denyAccess(['create','update','delete']);
        $this->crud->clearFilters();
        $this->setFilters();
        $this->crud->addButtonFromView('top', 'print_all', 'inventoryprint', 'end');
        $this->crud->removeActionLabel = true;

    }

    protected function setFilters(){
        $this->crud->addFilter(
            [ // Name(en) filter
                'label' => trans('Supplier'),
                'type' => 'select2',
                'name' => 'supplier_id', // the db column for the foreign key
            ],
            function () {
                // return false;
                return (new MstSupplier())->pluck('name','id')->toArray();
            },
            function ($value) { 
                // if the filter is active
                $item_ids = Item::where('supplier_id',$value)->pluck('id')->toArray();
                $this->crud->addClause('whereIn', 'item_id', $item_ids);
            }
        );

        $this->crud->addFilter(
            [ // Name(en) filter
                'label' => trans('Manufacturer'),
                'type' => 'select2',
                'name' => 'pharmaceutical_id', // the db column for the foreign key
            ],
            function () {
                // return false;
                return (new MstPharmaceutical())->pluck('name','id')->toArray();
            },
            function ($value) { // if the filter is active
                $item_ids = Item::where('pharmaceutical_id',$value)->pluck('id')->toArray();
                $this->crud->addClause('whereIn', 'item_id', $item_ids);
            }
        );

        $this->crud->addFilter(
            [ // simple filter
                'type' => 'text',
                'name' => 'brand_name',
                'label' => trans('Brand Name')
            ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'brand_name', 'iLIKE', "%$value%");
            }
        );
    }
    
    protected function setupListOperation()
    {
        $col=[
            $this->addRowNumber(),
            [
                'name' => 'brand_name',
                'type' => 'model_function',
                'function_name' => 'itemBrand',
                'label' => trans('Brand Name'),
            ],
            [
                'name' => 'supplier_name',
                'type' => 'model_function',
                'function_name' => 'itemSupplier',
                'label' => trans('Supplier'),
            ],
            [
                'name' => 'item_stock_unit',
                'type' => 'model_function',
                'function_name' => 'itemStockUnit',
                'label' => trans('Unit'),
            ],
            [
                'name' => 'batch_number',
                'type' => 'text',
                'label' => trans('Batch No'),
            ],
            [
                'name' => 'manufactured_date',
                'type' => 'model_function',
                'function_name' => 'itemManufacture',
                'label' => trans('Manufactured Date'),
            ],
            [
                'name' => 'expiry_date',
                'type' => 'model_function',
                'function_name' => 'itemExpiry',
                'label' => trans('Expiry Date'),
            ],
            [
                'name'=>'quantity',
                'type' => 'text',
                'label'=>trans('Quantity'),
            ],
        ];
        $this->crud->addColumns(array_filter($col));
    }


    public function printInventoryReport(Request $request)
    {
        $supplier_id =$request->input('supplier_id');
        $pharmaceutical_id = $request->input('pharmaceutical_id');
        $brand_name = $request->input('brand_name');

        $sql = "select pis.batch_number,ppri.manufactured_date_ad,ppri.expiry_date_ad,pis.quantity as stock,pmp.name as pharmaceutical_name,pms.name as supplier_name,pmu.name_en as unit_name,pi.brand_name
                from phr_item_stocks as pis
                Left Join phr_items pi on pi.id = pis.item_id
                Left Join phr_purchase_received_items as ppri on ppri.item_id = pis.item_id AND ppri.batch_number = pis.batch_number AND pi.stock_unit_id = ppri.unit_id
                Left Join phr_mst_suppliers as pms on pms.id = pi.supplier_id 
                Left Join phr_mst_pharmaceuticals as pmp on pmp.id = pi.pharmaceutical_id 
                Left Join phr_mst_units as pmu on pmu.id = pi.stock_unit_id 
                where 1=1";
        if( $supplier_id > 0) {
            $sql .= " AND supplier_id = '$supplier_id' ";
        }
        if( $pharmaceutical_id > 0 ) {
            $sql .= " AND pharmaceutical_id = '$pharmaceutical_id' ";
        }
        if( $brand_name != null ) {
            $sql .= " AND brand_name ilike '%$brand_name%' ";
        }
        $result = DB::select(DB::raw($sql));
        $client_details = AppSetting::with('client')->where('client_id', $this->user->client_id)->first();

        $data['result'] = $result;
        $data['client_details'] = $client_details;
        $template_name = "rkgah8I9Gw";
        JsReportPrint::printPdfReport($data, $template_name);
    }
   
}
