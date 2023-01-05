<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Models\Pms\SupStatus;
use App\Models\Pms\MstSequence;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class StockEntry extends BaseModel
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'stock_entries';
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

    public function getBatchNo()
    {
        if($this->sup_status_id == SupStatus::APPROVED){
            $batchId = StockItems::where('stock_id', $this->id)
                    ->where('batch_no', '!=', null)
                    ->first()->batch_no ?? 'n/a';
            return MstSequence::find($batchId)->sequence_code;
        }else{
            return 'N/A';
        }
    }

    public function getStockStatus()
    {
        return ucfirst(SupStatus::find($this->sup_status_id)->name_en) ?? 'n/a';
    }

    public function getDateString()
    {
        return $this->entry_date_bs ? dateToString($this->entry_date_bs) : '';
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
