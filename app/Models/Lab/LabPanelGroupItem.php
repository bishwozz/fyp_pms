<?php

namespace App\Models\Lab;

use App\Base\BaseModel;
use App\Models\Lab\LabGroup;
use App\Models\Lab\LabPanel;
use App\Models\Lab\LabMstItems;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabPanelGroupItem extends BaseModel
{
    use HasFactory;

    protected $table = 'lab_panel_groups_items';
    protected $fillable = ['lab_panel_id','lab_group_id','lab_item_id','client_id','display_order'];


    public function lab_panel(){
        return $this->belongsTo(LabPanel::class,'lab_panel_id','id');
    }

    public function labGroup(){
        return $this->belongsTo(LabGroup::class, 'lab_group_id', 'id');
    }
    public function labItem(){
        return $this->belongsTo(LabMstItems::class, 'lab_item_id', 'id');
    }
}
