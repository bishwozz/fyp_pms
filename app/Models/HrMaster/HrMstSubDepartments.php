<?php

namespace App\Models\HrMaster;

use App\Base\BaseModel;
// use App\Base\DataAccessPermission;
use App\Models\AppClient;
use Backpack\CRUD\app\Models\Traits\CrudTrait;


class HrMstSubDepartments extends BaseModel
{
    use CrudTrait;
    
    // public $dataAccessPermission = DataAccessPermission::ShowClientWiseDataOnly;
    protected $table = 'hr_mst_sub_departments';
    protected $guarded = ['id','created_by'];
    protected $fillable = ['code','client_id','department_id','title','is_active','display_order','updated_by'];

    public function client()
    {
        return $this->belongsTo(AppClient::class,'client_id','id');
    }

    public function department()
    {
        return $this->belongsTo(HrMstDepartments::class,'department_id','id');
    }
}
