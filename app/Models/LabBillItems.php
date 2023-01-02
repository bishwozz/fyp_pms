<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Models\Lab\LabPanel;
use App\Models\Lab\LabMstItems;
use Backpack\CRUD\app\Models\Traits\CrudTrait;


class LabBillItems extends BaseModel
{
    use CrudTrait;

    protected $table = 'lab_bill_items';
    protected $guarded = ['id','created_by'];
    protected $fillable = [];

    public function labPanel()
    {
        return $this->belongsTo(LabPanel::class,'lab_panel_id','id');
    }

    public function labItem()
    {
        return $this->belongsTo(LabMstItems::class,'lab_item_id','id');
    }
}
