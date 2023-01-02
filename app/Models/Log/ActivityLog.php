<?php

namespace App\Models\Log;

use App\Models\Log\SessionLog;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class ActivityLog extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $connection = 'pgsql2';
    protected $table = 'activity_log';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable =['session_id','activity_name','activity_type','activity_time','activity_date_ad','activity_date_bs',
                            'description','url','request_method','url_query_string','url_response','status','created_by'];
    // protected $hidden = [];
    // protected $dates = [];

    protected $casts =[
        'description'=>'array',
    ]; 

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function controller_name()
    {
        $controller_name = explode("\\",$this->activity_name);
        return end($controller_name);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function session(){
        return $this->belongsTo(SessionLog::class,'session_id','id');
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
