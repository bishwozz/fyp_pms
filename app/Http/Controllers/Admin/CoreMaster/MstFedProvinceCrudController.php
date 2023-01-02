<?php

namespace App\Http\Controllers\Admin\CoreMaster;

use App\Base\BaseCrudController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\CoreMaster\MstFedProvince;
use App\Http\Requests\CoreMaster\MstFedProvinceRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MstFedProvinceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MstFedProvinceCrudController extends BaseCrudController
{
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\CoreMaster\MstFedProvince::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mst-fed-province');
        CRUD::setEntityNameStrings(trans('menu.province'), trans('menu.province'));
        $this->checkPermission();
        $this->crud->clearFilters();
        $this->setFilters();
        
        
        // $data = DB::table('mst_fed_coordinates')->get();

        // array('id' => '',
        // 'code' => '',
        // 'level' => '',
        // 'coordinates' =>''
        // );

        // $array='';
        // foreach($data as $d){
        //    $array .= "array('id'=>'$d->id','code'=>'$d->code','level'=>'$d->level','coordinates'=>'$d->coordinates'),"."\n"; 

        // }

        // Storage::put('geo_coordinates',$array);

        // dd($array);
    
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setFilters(){

        $this->crud->addFilter(
            [
                'type' => 'text',
                'name' => 'name',
                'label'=> 'Province Name'
            ], 
            false, 
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'name', 'iLIKE', "%$value%");
            }
        );
    }

    protected function setupListOperation()
    { 
        $cols = [
        $this->addRowNumberColumn(),
        $this->addNameColumn(),

        ];
        $this->crud->addColumns($cols);
    }
    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(MstFedProvinceRequest::class);
        $arr = [
            $this->addReadOnlyCodeField(),
            $this->addPlainHtml(),
            $this->addNameField(),
        ];
        $this->crud->addFields($arr);
        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
