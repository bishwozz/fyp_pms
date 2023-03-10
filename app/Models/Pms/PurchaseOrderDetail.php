<?php

namespace App\Models\Pms;

use App\Models\User;
use App\Base\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

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
    protected $fillable = ['purchase_order_id','purchase_order_num','po_date','client_id','expected_delivery','approved_by','gross_amt','discount_amt','tax_amt','other_charges',
    'net_amt','comments','supplier_id','status_id'];
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
    public function approvedByEntity(){
        return $this->belongsTo(User::class,'approved_by','id');
    }

    public function createdByEntity(){
        return $this->belongsTo(User::class,'created_by','id');
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
