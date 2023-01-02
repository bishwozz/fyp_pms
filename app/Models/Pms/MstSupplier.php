<?php

namespace App\Models\Pms;

use App\Base\BaseModel;
use Modules\App\Entities\AppClient;
use Backpack\CRUD\app\Models\Traits\CrudTrait;


class MstSupplier extends BaseModel
{
    use CrudTrait;

    protected $table = 'phr_mst_suppliers';
    protected $guarded = ['id','created_by'];
    protected $fillable = ['code','name','description_en','description_lc','is_active',
    'address','email','contact_person','phone','website','client_id','updated_by'];

    public function client()
    {
        return $this->belongsTo(AppClient::class,'client_id','id');
    }
}
