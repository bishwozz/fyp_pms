<?php

namespace App\Models\HrMaster;

use App\Base\BaseModel;
// use App\Base\DataAccessPermission;
use App\AppClient;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;


class HrMstEmployeeCategory extends BaseModel
{
    use CrudTrait;
    // public $dataAccessPermission = DataAccessPermission::ShowClientWiseDataOnly;
    protected $table = 'hr_mst_employee_category';
    protected $guarded = ['id','created_by'];
    protected $fillable = ['client_id','title_en','title_lc','is_active','updated_by'];

    

    public function client()
    {
        return $this->belongsTo(AppClient::class,'client_id','id');
    }

}

