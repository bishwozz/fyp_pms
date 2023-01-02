<?php

namespace App\Http\Controllers\Admin\Billing;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\LabBill;
use App\Models\MstBank;
use App\Models\Patient;
use App\Utils\PdfPrint;
use App\Models\Referral;
use App\Models\Lab\LabPanel;
use App\Models\LabBillItems;
use Illuminate\Http\Request;
use App\Models\Lab\LabMstItems;
use App\Base\BaseCrudController;
use App\Models\Lab\LabGroupItem;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use App\Models\Lab\LabMstCategories;
use App\Models\CoreMaster\AppSetting;
use App\Models\Lab\LabPanelGroupItem;
use App\Models\Billing\PatientBilling;
use App\Models\Lab\LabPatientTestData;
use App\Models\HrMaster\HrMstEmployees;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PatientBillingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PatientBillingCrudController extends BaseCrudController
{
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    protected $user;

    public function setup()
    {
        $this->user = backpack_user();

        CRUD::setModel(PatientBilling::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/billing/patient-billing/'.$this->parent('custom_param'));
        CRUD::setEntityNameStrings('Patient Billing', 'Patient Billing');
        $this->data['patients']=Patient::select('id','name','patient_no','cell_phone')->get();
        CRUD::setCreateView('billing.billing-index',$this->data);
        $this->crud->addButtonFromModelFunction('line','labBillingPrint','labBillingPrint','beginning');
        $this->crud->addClause('where','client_id',$this->user->client_id);
        CRUD::setEditView('billing.billing-index',$this->data);

        $this->setCustomTabLinks();

        $this->crud->clearFilters();
        $this->setFilters();
        $this->processCustomParams();

        $this->checkPermission([
            'getPatientInfo'=>'create',
            'loadLabItems'=>'create',
            'getItemRate'=>'create',
            'storeBill'=>'create',
            'getReferalData'=>'create',
            'printSalesDetailBill'=>'list',
            'billCancelView'=>'list',
            'updateBillCancelStatus'=>'list',
            'dueCollectionView'=>'list',
            'updateDueCollection'=>'list',
            
        ]);

    }

    private function setFilters(){

        $this->crud->addFilter(
            [ 
                'type' => 'text',
                'name' => 'bill_id',
                'label' => 'Bill Number'
            ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'bill_no', 'iLIKE', "%$value%");
            }
        );

        $this->crud->addFilter(
            [ 
                'type' => 'text',
                'name' => 'customer_name',
                'label' => 'Patient Name'
            ],
            false,
            function ($value) { 
                $this->crud->addClause('where', 'customer_name', 'iLIKE', "%$value%");
            }
        );
        $this->crud->addFilter([
            'type'  => 'date_range',
            'name'  => 'generated_date',
            'label' => 'Date range'
          ],
          false,
          function ($value) {
            $dates = json_decode($value);
            if($dates!= null)
            {
                $this->crud->addClause('where', 'generated_date', '>=', $dates->from);
                $this->crud->addClause('where', 'generated_date', '<=', $dates->to);
            }
          });
    }

    protected function setCustomTabLinks()
    {
        $this->data['list_tab_header_view'] = 'tab.custom_tab_links';

        $links[] = ['label' => 'Recent', 'icon' => 'la la-cogs', 'href' => backpack_url('billing/patient-billing/recent')];
        $links[] = ['label' => 'Confirmed', 'icon' => 'la la-cogs', 'href' => backpack_url('billing/patient-billing/confirmed')];
        $links[] = ['label' => 'Credit', 'icon' => 'la la-cogs', 'href' => backpack_url('billing/patient-billing/credit')];
        $links[] = ['label' => 'Cancelled', 'icon' => 'la la-cogs', 'href' => backpack_url('billing/patient-billing/cancelled')];

        $this->data['links'] = $links;
    }

    protected function processCustomParams()
    {
            
        $custom_param = $this->parent('custom_param');

        $accepted_bill_ids = LabPatientTestData::where('collection_status',1)->whereNotNull('collection_datetime')->pluck('bill_id')->toArray();
        $accepted_bill_ids = \array_unique($accepted_bill_ids);
    
        switch ($custom_param) {
            case 'recent':
                $this->crud->addButtonFromView('line','cancelBillBtn','cancelBillBtn','beginning');
                $this->crud->query->whereNotIn('id',$accepted_bill_ids)->where('is_cancelled',false);
            break;

            case 'confirmed':
                $this->crud->query->whereIn('id',$accepted_bill_ids)->where('is_cancelled',false);
            break;

            case 'credit':
                $this->crud->addButtonFromView('line','collectDueBtn','collectDueBtn','beginning');
                $this->crud->query->where('is_paid',false)->where('is_cancelled',false);
            break;
            case 'cancelled':
                $this->crud->query->where('is_cancelled',true);
            break;

            default:
                $this->crud->addButtonFromView('line','cancelBillBtn','cancelBillBtn','beginning');
                $this->crud->query->whereNotIn('id',$accepted_bill_ids)->where('is_cancelled',false);

            break;

        }
        $this->crud->orderby('created_at','DESC');


    }

    public function billCancelView($custom_param,$id)
    {
        $this->data['entry_data'] = LabBill::findOrFail($id);

        return view('dialog.bill-cancel',$this->data);
    }
    public function updateBillCancelStatus(Request $request)
    {
        $bill = LabBill::find($request->bill_id);
        $bill->is_cancelled = true;
        $bill->cancelled_datetime = Carbon::now()->todatetimestring();
        $bill->cancelled_reason = $request->cancelled_reason;
        $bill->save();

        return response()->json(['status'=>'success']);
    }

    //due collection
    public function dueCollectionView($custom_param,$id)
    {
        $this->data['entry_data'] = LabBill::findOrFail($id);
        $this->data['employees'] = HrMstEmployees::all();
        $this->data['card_type'] = PatientBilling::$card_type;
        $this->data['banks'] = MstBank::all();

        $this->data['payment_methods']=DB::table('mst_payment_methods')
                                            ->select('id','title')
                                            ->where('id','!=',6)
                                            ->orderBy('id')
                                            ->get();

        return view('dialog.due-collection',$this->data);
    }

    public function updateDueCollection(Request $request)
    {
        DB::beginTransaction();
        try{
            $bill = LabBill::find($request->bill_id);

            $bill->is_paid = 1;
            $bill->payment_date=dateToday();
            $bill->payment_date_bs=convert_bs_from_ad();
            $bill->payment_method_id=$request->payment_method_type;
            $bill->card_id=isset($request->card_id)?$request->card_id : null;
            $bill->bank_id=isset($request->bank_id)?$request->bank_id : null;
            $bill->cheque_no = isset($request->cheque_no)?$request->cheque_no : null;
            $bill->transaction_number = isset($request->transaction_number)?$request->transaction_number : null;
            $bill->due_received_by= $request->due_received_by;
            $bill->total_paid_amount= $request->net_payable;
            $bill->due_received_datetime= Carbon::now()->toDateTimeString();
            $bill->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'url' => backpack_url('billing/patient-billing/recent'),
            ]);
        }catch(\Throwable $e){
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }
   

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->removeButtons(['create','update','delete']);
        $col=[
            $this->addRowNumber(),
            [
                'name'=>'bill_no',
                'type' => 'text',
                'label'=>trans('#Bill No.'),
                'orderable'=>false
            ],
            [
                'name'=>'customer',
                'type' => 'model_function',
                'label'=>trans('Name'),
                'function_name'=>'getName'
            ],
            [
                'name'=>'created_at',
                'type' => 'datetime',
                'label'=>trans('Date'),
                'orderable'=>false
            ],
            [
                'name'=>'total_gross_amount',
                'type' => 'number',
                'label'=>trans('Gross Amount'),
                'prefix'=>'Rs. ',
                'styles'=>'color:blue;font-weight:550;',
                'orderable'=>false
            ],
            [
                'name'=>'total_discount_amount',
                'type' => 'number',
                'label'=>trans('Discount Amount'),
                'prefix'=>'Rs. ',
                'styles'=>'color:brown;font-weight:550;',
                'orderable'=>false
            ],
            [
                'name'=>'total_net_amount',
                'type' => 'number',
                'label'=>trans('Net Amount'),  
                'prefix'=>'Rs. ',
                'styles'=>'color:darkgreen;font-weight:550;',
                'orderable'=>false
            ],
            [
                'name'=>'total_paid_amount',
                'type' => 'number',
                'label'=>trans('Paid Amount'),  
                'prefix'=>'Rs. ',
                'styles'=>'color:darkgreen;font-weight:550;',
                'orderable'=>false
            ],
            [
                'name'=>'is_paid',
                'type' => 'credit_status_check',
                'label'=>trans('Credit'),
                'orderable'=>false
            ],
         
        ];
        $this->crud->addColumns(array_filter($col));

    }

    //get patient info on billing home page
    public function getPatientInfo(Request $request){
        $patient_id = $request->patient_id;
        $data = [];
        if($patient_id != null){
            $data['patient'] = Patient::find($patient_id);
        }
        $data['rate_type'] = PatientBilling::$rate_type;
        $data['card_type'] = PatientBilling::$card_type;
        $data['doctors'] = HrMstEmployees::whereIn('salutation_id',[5,6,7,8])->get();
        $data['creditors'] = HrMstEmployees::where('is_credit_approver',true)->where('is_active',true)->get();
        $data['discounters'] = HrMstEmployees::where('is_discount_approver',true)->where('is_active',true)->get();
        $data['banks'] = MstBank::all();
        $data['payment_methods']=DB::table('mst_payment_methods')->select('id','title')->orderBy('id')->get();
        $data['referral']=Referral::select('id','name','discount_percentage')
                                    ->where('client_id',backpack_user()->client_id)
                                    ->where('is_active',true)
                                    ->orderBy('id')
                                    ->get();
        $data['referral_type']=Referral::$referral_type;


        return view('billing.billing-patient-home',$data);
        // return response()->json(['status'=>'success','data'=>$data]);
    }


    public function loadLabItems(Request $request)
    {
        $qs = $request->qs;
        if(!empty($qs)){
            $lab_items = DB::table('vw_lab_test_items')
                            ->select('id','test_name as name','code','category_name as category','test_amount as price')
                            ->where('test_name','iLike',"%$qs%")
                            ->orWhere('code','iLike',"%$qs")
                            ->get();
            return response()->json(['status'=>'success','lab_items'=>$lab_items]);                    
        }else{
            return response()->json(['status'=>'fail']);                    
        }
      
    }

    public function getItemRate(Request $request)
    {
        $item_id = $request->item_id;
        $item = DB::table('vw_lab_test_items')->find($item_id);
        if($item){
            return response()->json(['status'=>'success','item'=>$item]);                    
        }else{
            return response()->json(['status'=>'fail']);                    

        }

    }

    //store bill
    protected function storeBill(Request $request)
    {
        $date_bs = convert_bs_from_ad();
        $date_ad = Carbon::now()->toDateString();
        $now = Carbon::now()->todatetimestring();
        $categories = [];

        $address = '';
        if(isset($request->patient_id)){
             $patient = Patient::find($request->patient_id);
             $address=$patient->getFullAddress();
        }

        $age_gender = \str_replace(' ','',$request->age_sex);
        $age_gender = explode('/',$age_gender);

        if(isset($request)){
            $query = DB::table('lab_bills')->where('client_id',$this->user->client_id)->latest('created_at')->pluck('bill_no')->first();
            $prefix_key = appSetting()->bill_seq_key;
            $bill_no = $prefix_key.'1';
            if ($query != null) {
                $explode = explode('-',$query);
                $num = end($explode);
                $bill_no = $prefix_key.(intval($num) + 1);
            }
            DB::beginTransaction();
            try {
                $lab_bill = LabBill::create([
                    'client_id'=>$this->user->client_id,
                    'patient_id'=> isset($request->patient_id) ? $request->patient_id : NULL,
                    'bill_no'=>$bill_no,
                    'customer_name'=>$request->patient_name,
                    'address'=>$address,
                    'age'=>$age_gender[0],
                    'gender'=>$age_gender[1],
                    'item_discount_type'=>$request->item_discount_type,
                    'referred_by'=>$request->referred_by,

                    'generated_date_bs'=>$date_bs,
                    'generated_date'=>$date_ad,
                    'is_paid'=>intval($request->payment_method_type) == 6 ? FALSE:TRUE,
            
                    'payment_method_id'=>$request->payment_method_type,
                    'discount_approved_by'=>isset($request->discount_approved_by)?$request->discount_approved_by : null,
                    'credit_approved_by'=>isset($request->credit_approved_by)?$request->credit_approved_by : null,
                    'card_id'=>isset($request->card_id)?$request->card_id : null,
                    'bank_id'=>isset($request->bank_id)?$request->bank_id : null,
                    'cheque_no'=>isset($request->cheque_no)?$request->cheque_no : null,
                    'transaction_number'=>isset($request->transaction_number)?$request->transaction_number : null,
                    
                    'total_discount_type'=>$request->total_discount_type,
                    'total_discount_value'=>$request->total_discount_value,
                    
                    'total_gross_amount'=>$request->total_gross_amount,
                    'total_discount_amount'=>$request->total_discount_amount,
                    'total_tax_amount'=>$request->total_tax_amount,
                    'total_net_amount'=>$request->total_net_amount,
                    'total_paid_amount'=>$request->total_paid_amount,
                    'total_refund_amount'=>$request->total_refund_amount,
                    'created_by' => $this->user->id,
                    'created_at' => $now,
                ]);
                    if($lab_bill->id){
                        if(isset($request->selected_item)){
                            foreach($request->selected_item as $key=>$item_id){ 
                               
                                //get item detail from view
                                $item = DB::table('vw_lab_test_items')->find($item_id);

                                //build array of category
                                $category = LabMstCategories::where('title',$item->category_name)->first();
                                $categories[$category->id][]=$item;

                                LabBillItems::create([
                                    'client_id'=>$this->user->client_id,
                                    'lab_bill_id'=>$lab_bill->id,
                                    'lab_panel_id'=>$item->panel_id,
                                    'lab_item_id'=>$item->item_id,
                                    'quantity'=>$request->item_quantity[$key],
                                    'rate'=>$request->item_rate[$key],
                                    'discount'=>$request->item_discount[$key],
                                    'amount'=>$request->item_amount[$key],
                                    // 'tax'=>$request->item_tax[$key],
                                    'net_amount'=>$request->item_net_amount[$key],
                                    'created_by' => $this->user->id,
                                    'created_at' => $now,
                                ]);
                            }

                            //create records in lab_patient_data
                            foreach($categories as $key=>$category){

                                $order = DB::table('lab_patient_test_data')->where('client_id',$this->user->client_id)->max('order_no');
                                $ord_prefix_key = appSetting()->order_seq_key;

                                    $order_no = $ord_prefix_key.'100000';
                                    if ($order != null) {

                                        $explode = explode('-',$order);
                                        $num = end($explode);
                                        $order_no = $ord_prefix_key.(intval($num) + 1);
                                    }

                                    $data=[
                                        'client_id'=>$this->user->client_id,
                                        'patient_id'=> isset($request->patient_id) ? $request->patient_id : NULL,
                                        'bill_id'=>$lab_bill->id,
                                        'order_no'=>$order_no,
                                        'category_id'=>$key,
                                        'collection_status'=>0,
                                        'reported_status'=>0,
                                        'dispatch_status'=>0,
                                        'approve_status'=>0,
                                        'created_by' => $this->user->id,
                                        'created_at' => $now,
                                        'deleted_uq_code'=>1
                                    ];
                                    $patient_test_id = DB::table('lab_patient_test_data')->insertGetId($data);

                                //category can have multiple items
                                foreach($category as $cat){
                                    //check for panel, if panel exists get panel items and insert into patient lab data results table
                                    if($cat->panel_id != null){

                                        //get all panel items from lab_panel_group_items using category panel_id
                                        $panel_items= LabPanelGroupItem::where('lab_panel_id',$cat->panel_id)->get();
                                        //looping through panel_items
                                        foreach($panel_items as $pi)
                                        {
                                            //check if group
                                            if($pi->lab_group_id != null)
                                            {
                                                //get all items from a group
                                                $group_items = LabGroupItem::where('lab_group_id',$pi->lab_group_id)->get();
                                                foreach($group_items as $gi){
                                                    DB::table('lab_patient_test_results')
                                                        ->insert([
                                                            'patient_test_data_id'=>$patient_test_id,
                                                            'lab_panel_id'=>$pi->lab_panel_id,
                                                            'lab_group_id'=>$gi->lab_group_id,
                                                            'lab_item_id'=>$gi->lab_item_id
                                                        ]);
                                                }
                                            }else{
                                                DB::table('lab_patient_test_results')
                                                    ->insert([
                                                        'patient_test_data_id'=>$patient_test_id,
                                                        'lab_panel_id'=>$pi->lab_panel_id,
                                                        'lab_group_id'=>null,
                                                        'lab_item_id'=>$pi->lab_item_id
                                                    ]);
                                            }
                                        }
                                    }else{
                                        DB::table('lab_patient_test_results')
                                        ->insert([
                                            'patient_test_data_id'=>$patient_test_id,
                                            'lab_panel_id'=>null,
                                            'lab_group_id'=>null,
                                            'lab_item_id'=>$cat->item_id
                                        ]);
                                    }
                                }

                           }
                        }
                    }
                DB::commit();
                    // show a success message
                Alert::success(trans('backpack::crud.insert_success'))->flash();
                return response()->json([
                    'status' => true,
                    'url' => backpack_url('billing/patient-billing/recent'),
                ]);                    
            } catch (\Throwable $th) {
                DB::rollback();
                Alert::error($th->getMessage())->flash();
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage()
                ], 404);
            }
        }
    }

    // referral data
    public function getReferalData(Request $request)
    {
    DB::beginTransaction();
       try{
            $getCode = Referral::select('code')->orderBy('code','desc')->first();
            if(!$getCode){
                $new_code = 1;
            }else{
                $new_code = ($getCode->code + 1);
            }

            if($request->is_active == "1"){
                $active = true;
            }else{
                $active = false;
            }

            $create_referal = Referral::create([
                'code' => $new_code,
                'name' => $request->name,
                'referral_type' => $request->referal_type,
                'contact_person' => $request->contact_person,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'discount_percentage' => $request->discount_percentage,
                'is_active'=>$active,
            ]);
            DB::commit();
        return response()->json(['status'=>'success','referal'=>$create_referal]);  
       }
        catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status'=>'fail','referal'=>$create_referal]);                 
        }
    }


    public function printSalesDetailBill($custom_param,$lab_id)
    {
        DB::table('lab_bills')
        ->where('id', $lab_id)
        ->update([
            'is_paid' => false
        ]);

        $client_details = AppSetting::with('client')->where('client_id', $this->user->client_id)->first();

        // $lab_orders_type = DB::table('sales_mst_types')->where('code', 'lab')->pluck('id')->first();

        $lab_bill_details = DB::table('lab_bills')->select('id','bill_no','payment_date_bs','total_paid_amount',
                                        'customer_name','address','gender','age','patient_id','generated_date_bs',
                                        'total_gross_amount','total_discount_amount','total_tax_amount','total_refund_amount',
                                        'total_net_amount','payment_method_id','referred_by','created_by')
                                        ->latest('created_at')
                                        ->where('id', $lab_id)
                                        ->first();

        $lab_items = DB::table('lab_bill_items as lbi')->select('lbi.id','lmi.name','lbi.lab_panel_id','lbi.lab_item_id','lbi.quantity','lbi.rate',
                                                                'lbi.discount','lbi.net_amount','lbi.tax','lmc.title as category','lp.name as lap_panel')
        ->leftjoin('lab_mst_items as lmi', 'lbi.lab_item_id', '=', 'lmi.id')
        ->leftjoin('lab_mst_categories as lmc', 'lmi.lab_category_id', '=', 'lmc.id')
        ->leftjoin('lab_panels as lp', 'lbi.lab_panel_id', '=', 'lp.id')
        ->where('lbi.lab_bill_id',$lab_id)
        ->get();


        // $data['payment_method'] = BD::MstPaymentMethod::find($lab_bill_details->payment_method_id)->first()->title;

        $payment_method = DB::table('mst_payment_methods')->select('id','code')
        ->where('id',$lab_bill_details->payment_method_id)
        ->first();

        $referred = Referral::findOrFail($lab_bill_details->referred_by)->name;

        $data['referred_by'] = $referred;
        $data['lab_items'] = $lab_items;
        $data['payment_method'] = $payment_method;
        $data['patient']=Patient::find($lab_bill_details->patient_id);
        $data['lab_bill_details'] = $lab_bill_details;
        $data['client_details'] = $client_details;

        $report_header = AppSetting::where("client_id",$this->user->client_id)->first();
        // dd($report_header);
        $data['report_header'] = $report_header;
        // $app_setting = '1';
        $app_setting = AppSetting::where("client_id",$this->user->client_id)->first();
        if(isset($app_setting->client_logo)){
            $logo_path = public_path('storage/uploads/'.$app_setting->client_logo);
            // Read image path, convert to base64 encoding
            $logoData = base64_encode(file_get_contents($logo_path));
                // Format the image SRC:  data:{mime};base64,{data};
            $logo_encoded = 'data: '.mime_content_type($logo_path).';base64,'.$logoData;
        }else{
            $logo_path = public_path('images/sample_logo.jpg');
            $logoData = base64_encode(file_get_contents($logo_path));
            $logo_encoded = 'data: '.mime_content_type($logo_path).';base64,'.$logoData;
        }
        $data['logo_encoded'] = $logo_encoded;

        $created_by = User::find($lab_bill_details->created_by)->employee_id;

        $sign_encoded='';
        $receptionist_name='';

        if($created_by){
            //get reception signature name
            $receptionist = HrMstEmployees::find($created_by);
            if($receptionist->signature){
                $signature_path = public_path('storage/uploads/'.$receptionist->signature);
                $signData = base64_encode(file_get_contents($signature_path));
                $sign_encoded = 'data: '.mime_content_type($signature_path).';base64,'.$signData;
            }
            $receptionist_name=$receptionist->full_name;
        }

        $data['sign_encoded'] = $sign_encoded;
        $data['receptionist_name'] = $receptionist_name;
        $html = view('billing.patient_sales_bill', $data)->render();
        PdfPrint::printPortrait($html, "patient_sales_bill.pdf");

    }

}