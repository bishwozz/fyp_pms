<?php

namespace App\Models\Pms;

use App\Base\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItems extends BaseModel
{
    protected $table = 'sales_items';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
     protected $fillable = [
         'stock_id',
         'item_id',
         'unit_id',
         'item_price',
         'item_qty_detail_id',
         'batch_qty_detail_id',
         'add_qty',
         'total_qty',
         'batch_no',
         'batch_qty',
         'sold_qty',
         'item_discount',
         'tax_vat',
         'item_total',
         'sales_id',
         'return_qty',
         'store_id'
     ];
    

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function mstItem()
    {
        return $this->belongsTo(MstItem::class,'item_id','id');
    }
    public function sales()
    {
        return $this->belongsTo(Sales::class,'sales_id','id');
    }

    public function itemQty()
    {
        return $this->belongsTo(ItemQuantityDetail::class,'item_qty_detail_id','id');
    }
    public function batchQty()
    {
        return $this->belongsTo(BatchQuantityDetail::class,'batch_qty_detail_id','id');
    }
    public function barcodeDetails()
    {
        return $this->hasMany(StockItemDetails::class,'sales_item_id','id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getTotalQuantityAttribute()
    {
        $return_qty = $this->return_qty;
        if($return_qty !== null){
            return $this->return_qty;
        }else{
            return $this->total_qty;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */


}
