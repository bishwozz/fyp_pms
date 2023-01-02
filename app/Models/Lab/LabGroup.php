<?php

namespace App\Models\Lab;

use App\Base\BaseModel;
use App\Models\AppClient;
use App\Models\Lab\LabGroupItem;
use App\Models\Lab\LabMstCategories;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class LabGroup extends BaseModel
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'lab_groups';
    protected $guarded = ['id'];
    protected $fillable = ['client_id','lab_category_id','code','name','charge_amount','is_active'];

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
    public function lab_category(){
        return $this->belongsTo(LabMstCategories::class,'lab_category_id','id');
    }

    public function items() {
        return $this->belongsToMany(LabMstItems::class, 'lab_group_items','lab_group_id', 'lab_item_id');
    }

    public function labGroupsItems()
    {
        return $this->hasMany(LabGroupItem::class,'lab_group_id','id');
    }
    public function groupItems(){
        $group_item_names='';
       
        if($this->id != ''){
            $group_items = LabGroupItem::where('lab_group_id',$this->id)->get();
            
            foreach($group_items as $pa){
                $group_item_name [] = $pa->item->name;
            }
            if(!empty($group_item_name)){
                $group_item_names = implode(",",$group_item_name);
            }
            return $group_item_names;
        }
        return $group_item_names;
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
