<?php

namespace App\Models\Pms;

use App\Base\BaseModel;
use App\Models\AppClient;
use Backpack\CRUD\app\Models\Traits\CrudTrait;


class MstUnit extends BaseModel
{
    use CrudTrait;

    protected $table = 'phr_mst_units';
    protected $guarded = ['id','created_by'];
    protected $fillable = ['code','name_en','name_lc','dependent_unit_id','count','is_active','client_id','updated_by'];

    public function dependent_unit() {
        return $this->belongsTo(MstUnit::class);
    }
    public function client()
    {
        return $this->belongsTo(AppClient::class,'client_id','id');
    }
}
