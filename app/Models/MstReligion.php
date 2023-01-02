<?php

namespace App\Models;

use App\Base\BaseModel;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class MstReligion extends BaseModel
{
    use CrudTrait;

    protected $table = 'mst_religions';
    protected $guarded = ['id','created_by','updated_by'];
    protected $fillable = ['code','name','display_order','remarks'];
}
