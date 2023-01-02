<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Models\CoreMaster\MstGender;
use App\Models\HrMaster\HrMstEmployees;
use App\Models\HrMaster\HrMstDepartments;
use Illuminate\Notifications\Notifiable;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class PatientAppointment extends BaseModel
{
    use CrudTrait, Notifiable;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'patient_appointments';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
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
    public function genderEntity(){
        return $this->belongsTo(MstGender::class,'gender_id','id');
    }
    // public function departmentEntity(){
    //     return $this->belongsTo(HrMstDepartments::class,'department_id','id');
    // }
    // public function doctorEntity(){
    //     return $this->belongsTo(HrMstEmployees::class,'doctor_id','id');
    // }
    public function approvedEntity(){
        return $this->belongsTo(HrMstEmployees::class,'approved_by','id');
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

    public function routeNotificationForLog ($notifiable) {
        return 'identifier-from-notification-for-log: ' . $this->id;
    }
}
