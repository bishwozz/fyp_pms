<?php
namespace App\Base\Traits;

use Illuminate\Support\Facades\DB;


/**
 * To get combo filed from model
 */
trait ComboField
{

    public function getComboFieldAttribute()
    {
        return $this->code. ' - '.$this->name_lc;
    }
    
    public function getFilterComboOptions()
    {
        $a = self::selectRaw("code|| ' - ' || name_en as name_en, id");

        return $a->orderBy('id', 'ASC')
            ->get()
            ->keyBy('id')
            ->pluck('name_en', 'id')
            ->toArray();
    }


    public function getCodeFilterOptions()
    {
        $a = self::selectRaw("code, id");

        return $a->get()
            ->keyBy('id')
            ->pluck('code', 'id')
            ->toArray();
    }

    public function getFieldComboOptions($query)
    {
        $query->selectRaw("code|| ' - ' || name as name, id");
        return $query->orderBy('id', 'ASC')
            ->get();
    }

    public function getFieldComboFiscalOptions($query)
    {
        $query->selectRaw("code, id");

        return $query->orderBy('id', 'ASC')
            ->get();
    }

    public function getClientFieldComboOptions($query)
    {
        $query->selectRaw("lmbiscode|| ' - ' || name_lc as name_lc, id")->where('is_tmpp_applicable', true);

        return $query->orderBy('id', 'ASC')
            ->get();
    }

    public function getClientFilterComboOptions()
    {
        $a = self::selectRaw("lmbiscode|| ' - ' || name_lc as name_lc , id")->where('is_tmpp_applicable', true);

        return $a->orderBy('id', 'ASC')
            ->get()
            ->keyBy('id')
            ->pluck('name_lc', 'id')
            ->toArray();
    }

    public function getFieldActiveComboOptions($query)
    {
        $query->selectRaw("full_name as full_name, id")->where('is_active', true);

        return $query->orderBy('id', 'ASC')
            ->get();
    }

    public function getProvinceFilterComboOptions()
    {
        $a = self::selectRaw("code|| ' - ' || name_lc as name_lc , id")
                    ->whereIn('id', function($query) 
                    {
                        $query->select(DB::raw('distinct province_id'))
                        ->from('mst_fed_district')
                        ->whereIn('id', function($query) {
                        $query->select(DB::raw('distinct district_id'))
                            ->from('mst_fed_local_level')
                            ->where('is_tmpp_applicable', true);
                        });
                    });
        return $a->orderBy('id', 'ASC')
        ->get()
        ->keyBy('id')
        ->pluck('name_lc', 'id')
        ->toArray();

    }

    public function getFilterTitleComboOptions()
    {
        $a = self::selectRaw("code|| ' - ' || title_lc as title_lc , id")->where('client_id',backpack_user()->client_id);

        return $a->orderBy('id', 'ASC')
            ->get()
            ->keyBy('id')
            ->pluck('title_lc', 'id')
            ->toArray();
    }
    public function getFilterNameComboOptions()
    {
        $a = self::selectRaw("code|| ' - ' || name as name , id")->where('client_id',backpack_user()->client_id);

        return $a->orderBy('id', 'ASC')
            ->get()
            ->keyBy('id')
            ->pluck('name', 'id')
            ->toArray();
    }

 

}
