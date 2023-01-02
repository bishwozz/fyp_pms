<?php

namespace App\Models\Pms;

use App\Base\BaseModel;
use App\Models\AppClient;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;


class MstGenericName extends BaseModel
{
    use CrudTrait;

    protected $table = 'phr_mst_generic_names';
    protected $guarded = ['id'];
    protected $fillable = ['code','name','client_id'];

    public function client()
    {
        return $this->belongsTo(AppClient::class,'client_id','id');
    }
}
