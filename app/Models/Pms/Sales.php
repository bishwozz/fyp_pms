<?php

namespace App\Models\Pms;

use App\Base\BaseModel;
use App\Models\AppClient;
use App\Models\Pms\Item;
use App\Models\Pms\MstSupplier;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;


class Sales extends BaseModel
{
    use CrudTrait;


    public static $rate_type = [
        1=>'General Rate',
    ];
    public static $card_type = [
        1=>'Master Card',
        2=>'Visa Card',
    ];
    
    protected $table = 'sales';
    protected $guarded = ['id'];

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
        return '<a class="btn btn-sm btn-primary print-btn mr-2 mt-1" data-fancybox data-type="iframe" href="/admin/billing/patient-billing/'.$this->id.'/generate_sales_bill/'.$this->customer_name .' ('.$this->bill_no.')"><i class="la la-print" style="color: white;"></i></a>';

    }

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
    
    
}
