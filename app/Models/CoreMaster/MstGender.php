<?php

namespace App\Models\CoreMaster;

use App\Base\BaseModel;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class MstGender extends BaseModel
{
    use CrudTrait;

    protected $table = 'mst_genders';
    protected $guarded = ['id','created_by'];
    protected $fillable = ['code','name','display_order','remarks','updated_by'];
}
