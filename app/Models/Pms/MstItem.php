<?php

namespace App\Models\Pms;

use App\Base\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MstItem extends BaseModel
{

    protected $table = 'mst_items';

    protected $fillable = ['code', 'barcode_details', 'client_id', 'batch_no', 'name', 'description',
     'stock_alert_minimun','category_id' , 'supplier_id', 'brand_id', 'unit_id', 'is_deprecated', 'is_active', 'deleted_by', 'deleted_at', 'deleted_uq_code', 'is_price_editable','is_barcode','tax_vat'];

    protected static function sellingTypes(){
        return [
            0 => 'LIFO',
            1 => 'FIFO'
        ];
}
    public function mstStoreEntity()
    {
        return $this->belongsTo(MstStore::class, 'store_id', 'id');
    }
    public function manySubStoresEntity()
    {
        return $this->belongsToMany(MstStore::class, 'child_item_stores', 'item_id', 'store_id');
    }
    public function category()
    {
        return $this->belongsTo(MstCategory::class, 'category_id', 'id');
    }
    public function mstSubCategory()
    {
        return $this->belongsTo(MstSubcategory::class, 'subcategory_id', 'id');
    }

    public function mstSupplierEntity()
    {
        return $this->belongsTo(MstSupplier::class, 'supplier_id', 'id');
    }



    public function mstBrandEntity()
    {
        return $this->belongsTo(MstBrand::class, 'brand_id', 'id');
    }
    public function brand()
    {
        return $this->belongsTo(MstBrand::class, 'brand_id', 'id');
    }
    public function mstUnitEntity()
    {
        return $this->belongsTo(MstUnit::class, 'unit_id', 'id');
    }

    public function mstDiscModeEntity()
    {
        return $this->belongsTo(MstDiscMode::class, 'discount_mode_id', 'id');
    }

    public function parentDepartment()
    {
        return $this->belongsTo(MstDepartment::class, 'department_id ', 'id');
    }

    public function batchQtyDetails()
    {
        return $this->hasMany(BatchQuantityDetail::class, 'item_id', 'id');
    }
    public function itemQtyDetail()
    {
        return $this->hasOne(ItemQuantityDetail::class, 'item_id', 'id')->where('client_id', backpack_user()->client_id);
    }

    public function mstItemStores()
    {
        return $this->belongsToMany(MstStore::class, 'mst_item_stores', 'item_id', 'store_id');
    }
    // public function mstSupplierEntity()
    // {
    //     return $this->belongsToMany(MstSupplier::class, 'mst_item_mst_supplier', 'item_id', 'supplier_id');
    // }
    public function childItemStores()
    {
        return $this->belongsToMany(MstStore::class, 'child_item_stores', 'item_id', 'store_id');
    }

    public function itemsSampleExcel()
    {
        return '<a href=' . '" /storage/uploads/sampleFiles/products.xlsx' . '" target=' . '"_blank' . '" class=' . '"btn btn-success btn-sm' . '" title=' . '"Download Excel Sample for uploading Bulk Items' . '" ><i class=' . '"fa fa-download' . '" aria-hidden=' . '"true' . '"></i> &nbsp; Sample</a>';
    }

    public function mstFixedAssettTypeEntity()
    {
        return $this->belongsTo(MstAssetType::class, 'asset_type_id', 'id');
    }

    //sales
    public function salesEntity()
    {
        return $this->hasMany(SaleItems::class, 'item_id', 'id');
    }

    public function storeItemSetting(){
        if(backpack_user()->store_id){
            return "<a data-fancybox data-type='ajax' data-src='".url(route('store-item-setting',$this->id))."' href='javascript:;' class='btn btn-sm btn-primary'  data-toggle='tooltip' title='Item Setting'><i class='fas fa-cogs'></i></a>";
        }
    }
}
