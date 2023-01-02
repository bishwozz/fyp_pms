<?php

namespace App\Http\Controllers\Admin\Lab;

use Carbon\Carbon;
use App\Models\Lab\LabGroup;
use Illuminate\Http\Request;
use App\Models\Lab\LabMstItems;
use App\Base\BaseCrudController;
use App\Models\Lab\LabGroupItem;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use App\Models\Lab\LabMstCategories;
use App\Http\Requests\LabGroupRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class LabGroupCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LabGroupCrudController extends BaseCrudController
{

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Lab\LabGroup::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/lab/lab-group');
        CRUD::setEntityNameStrings('lab group', 'lab groups');
        $this->crud->clearFilters();
        $this->setFilters();
        $this->checkPermission([
            'fetchLabCategoryItems'=>'create',
        ]);
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    private function setFilters(){

        $this->crud->addFilter(
            [ // simple filter
                'type' => 'text',
                'name' => 'name',
                'label' => 'Group Name'
            ],
            false,
            function($value) { 
                $this->crud->addClause('where', 'name', 'iLIKE', "%$value%");
            }
        );
    }
    protected function setupListOperation()
    {
        $col = [
            $this->addRowNumber(),
            $this->addCodeColumn(),
            [
                'name' => 'name',
                'type' => 'text',
                'label' => trans('lab.group_name'),
            ],
            [
                'name' => 'group_items',
                'label' => trans('Items'),
                'type' => 'model_function',
                'function_name' =>'groupItems',
            ],
            $this->addIsActiveColumn()
        ];
        $this->crud->addColumns(array_filter($col));
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(LabGroupRequest::class);

        $arr = [
            $this->addClientIdField(),
            $this->addCodeField(),
            [
                'name' => 'name',
                'type' => 'text',
                'label' => trans('lab.group_name'),
                 'wrapperAttributes' => [
                     'class' => 'form-group col-md-6',
                 ],
                 'attributes'=>[
                    'required' => 'Required',
                    'maxlength' => '200',
                 ],
            ],
            [
                'name'=>'charge_amount',
                'type' => 'number',
                'label'=>trans('labPanel.charge_amount'),
                'attributes'=>[
                    'maxlength' => '100',
                    "step" => "any"
                 ],
                 'default'=>0,
                'wrapper' => [
                    'class' => 'form-group col-md-5',
                ],
            ],
            [
                'type' => 'plain_html',
                'name'=>'plain_html_2',
                'value' => '<br/>',
            ],
            [
                'name' => 'lab_category_id',
                'type' => 'lab_group_items_select',
                'entity'=>'lab_category',
                'attribute' => 'title',
                'model'=>LabMstCategories::class,
                'label' => trans('labPanel.labcategories'),
                'options'   => (function ($query) {
                    return $query->selectRaw("code|| ' - ' || title as title, id")->where('id','<>',1)
                            ->get();
                        }),
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes'=>[
                    'required' => 'Required',
                    'id' => 'lab-category-id',
                 ],
            ],
            [
                'type' => 'plain_html',
                'name'=>'plain_html_3',
                'value' => '<br />',
            ],

            [
                'name'=>'checklist',
                'type' => 'checklist_filtered',
                'label' =>'Laboratory Tests',
            ],
            [
                'name'=>'general_item_checklist',
                'type' => 'general_category_group_items_checklist',
                'label' =>'General Items',
            ],

            [
                'type' => 'custom_html',
                'name'=>'plain_html_4',
                'value' => '<br />',
            ],
            $this->addIsActiveField(),
        ];
        $arr = array_filter($arr);
        $this->crud->addFields($arr); 
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
    public function store()
    {
        $this->crud->hasAccessOrFail('create');
        $request = $this->crud->validateRequest();

        // if ($request->has('code')) {
        //     $query = $this->crud->model->latest('created_at')->pluck('code')->first();
        //     if ($query != null) {
        //         $code = $query + 1;                
        //     }else{
        //         $code = 1;
        //     }
        // }
        DB::beginTransaction();
        try {
            $labGroup = LabGroup::create([
                'code' => $request->code,
                'name'=>$request->name,
                'charge_amount'=>$request->charge_amount,
                'lab_category_id'=>$request->lab_category_id,
                'is_active'=>$request->is_active,
                'client_id'=> backpack_user()->client_id,
                'created_by' => backpack_user()->id,
                'updated_by' => backpack_user()->id,
                'created_at' => Carbon::now()->todatetimestring(),
                'updated_at' => Carbon::now()->todatetimestring(),
            ]);

            if($labGroup){
                if(isset($request->laboratory_items)){
                    foreach($request->laboratory_items as $la){
                        LabGroupItem::create([
                            'lab_group_id' => $labGroup->id,
                            'lab_item_id' => $la,
                            'display_order'=> $request->display_order[$la],
                        ]);
                    }
                }
                if(isset($request->general_category_items)){
                    foreach($request->general_category_items as $general_item){
                        LabGroupItem::create([
                            'lab_group_id' => $labGroup->id,
                            'lab_item_id' => $general_item,
                            'display_order'=> $request->display_order[$general_item],
                        ]);
                    }
                }
            }

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
        }

        \Alert::success(trans('backpack::crud.insert_success'))->flash();
        return redirect(backpack_url('lab/lab-group'));
    }

    public function update() {
        $this->crud->hasAccessOrFail('update');
        $request = $this->crud->validateRequest();

        $labGroup = LabGroup::find($request->id);
        DB::beginTransaction();
        try {
            $labGroup->update([
                'name'     =>$request->name,
                'charge_amount'     =>$request->charge_amount,
                'lab_category_id'   =>$request->lab_category_id,
                'is_active'         =>$request->is_active,
                'updated_by'        => backpack_user()->id,
                'updated_at'        => Carbon::now()->todatetimestring(),
            ]);

            LabGroupItem::where('lab_group_id', $request->id)->delete();
            if(isset($request->laboratory_items)){
                foreach($request->laboratory_items as $la){
                    LabGroupItem::create([
                        'lab_group_id' => $labGroup->id,
                        'lab_item_id' => $la,
                        'display_order'=> $request->display_order[$la],
                    ]);
                }
            }
            if(isset($request->general_category_items)){
                foreach($request->general_category_items as $general_item){
                    LabGroupItem::create([
                        'lab_group_id' => $labGroup->id,
                        'lab_item_id' => $general_item,
                        'display_order'=> $request->display_order[$general_item],
                    ]);
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
        }

        \Alert::success(trans('backpack::crud.update_success'))->flash();
        return redirect(backpack_url('lab/lab-group'));
    }
    public function fetchLabCategoryItems(Request $request)
    {
        $categoryId = $request->categoryId;
        $alreadySelectedItems = [];
        if(!empty($request->group_id)) {
            $alreadySelectedItems = LabGroupItem::where('lab_group_id', $request->group_id)->pluck('lab_item_id')->toArray();
        }
        
        $lab_items = LabMstItems::where([['lab_category_id',$categoryId],['is_active',true]])->get();
        $returnItems = [];
        foreach($lab_items as $lab_item) {

            $item_display_order = LabGroupItem::where('lab_item_id', $lab_item->id)->where('lab_group_id', $request->group_id)->first();
            if(!isset($item_display_order)){
                $display_order = 0;
            }else{
                $display_order = $item_display_order->display_order;
            }

            $returnItems[] = [
                'id' => $lab_item->id,
                'name' => $lab_item->name,
                'price' => $lab_item->price,
                'checked' => (in_array($lab_item->id, $alreadySelectedItems) ? 'checked' : ''),
                'display_order' => $display_order,
            ];
        }
        return response()->json([
            'lab_items'=>$returnItems
        ]);
    } 
}
