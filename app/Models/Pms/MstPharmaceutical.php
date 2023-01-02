<?php

namespace App\Models\Pms;

use App\Base\BaseModel;
use Modules\App\Entities\AppClient;
use Backpack\CRUD\app\Models\Traits\CrudTrait;


class MstPharmaceutical extends BaseModel
{
    use CrudTrait;

    protected $table = 'phr_mst_pharmaceuticals';
    protected $guarded = ['id','created_by'];
    protected $fillable = ['code','name','address','email','contact_person','contact_number','website','is_active','updated_by','client_id'];

    public function client()
    {
        return $this->belongsTo(AppClient::class,'client_id','id');
    }
}
