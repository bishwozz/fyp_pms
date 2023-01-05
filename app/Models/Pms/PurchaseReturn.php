<?php

namespace App\Models\Pms;

use App\Models\User;
use App\Base\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class PurchaseReturn extends BaseModel
{


    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'purchase_returns';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['total_discount','sup_org_id','tax_total','net_amount','other_charge','taxable_amount','purchase_order_id','return_no','return_date','return_type','approved_by','gross_amt','discount_amt','tax_amt','other_charges',
    'net_amt','comments','store_id','supplier_id','grn_id','return_reason_id','requested_store_id','status_id','grn_sequences_id','store_id'];
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

    public function supplierEntity(){
        return $this->belongsTo(MstSupplier::class,'supplier_id','id');
    }

  

    public function statusEntity(){
        return $this->belongsTo(SupStatus::class,'status_id','id');
    }
    public function returnReasonEntity(){
        return $this->belongsTo(ReturnReason::class,'return_reason_id','id');
    }

    public function grnSequenceEntity(){
        return $this->belongsTo(GrnSequence::class,'grn_sequences_id','id');
    }

    public function purchaseReturnSequenceEntity(){
        return $this->belongsTo(PurchaseReturnSequence::class,'return_no_id','id');
    }
    public function mstStore()
    {
        return $this->belongsTo(MstStore::class,'store_id','id');
    }
    public function supStatus()
    {
        return $this->belongsTo(SupStatus::class,'status_id','id');
    }
  
  
   
    public function items()
    {
        return $this->hasMany(PurchaseReturnItem::class,'purchase_return_id','id');
    }
    public function approvedByEntity(){
        return $this->belongsTo(User::class,'approved_by','id');
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
