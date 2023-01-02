<?php

namespace App\Models\Lab;

use App\Base\BaseModel;
use App\Models\AppClient;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class LabMstCategories extends BaseModel
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    public static $auto_code = false;

    protected $table = 'lab_mst_categories';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['code','title','description','is_active','client_id'];

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
    public function client()
    {
        return $this->belongsTo(AppClient::class,'client_id','id');
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
    |--------
    
    ------------------------------------------------------------------
    */

     //model button
     public function labItems()
     {
         return '<a href="/admin/lab/lab-mst-categories/'.$this->id.'/lab-mst-items" class="btn btn-sm btn-primary model-btn fa fa-bars p-1 px-2 mr-3" data-toggle="tooltip" title="Lab Items">&nbsp;&nbsp;Items </a>';
     }
}
