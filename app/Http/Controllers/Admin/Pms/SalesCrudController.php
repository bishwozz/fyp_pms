<?php

namespace App\Http\Controllers\Admin\Pms;


use Carbon\Carbon;
use App\Models\LabBill;
use App\Models\LabBillItems;
use Illuminate\Http\Request;

use App\Models\Pms\Item;
use Illuminate\Http\Response;
use App\Models\Pms\Sales;
use App\Base\Traits\ParentData;
use App\Base\BaseCrudController;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use App\Base\Helpers\JsReportPrint;
use App\Models\Pms\MstCategory;
use App\Models\Pms\MstSupplier;
use App\Models\HrMaster\HrMstEmployees;
use App\Http\Requests\Pms\PurchaseRequest;


class SalesCrudController extends BaseCrudController
{

    public function setup()
    {
        $this->user = backpack_user();

        $this->crud->setModel(Sales::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/sales'.$this->parent('custom_param'));
        $this->crud->setEntityNameStrings('Sales', 'Sales');
        $this->crud->setCreateView('billing.billing-index');
        $this->crud->addButtonFromModelFunction('line','labBillingPrint','labBillingPrint','beginning');
        $this->crud->addClause('where','client_id',$this->user->client_id);
        $this->crud->setEditView('billing.billing-index',$this->data);

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
            'name'  => 'created_at',
            'label' => 'Date range'
          ],
          false,
          function ($value) {
            $dates = json_decode($value);
            if($dates!= null)
            {
                $this->crud->addClause('where', 'created_at', '>=', $dates->from);
                $this->crud->addClause('where', 'created_at', '<=', $dates->to);
            }
          });
    }
    protected function processCustomParams()
    {
            
        $custom_param = $this->parent('custom_param');

        switch ($custom_param) {
            case 'recent':
                $this->crud->addButtonFromView('line','cancelBillBtn','cancelBillBtn','beginning');
                // $this->crud->query->whereNotIn('id',$accepted_bill_ids)->where('is_cancelled',false);
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
            break;

        }
        $this->crud->orderby('created_at','DESC');


    }

    protected function setCustomTabLinks()
    {
            
        $this->data['recent_bills'] = "";
        $this->data['accepted_bills'] = "";
        $this->data['pending_bills'] = "";
        $this->data['cancelled_bills'] = "";
        $this->data['list_tab_header_view'] = 'tab.billing_tab';

    
        $tab = $this->request->bill_status;
        switch ($tab) {
            case 'recent':
                $this->data['recent_bills'] = "disabled active";
                $this->crud->addButtonFromView('line','cancelBillBtn','cancelBillBtn','beginning');
                // $this->crud->query->whereNotIn('id',$accepted_bill_ids)->where('is_cancelled',false);
            break;

            // case 'accepted':
            //     $this->data['accepted_bills'] = "disabled active";
            //     // $this->crud->query->whereIn('id',$accepted_bill_ids)->where('is_cancelled',false);
            // break;

            case 'pending':
                $this->data['pending_bills'] = "disabled active";
                $this->crud->addButtonFromView('line','collectDueBtn','collectDueBtn','beginning');
                $this->crud->query->where('is_paid',false)->where('is_cancelled',false);
            break;
            case 'cancelled':
                $this->data['cancelled_bills'] = "disabled active";
                $this->crud->query->where('is_cancelled',true);
            break;

            default:
                $this->data['recent_bills'] = "disabled active";
                $this->crud->addButtonFromView('line','cancelBillBtn','cancelBillBtn','beginning');
                // $this->crud->query->whereNotIn('id',$accepted_bill_ids)->where('is_cancelled',false);

            break;

        }
        $this->crud->orderby('created_at','DESC');


    }

    public function billCancelView($id)
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
    public function dueCollectionView($id)
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
                'url' => backpack_url('billing/patient-billing'),
            ]);
        }catch(\Throwable $e){
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }


    protected function setupListOperation()
    {
        // $this->crud->removeButtons(['create','update','delete']);
        $col=[
            $this->addRowNumber(),
            [
                'name'=>'bill_no',
                'type' => 'text',
                'label'=>trans('#Bill No.'),
                'orderable'=>false
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



    //get Item info on billing home page
    public function getItemsInfo(Request $request){
        $data = [];
        $data['rate_type'] = '';
        $data['card_type'] = '';
        $data['creditors'] = HrMstEmployees::where('is_credit_approver',true)->where('is_active',true)->get();
        $data['payment_methods']=DB::table('mst_payment_methods')->select('id','title')->orderBy('id')->get();

        return view('billing.billing-items-home',$data);
        // billing-items-home.blade.php
    }


    public function loadItems(Request $request)
    {
        $qs = $request->qs;
        if(!empty($qs)){
            $item = DB::table('vw_items')
                            ->select('id','name','qty','amount as price')
                            ->where('name','iLike',"%$qs%")
                            ->get();
            return response()->json(['status'=>'success','items'=>$item]);                    
        }else{
            return response()->json(['status'=>'fail']);                    
        }
    
    }

    public function getItemRate(Request $request)
    {
        $item_id = $request->item_id;
        $item = DB::table('vw_items')->find($item_id);
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


        if(isset($request)){
            $query = DB::table('sales')->where('client_id',$this->user->client_id)->latest('created_at')->pluck('bill_no')->first();
            $prefix_key = backpack_user()->clientEntity->prefix_key;
            $bill_no = $prefix_key.'-BILL-1';
            if ($query != null) {
                $explode = explode('-',$query);
                $num = end($explode);
                $bill_no = $prefix_key.'-BILL-'.(intval($num) + 1);
            }

      
            DB::beginTransaction();
            try {
                $lab_bill = Sales::create([
                    'client_id'=>$this->user->client_id,
                    'generated_date_bs'=>$date_bs,
                    'generated_date'=>$date_ad,
                    'is_paid'=>$request->payment_method_type == 6 ? 0:1,
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
                            $item = DB::table('vw_items')->find($item_id);

                            // 
                            LabBillItems::create([
                                'client_id'=>$this->user->client_id,
                                'lab_bill_id'=>$lab_bill->id,
                                'lab_item_id'=>$item->item_id,
                                'quantity'=>$request->item_quantity[$key],
                                'rate'=>$request->item_rate[$key],
                                'amount'=>$request->item_amount[$key],
                                'net_amount'=>$request->item_net_amount[$key],
                                'created_by' => $this->user->id,
                                'created_at' => $now,
                            ]);


                            $sold_qty = $request->item_quantity[$key];
                            $changing_items = Item::find($item_id);
                            if($changing_items){
                                $changing_items->current_stock = (
                                    $changing_items->current_stock - $sold_qty
                                );   
                            }
                            $changing_items->save();
                        }

                    }
                }

                DB::commit();
                    // show a success message
                Alert::success(trans('backpack::crud.insert_success'))->flash();
                return response()->json([
                    'status' => true,
                    'url' => backpack_url('sales'),
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


    public function printSalesDetailBill($lab_id)
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
                                        'total_net_amount','payment_method_id','referred_by')
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

        $referred = Referral::find($lab_bill_details->referred_by)->first()->name;

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
        // $template_name = "BJxxWJlN-w";
        // JsReportPrint::printPdfReport($data, $template_name);

        $html = view('billing.patient_sales_bill', $data)->render();
        PdfPrint::printPortrait($html, "patient_sales_bill.pdf");

    }

  
}
