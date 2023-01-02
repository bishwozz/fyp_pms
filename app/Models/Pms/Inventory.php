<?php

namespace App\Models\Pms;

use App\Base\BaseModel;
use Illuminate\Support\Facades\URL;
use Modules\App\Entities\AppClient;
use Modules\Pharmacy\Entities\PhrItem;
use Modules\Pharmacy\Entities\PhrMstSupplier;
use Backpack\CRUD\app\Models\Traits\CrudTrait;


class Inventory extends BaseModel
{
    use CrudTrait;
    protected $table = 'phr_item_stocks';
    protected $guarded = [];
    protected $fillable = [];
    
    public function item()
    {
        return $this->belongsTo(PhrItem::class,'item_id','id');
    }

    public function unit() {
        return $this->belongsTo(PhrMstUnit::class,'unit_id','id');
    }

    public function supplier() {
        return $this->belongsTo(PhrMstSupplier::class,'supplier_id','id');
    }

    public function category() {
        return $this->belongsTo(PhrMstCategory::class,'category_id','id');
    }

    public function pharmaceutical() {
        return $this->belongsTo(PhrMstPharmaceutical::class,'pharmaceutical_id','id');
    }

    //model functions
    public function itemBrand(){
        return $this->item->brand_name;
    }
    public function itemSupplier(){
        return $this->item->supplier->name;
    }

    public function itemStockUnit(){
        return $this->item->stock_unit->name_en;
    }
    public function itemManufacture(){
        $date = PhrPurchaseReceivedItem::where([
            ['item_id',$this->item_id],
            ['batch_number',$this->batch_number],
            ['unit_id',$this->item->stock_unit->id]])->pluck('manufactured_date_ad')->first();
        
        return !is_null($date) ? $date : '-';
    }

    public function itemExpiry(){
        $date = PhrPurchaseReceivedItem::where([
            ['item_id',$this->item_id],
            ['batch_number',$this->batch_number],
            ['unit_id',$this->item->stock_unit->id]])->pluck('expiry_date_ad')->first();
        
        return !is_null($date) ? $date : '-';
    }
}
