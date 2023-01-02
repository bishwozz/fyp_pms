<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Models\HrMaster\HrMstEmployees;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use App\Models\Patient;

class LabBill extends BaseModel
{
    use CrudTrait;

    protected $table = 'lab_bills';
    protected $guarded = ['id','created_by'];
    protected $fillable = [];
    
    public function patient()
    {
        return $this->belongsTo(Patient::class,'patient_id','id');
    }
    public function discountApprover()
    {
        return $this->belongsTo(HrMstEmployees::class,'discount_approved_by','id');
    }
    public function creditApprover()
    {
        return $this->belongsTo(HrMstEmployees::class,'credit_approved_by','id');
    }
    public function referral()
    {
        return $this->belongsTo(Referral::class,'referred_by','id');
    }
}
