<?php

namespace App\Models\Lab;

use App\Models\LabBill;
use App\Models\Patient;
use App\Models\Lab\LabMstCategories;
use Illuminate\Support\Facades\Route;
use App\Models\HrMaster\HrMstEmployees;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lab\LabPatientTestResult;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class LabPatientTestData extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'lab_patient_test_data';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    public static $collection_status = 
    [
        0 => 'Pending',
        1 => 'Collected',
    ];
    public static $reported_status = 
    [
        0 => 'Pending',
        1 => 'Reported',
    ];
    public static $approve_status = 
    [
        0 => 'Pending',
        1 => 'Approved',
    ];
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function patient(){
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }
    public function bill(){
        return $this->belongsTo(LabBill::class, 'bill_id', 'id');
    }
    public function category(){
        return $this->belongsTo(LabMstCategories::class, 'category_id', 'id');
    }
    public function doctorEntity(){
        return $this->belongsTo(HrMstEmployees::class, 'doctor_id', 'id');
    }
    public function labTechnicianEntity(){
        return $this->belongsTo(HrMstEmployees::class, 'lab_technician_id', 'id');
    }
    public function labPatientTestResults()
    {
        return $this->hasMany(LabPatientTestResult::class,'patient_test_data_id','id');
    }
    public function printTestReport(){
        return '<a target="_blank" href="/lab-patient-test-data/'.$this->id.'/print-test-report" class="btn btn-sm btn-link print-btn"><i class="la la-print" style="color: white;"></i></a>';
    }
    public function orderNo(){
        return '<a class="btn-link" href="'.current_url().'/'.$this->id.'/edit" data-toggle="tooltip" title="Go to sample collection">'.$this->order_no.'</a>';
    }
    public function orderNoResult(){
        return '<a class="btn-link" href="'.current_url().'/'.$this->id.'/edit" data-toggle="tooltip" title="Go to Result entry">'.$this->order_no.'</a>';
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
