<?php

namespace App\Http\Controllers;

use App\Models\LabBill;
use App\Models\Patient;
use App\Models\ProgramInformation;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(backpack_middleware());
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {

        // $data = DB::table('lab_mst_items')->orderBy('id')->get();
        // $data = DB::table('lab_groups')->orderBy('id')->get();
        // $data = DB::table('lab_group_items')->orderBy('id')->get();
        // $data = DB::table('lab_panels')->orderBy('id')->get();
        // $data = DB::table('lab_panel_groups_items')->orderBy('id')->get();
        // $array='';

        //mst_items
        // foreach($data as $d){
        //     $testable = $d->is_testable == true ? 1 : 0;
        //     $special_reference = $d->is_special_reference == true ? 1 : 0;
        //    $array .= "array('id'=>$d->id,'client_id'=>2,'code'=>'$d->code','lab_category_id'=>$d->lab_category_id,'name'=>'$d->name','reference_from_value'=>'$d->reference_from_value','reference_from_to'=>'$d->reference_from_to','unit'=>'$d->unit','price'=>'$d->price','is_testable'=>$testable,'result_field_type'=>$d->result_field_type,'result_field_options'=>'$d->result_field_options','sample_id'=>$d->sample_id,'method_id'=>$d->method_id,'is_special_reference'=>$special_reference,'special_reference'=>'$d->special_reference'),"."\n"; 
        // }


        // lab_groups
        // foreach($data as $d){
        //    $array .= "array('id'=>$d->id,'client_id'=>2,'code'=>'$d->code','lab_category_id'=>$d->lab_category_id,'name'=>'$d->name'),"."\n"; 
        // }

           // lab_groups_items
        // foreach($data as $d){
        //    $array .= "array('id'=>$d->id,'lab_item_id'=>$d->lab_item_id,'lab_group_id'=>$d->lab_group_id),"."\n"; 
        // }

             // lab_panels
        // foreach($data as $d){
        //    $array .= "array('id'=>$d->id,'client_id'=>2,'code'=>'$d->code','name'=>'$d->name','charge_amount'=>$d->charge_amount,'lab_category_id'=>$d->lab_category_id),"."\n"; 
        // }

             // lab_panels_group_items
        // foreach($data as $d){
        //    $array .= "array('id'=>$d->id,'lab_panel_id'=>$d->lab_panel_id,'lab_group_id'=>$d->lab_group_id,'lab_item_id'=>$d->lab_item_id),"."\n"; 
        // }


        // Storage::put('lab_items_1',$array);

        $this->data['title'] = trans('backpack::base.dashboard'); // set the page title
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => backpack_url('dashboard'),
            trans('backpack::base.dashboard') => false,
        ];
        $this->data['registration_count'] = Patient::where('is_emergency',false)->count();
        $this->data['emergency_registration_count'] = Patient::where('is_emergency',true)->count();
        $this->data['lab__billing_count'] = LabBill::all()->count();
        return view(backpack_view('dashboard'), $this->data);
    }

    /**
     * Redirect to the dashboard.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function redirect()
    {
        // The '/admin' route is not to be used as a page, because it breaks the menu's active state.
        return redirect(backpack_url('dashboard'));
    }
}
