<?php

namespace App\Models\Pms;

use App\Base\BaseModel;
use Modules\App\Entities\AppClient;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;


class ItemUnit extends BaseModel
{
    use CrudTrait;
    
    protected $table = 'phr_item_units';
    protected $guarded = ['id','created_by'];
    protected $fillable = ['client_id','code','item_id','unit_id','price','batch_number','is_active','updated_by','quantity'];

    public function item() {
        return $this->belongsTo(PhrItem::class,'item_id','id');
    }
    public function unit() {
        return $this->belongsTo(PhrMstUnit::class,'unit_id','id');
    }
    public function client()
    {
        return $this->belongsTo(AppClient::class,'client_id','id');
    }

  
}
