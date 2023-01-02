<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Eloquent\SoftDeletes;
use App\Models\MstBloodGroup;
use App\Models\CoreMaster\MstGender;
use App\Models\CoreMaster\MstCountry;
use App\Models\CoreMaster\MstFedDistrict;
use App\Models\CoreMaster\MstFedProvince;
use App\Models\CoreMaster\MstFedLocalLevel;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Patient extends BaseModel
{
    use CrudTrait;
    use SoftDeletes;

    const Registerated = 1;
    const Appoinment_Taken = 2;
    const Registeration_Paid = 3;
    const Visit_Started = 4;
    const Visit_Ended = 5;
    const Closed = 6;
    const Cancelled = 7;
    const Deleted = 8;
    const Admitted = 9;
    const Discharged = 10;

    protected $table = 'patients';
    
    protected $guarded = ['id','created_by'];
    public static $patient_types = [
        1=>'General',
        2=>'VIP',
        3=>'VVIP',
    ];
    public static $salutation_ids = [
        1=>'Mr.',
        2=>'Mrs.',
        3=>'Ms.',
    ];
    public static $marital_status = [
        0 => 'unmarried',
        1 => 'married',
        2 => 'divorced',
        3 => 'widow',
    ];
    public static $id_types = [
        1 => 'Citizenship',
        2 => 'Voter Card',
        3 => 'Passport',
        4 => 'National Id',
    ];
    public static $age_units = [
        1 => 'Years',
        2 => 'Months',
    ];
    public static $is_referred = [
        0 => 'Self',
        1 => 'Referred',
    ];

    protected $fillable = [
        'patient_no','name','age','ward_no',
        'gender_id','date_of_birth',
        'date_of_birth_bs','citizenship_no', 'doctor_id', 
        'country_id', 'province_id', 'district_id', 'local_level_id', 
        'street_address', 'cell_phone','email', 'religion_id',
        'has_insurance','photo_name','created_at','updated_at',
        'updated_by','client_id','patient_status','is_emergency','registered_date','registered_date_bs',
        'is_referred','referrer_hospital_name','referrer_doctor_name',
        'passport_no','voter_no','national_id_no', 'nationality','patient_type','blood_group_id','hospital_id',
        'salutation_id','marital_status','age_unit','id_type'
    ];

    public function blood_group(){
        return $this->belongsTo(MstBloodGroup::class,'blood_group_id','id');
    }
    public function gender(){
        return $this->belongsTo(MstGender::class,'gender_id','id');
    }

    public function country()
    {
        return $this->belongsTo(MstCountry::class,'country_id','id');
    }

    public function province()
    {
        return $this->belongsTo(MstFedProvince::class,'province_id','id');
    }

    public function district()
    {
        return $this->belongsTo(MstFedDistrict::class,'district_id','id');
    }

    public function locallevel()
    {
        return $this->belongsTo(MstFedLocalLevel::class,'local_level_id','id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctors::class,'doctor_id','id');
    }

    public function local_address()
    {
        return $this->street_address.' , '.$this->locallevel->name.'-'.$this->ward_no.'<br>'.$this->province->name.' , '.$this->district->name;
    }

    public function gender_age()
    {
        return $this->age. ' / ' . $this->gender->name;
    }

    public function full_name()
    {
        return $this->name;
    }

    public function short_name() {
        $names = explode(" ", $this->name);
        return isset($names[0]) ? $names[0] : $this->name;
    }

    public function date_of_birth()
    {
        return $this->convertToNepaliNumber($this->date_of_birth_bs). '<br>' .$this->date_of_birth;
    }

 
    /**
     * Get patient's full addresses
     */
    public function getFullAddress() {
        $address = [];
        if($this->province) {
            $address[] = $this->province->name;
        }
        if($this->district) {
            $address[] = $this->district->name;
        }
        if($this->locallevel) {
            $address[] = $this->locallevel->name;
        }
        $address[] = $this->street_address;
    
        return implode(", ", $address);
    }
}
