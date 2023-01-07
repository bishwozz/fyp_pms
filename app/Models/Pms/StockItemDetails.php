<?php

namespace App\Models\Pms;

use App\Base\BaseModel;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class StockItemDetails extends BaseModel
{
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'stock_items_details';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
     protected $fillable = [
         'stock_item_id',
         'item_id',
         'barcode_details',
         'sup_org_id',
         'is_active',
         'sales_item_id',
         'store_id',
         'batch_no'

     ];
    // protected $hidden = [];
    // protected $dates = [];
    public $timestamps = false;
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

    public function stockItem()
    {
        return $this->belongsTo(StockItems::class,'stock_item_id','id');
    }

    public function salesItem()
    {
        return $this->belongsTo(SaleItems::class,'sales_item_id','id');
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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
