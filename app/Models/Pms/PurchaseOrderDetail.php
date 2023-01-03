<?php

namespace App\Models\Pms;

use App\Base\BaseModel;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetail extends BaseModel
{
   

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'purchase_order_details';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['purchase_order_id','purchase_order_num','po_date','sup_org_id','expected_delivery','approved_by','gross_amt','discount_amt','tax_amt','other_charges',
    'net_amt','comments','store_id','supplier_id','purchase_order_type_id','requested_store_id','status_id','store_id'];
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
    public function storeEntity(){
        return $this->belongsTo(MstStore::class,'store_id','id');
    }
    public function requestedStoreEntity(){
        return $this->belongsTo(MstStore::class,'requested_store_id','id');
    }

    public function supplierEntity(){
        return $this->belongsTo(MstSupplier::class,'supplier_id','id');
    }

    public function PurchaseOrderEntity(){
        return $this->belongsTo(PurchaseOrderType::class,'purchase_order_type_id','id');
    }

    public function statusEntity(){
        return $this->belongsTo(SupStatus::class,'status_id','id');
    }
   

    public function purchase_items()
    {
        return $this->hasMany(PurchaseItem::class,'po_id','id');
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
