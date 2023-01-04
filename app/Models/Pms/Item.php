<?php

namespace App\Models\Pms;

use App\Base\BaseModel;
use App\Models\AppClient;
use App\Models\Pms\MstUnit;
use App\Models\Pms\ItemUnit;
use App\Models\Pms\MstCategory;
use App\Models\Pms\MstSupplier;
use App\Models\Pms\MstGenericName;
use Illuminate\Support\Facades\DB;
use App\Models\Pms\MstPharmaceutical;
use Backpack\CRUD\app\Models\Traits\CrudTrait;


class Item extends BaseModel
{
    use CrudTrait;

    protected $table = 'phr_items';
     protected $keyType = 'string';
    protected $guarded = ['id','created_by'];
    protected $fillable = ['client_id','code','supplier_id','category_id','brand_id','name','unit_id',
    'pharmaceutical_id','stock_alert_minimun','is_free','is_deprecated','description','is_active','updated_by'];

    public function supplier() {
        return $this->belongsTo(MstSupplier::class,'supplier_id','id');
    }

    public function category() {
        return $this->belongsTo(MstCategory::class,'category_id','id');
    }

    public function pharmaceutical() {
        return $this->belongsTo(MstPharmaceutical::class,'pharmaceutical_id','id');
    }
    public function generic_name() {
        return $this->belongsTo(MstGenericName::class,'generic_name_id','id');
    }

    // public function stock_unit() {
    //     return $this->belongsTo(MstUnit::class,'stock_unit_id','id');
    // }

    public function client()
    {
        return $this->belongsTo(AppClient::class,'client_id','id');
    }

    public function itemunits()
    {
        return $this->hasMany(ItemUnit::class, 'item_id', 'id');
    }

    public function mstbrand()
    {
        return $this->belongsTo(MstBrand::class, 'brand_id', 'id');
    }
    public function mstunit()
    {
        return $this->belongsTo(MstUnit::class, 'unit_id', 'id');
    }

    public function unitlistbyitem()
    {
        $items =  DB::select(DB::raw("select t.item_id, t.unit_id, t.price,t.batch_number,pmu.name_lc as unit_name
        from phr_item_units t
        left join phr_mst_units pmu on pmu.id = t.unit_id
        inner join (
            select unit_id, max(created_at) as MaxDate
            from phr_item_units
            where item_id = '$this->id' 
            group by unit_id
        ) tm on t.unit_id = tm.unit_id and t.created_at = tm.MaxDate
        where t.item_id = '$this->id'"));
        return $items;
    }

    public function editunitlistbyitem($batch_number)
    {
        $items =  DB::select(DB::raw("select t.item_id, t.unit_id, t.price,t.batch_number,pmu.name_lc as unit_name
        from phr_item_units t
        left join phr_mst_units pmu on pmu.id = t.unit_id
        inner join (
            select unit_id, max(created_at) as MaxDate
            from phr_item_units
            where item_id = '$this->id' and batch_number = '$batch_number'
            group by unit_id
        ) tm on t.unit_id = tm.unit_id and t.created_at = tm.MaxDate
        where t.item_id = '$this->id'"));
        return $items;
    }


   
    public function item_name_full()
    {
        return $this->code.' : '.$this->brand_name.' ('.$this->generic_name.')';
    }

    public function getFilterBrandNameComboOptions()
    {
        $a = self::selectRaw("brand_name , id");

        return $a->orderBy('id', 'ASC')
            ->get()
            ->keyBy('id')
            ->pluck('brand_name', 'id')
            ->toArray();
    }

}
