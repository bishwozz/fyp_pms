<?php
namespace  App\Base\Traits;

use ReflectionClass;
use App\Base\DataAccessPermission;
use Illuminate\Support\Facades\Schema;


/**
 *  CheckPermission
 */
trait FilterStore{

    public function filterDataByStoreUser($arr)
    {
        // $arr=["store_id"=>1,"created_by"=>1];
        // dd($arr);
        if (backpack_user()->isStoreUser()) {
            $this->crud->query->where($arr);
        }
    }
}