<?php

namespace  App\Base\Traits;

use ReflectionClass;
use App\Base\DataAccessPermission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


/**
 *  CheckPermission
 */
trait UserLevelFilter
{
    public function filterListByUserLevel()
    {
        $this->crud->query->where('client_id',backpack_user()->client_id);                    
    }




    public function getFilteredBatchList()
    {
        return BatchQuantityDetail::where('client_id',backpack_user()->client_id)
            ->pluck('batch_no','batch_no');

    }
}
