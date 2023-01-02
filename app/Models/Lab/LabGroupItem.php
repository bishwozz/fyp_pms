<?php

namespace App\Models\Lab;

use App\Base\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabGroupItem extends BaseModel
{
    use HasFactory;
    protected $table = 'lab_group_items';
    protected $guarded = ['id'];
    protected $fillable = ['lab_group_id','lab_item_id','client_id','display_order'];
    public function item() {
        return $this->belongsTo(LabMstItems::class,'lab_item_id', 'id');
    }
}
