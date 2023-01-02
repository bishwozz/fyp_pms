<?php

namespace App\Models\HrMaster;

use App\Base\BaseModel;
// use App\Base\DataAccessPermission;
use App\Models\AppClient;
use Backpack\CRUD\app\Models\Traits\CrudTrait;


class HrMstDepartments extends BaseModel
{
    use CrudTrait;
    
    // public $dataAccessPermission = DataAccessPermission::ShowClientWiseDataOnly;
    protected $table = 'hr_mst_departments';
    protected $guarded = ['id','created_by'];
    protected $fillable = ['code','client_id','title','is_active','display_order','updated_by'];

    public function client()
    {
        return $this->belongsTo(AppClient::class,'client_id','id');
    }

    //model button
    public function subDepartment()
    {
        return '<a href="/admin/hrmstdepartments/'.$this->id.'/hrmstsubdepartments" class="btn btn-primary active" style="font-size: 12px; font-weight: bold;" data-toggle="tooltip" title="SubDepartment">SubDepartment</a>';

    }
}
