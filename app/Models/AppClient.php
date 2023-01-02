<?php

namespace App\Models;

use App\Models\CoreMaster\AppSetting;
use Illuminate\Database\Eloquent\Model;
use App\Models\CoreMaster\MstFedLocalLevel;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class AppClient extends Model
{
    use CrudTrait;

    protected $table = 'app_clients';
    protected $guarded = ['id','created_by','updated_by'];
    protected $fillable = ['code','name','fed_local_level_id','admin_email','short_name','prefix_key','remarks','is_active'];

    public function fed_local_level() {
        return $this->belongsTo(MstFedLocalLevel::class,'fed_local_level_id','id');
    }

    public function appSetting()
    {
        return $this->hasOne(AppSetting::class,'client_id','id');
    }
}
