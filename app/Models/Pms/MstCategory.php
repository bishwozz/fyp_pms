<?php

namespace App\Models\Pms;

use App\Base\BaseModel;
use Modules\App\Entities\AppClient;
use Backpack\CRUD\app\Models\Traits\CrudTrait;


class MstCategory extends BaseModel
{
    use CrudTrait;

    protected $table = 'phr_mst_categories';
    protected $guarded = ['id','created_by'];
    protected $fillable = ['code','client_id','title_en','title_lc','description_en','description_lc','is_active','updated_by','client_id'];

    public function client()
    {
        return $this->belongsTo(AppClient::class,'client_id','id');
    }
}
