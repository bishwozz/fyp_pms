<?php

namespace App\Models\Pms;

use App\Base\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemQuantityDetail extends BaseModel
{
    protected $table='item_qty_detail';
    protected $guarded = ['id'];

   

    public function storeEntity(){
        return $this->belongsTo(MstStore::class,'store_id','id');
    }

    public function itemEntity(){
        return $this->belongsTo(MstItem::class,'item_id','id');
    }
    public function childItemEntity(){
        return $this->belongsTo(MstItem::class,'item_id','id');
    }
}

