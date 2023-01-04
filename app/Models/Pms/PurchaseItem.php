<?php

namespace App\Models\Pms;

use App\Base\BaseModel;
use App\Models\Pms\Item;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class PurchaseItem extends BaseModel
{
    

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'purchase_items';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['purchase_qty','client_id','free_qty','po_id','total_qty','discount',
    'purchase_price','sales_price','item_amount','tax_vat','items_id','discount_mode_id'];
    // protected $hidden = [];
    // protected $dates = [];

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
    public function itemEntity(){
        return $this->belongsTo(Item::class,'items_id','id');
    }
    public function childItemEntity(){
        return $this->belongsTo(Itpem::class,'items_id','id');
    }

    public function discountEntity(){
        return $this->belongsTo(MstDiscMode::class,'discount_mode_id','id');
    }
    public function requestedStoreEntity(){
        return $this->belongsTo(MstStore::class,'requested_store_id','id');
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
