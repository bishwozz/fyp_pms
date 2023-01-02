<?php

namespace App\Models\Lab;

use App\Base\BaseModel;
use App\Models\Lab\LabPanelItems;
use App\Models\Lab\LabMstCategories;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class LabPanel extends BaseModel
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'lab_panels';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['code','name','charge_amount','is_active','lab_category_id','client_id'];
    public static $auto_code = false;

    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function lab_category(){
        return $this->belongsTo(LabMstCategories::class,'lab_category_id','id');
    }
    public function groups(){
        return $this->belongsToMany(LabGroup::class,'lab_panel_groups_items','lab_panel_id','lab_group_id');
    }
    public function items(){
        return $this->belongsToMany(LabMstItems::class,'lab_panel_groups_items','lab_panel_id','lab_item_id');
    }

    public function panelGroupsItems()
    {
        return $this->hasMany(LabPanelGroupItem::class,'lab_panel_id','id');
    }

    public function panelGroups(){

        $panel_item_names='';
       
        if($this->id != ''){
            $panel_items = LabPanelGroupItem::where('lab_panel_id',$this->id)->whereNull('lab_item_id')->get();
            
            foreach($panel_items as $pa){
                    $panel_item_name[] = $pa->labGroup->name;
            }

            if(!empty($panel_item_name)){
                $panel_item_names = implode(",",$panel_item_name);
            }
            return $panel_item_names;
        }
        return $panel_item_names;
    }


    public function panelItems(){
        $panel_item_names='';
       
        if($this->id != ''){
            $panel_items = LabPanelGroupItem::where('lab_panel_id',$this->id)->whereNull('lab_group_id')->get();
            
            foreach($panel_items as $pa){
                    $panel_item_name[] = $pa->labItem->name;
            }

            if(!empty($panel_item_name)){
                $panel_item_names = implode(",",$panel_item_name);
            }
            return $panel_item_names;
        }
        return $panel_item_names;
    }

    public function item()
    {
        return $this->belongsTo(LabMstItems::class,'lab_item_id','id');
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
