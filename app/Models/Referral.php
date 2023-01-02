<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Referral extends BaseModel
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    
    public static $referral_type = [
        1 => 'WALK-IN',
        2 => 'REFERRAL CONSULTANT',
        3 => 'HOSPITALS / CLINICS',
        4 => 'MAGZINE / NEWSPAPER',
        5 => 'CAMP',
        6 => 'Current / Previous Patient',
        7 => 'Internal Staff',
        8 => 'Internal Constultant',
        9 => 'Relatives / Friends',
        10 => 'Advertisement Tv',
    ];

    protected $table = 'mst_referrals';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['code','name','referral_type','contact_person','phone','email','address','is_active','discount_percentage'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function patient(){
        return $this->belongsTo(Patient::class,'blood_group_id','id');
    }
    

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

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
