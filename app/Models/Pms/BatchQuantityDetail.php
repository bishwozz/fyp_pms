<?php

namespace App\Models\Pms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchQuantityDetail extends Model
{
    protected $table='batch_qty_detail';
    protected $guarded = ['id'];

 	protected $fillable=['client_id','item_id','batch_no','batch_from','batch_qty','batch_price','created_by','deleted_by','deleted_at','deleted_uq_code'];




    public function storeEntity(){
        return $this->belongsTo(MstStore::class,'store_id','id');
    }

    public function itemEntity(){
        return $this->belongsTo(MstItem::class,'item_id','id');
    }
    public function childItemEntity(){
        return $this->belongsTo(MstItem::class,'item_id','id');
    }

    public function batchEntity(){
        return $this->belongsTo(MstSequence::class,'batch_no','id');
    }

}



