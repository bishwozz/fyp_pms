<?php

namespace App\Models;

use App\Base\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesBill extends BaseModel
{
    use CrudTrait;

    protected $table = 'sales_bills';
    protected $guarded = ['id','created_by'];
    protected $fillable = [];
    public function patient()
    {
        return $this->belongsTo(Patients::class,'patient_id','id');
    }
    public function patientVisit()
    {
        return $this->belongsTo(PatientVisits::class,'patient_visit_id','id');
    }
}
