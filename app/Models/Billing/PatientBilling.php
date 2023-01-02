<?php

namespace App\Models\Billing;

use App\Base\BaseModel;
use App\Models\Patient;
use App\Models\Lab\LabPatientTestData;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class PatientBilling extends BaseModel
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    public static $rate_type = [
        1=>'General Rate',
    ];
    public static $card_type = [
        1=>'Master Card',
        2=>'Visa Card',
    ];

    protected $table = 'lab_bills';
    // protected $primaryKey = 'id';
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
    public function patientEntity()
    {
        return $this->belongsTo(Patient::class,'patient_id','id');
    }
    public function labPatientTestData()
    {
        return $this->hasMany(LabPatientTestData::class,'bill_id','id');
    }

    //print bill model button
    public function labBillingPrint()
    {
        // return '<a href="/patient-billing/'.$this->id.'/generate_sales_bill" class="btn btn-sm btn-primary print-btn mr-2 mt-1" title="Print Bill"><i class="la la-print" style="color: white;"></i></a>';
        return '<a class="btn btn-sm btn-primary print-btn mr-2 mt-1" target="_blank" href="/admin/billing/patient-billing/default/'.$this->id.'/generate_sales_bill/'.$this->customer_name .' ('.$this->bill_no.')"><i class="la la-print" style="color: white;"></i></a>';

    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function sampleCollected()
    {
        return $this->hasOne(LabPatientTestData::class,'bill_id','id')
                    ->where('collection_status',1)
                    ->whereNotNull('collection_date_time');
    }

    public function getName()
    {
        if($this->patient_id){
            $name = $this->patientEntity->name;
        }else{

            $name=$this->customer_name;
        }

        return $name;
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
