<?php

namespace App\Models\Lab;

use App\Base\BaseModel;
use App\Models\AppClient;
use App\Models\Lab\LabMstCategories;
use App\Models\CoreMaster\MstLabMethod;
use App\Models\CoreMaster\MstLabSample;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class LabMstItems extends BaseModel
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    public static $auto_code = false;
    protected $table = 'lab_mst_items';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['code','lab_category_id','name','reference_from_value',
                    'reference_from_to','unit','price','description','is_active','client_id',
                    'result_field_options','result_field_type','sample_id','method_id','is_testable',
                    'special_reference','is_special_reference'];
    // protected $hidden = [];
    // protected $dates = [];
    public static $result_field_types = [
        0 => 'Number',
        1 => 'Text',
        2 => 'Dropdown'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function lab_category(){
        return $this->belongsTo(LabMstCategories::class,'lab_category_id','id');
    }

    public function client()
    {
        return $this->belongsTo(AppClient::class,'client_id','id');
    }
    public function sample()
    {
        return $this->belongsTo(MstLabSample::class,'sample_id','id');
    }
    public function method()
    {
        return $this->belongsTo(MstLabMethod::class,'method_id','id');
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
