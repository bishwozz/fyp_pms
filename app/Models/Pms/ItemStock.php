<?php

namespace App\Models\Pms;

use App\Base\BaseModel;
use Modules\App\Entities\AppClient;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;


class ItemStock extends BaseModel
{
    use CrudTrait;
    
    protected $table = 'phr_item_stocks';
    protected $keyType ='string';
    protected $guarded = ['id','created_by'];
    protected $fillable = ['client_id','item_id','batch_number','quantity','updated_by'];

    public function item() {
        return $this->belongsTo(PhrItem::class,'item_id','id');
    }

    public function client()
    {
        return $this->belongsTo(AppClient::class,'client_id','id');
    }
}
