<?php

namespace App\Models\Pms;

use App\Base\BaseModel;
use App\Models\Pms\MstItem;
use App\Models\CoreMaster\MstCountry;
use Illuminate\Database\Eloquent\Model;
use App\Models\CoreMaster\MstFedDistrict;
use App\Models\CoreMaster\MstFedProvince;
use App\Models\CoreMaster\MstFedLocalLevel;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class MstSupplier extends BaseModel
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'mst_suppliers';
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

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function countryEntity()
    {
        return $this->belongsTo(MstCountry::class,'country_id','id');
    }
    public function district()
    {
        return $this->belongsTo(MstFedDistrict::class,'district_id','id');
    }
    public function province()
    {
        return $this->belongsTo(MstFedProvince::class,'province_id','id');
    }
    public function locallevel()
    {
        return $this->belongsTo(MstFedLocalLevel::class,'local_level_id','id');
    }
    public function company()
    {
        return $this->belongsTo(MstPharmaceutical::class,'company_id','id');
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

    public function mstItemEntity()
    {
        return $this->belongsToMany(MstItem::class, 'mst_item_mst_supplier', 'supplier_id', 'item_id');
    }
}
