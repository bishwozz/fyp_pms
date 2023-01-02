<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\LabBill;
use App\Models\Patient;
use App\Models\Referral;
use App\Exports\ReportExcel;
use Illuminate\Http\Request;
use App\Models\MstBloodGroup;
use App\Base\Helpers\PdfPrint;
use App\Models\Lab\LabMstItems;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\CoreMaster\MstGender;
use App\Models\Lab\LabMstCategories;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\HrMaster\HrMstEmployees;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function getData($report_type)
    {
        $this->user = backpack_user();
        $this->data['report_type'] = trim($report_type, "{}");
        $this->data['blood_groups'] = MstBloodGroup::select('id','type')->get();
        if(backpack_user()->hasRole('referral')){
            $this->data['referals'] = Referral::select('id', 'name')->where('name', backpack_user()->name)->first();
        }else{
            $this->data['referals'] = Referral::select('id', 'name')->where('id', '>', 1)->get();
        }
        $this->data['genders'] = MstGender::select('id', 'name')->get();
        $this->data['payment_modes'] = DB::table('mst_payment_methods')->select('id', 'title')->orderBy('id')->get();
        $this->data['credit_approvers'] = HrMstEmployees::select('id', 'full_name')->where('is_credit_approver', true)->get();
        $this->data['dis_approvers'] = HrMstEmployees::select('id', 'full_name')->where('is_discount_approver', true)->get();
        $this->data['departments'] = LabMstCategories::select('id', 'title')->get();
        $this->data['items'] = LabMstItems::select('id', 'name')->where('is_testable', true)->get();
        $this->data['due_collectors'] = HrMstEmployees::select('id', 'full_name')->where('is_credit_approver', true)->get();
        $this->data['users'] = User::select('id', 'name')->where('id','<>',1)
            ->where('client_id',$this->user->client_id)->where('patient_id',NULL)
            ->where('id','!=',23)
            ->get();
        return view('reports.report_filter', $this->data);
    }

    public function getLmsReportData(Request $request)
    {
        $report_type = $request->report_type;
        $this->data['report_name'] = str_replace('_', ' ', ucwords(trim($report_type, "{}"), '_'));
        $output = [];
        $i = 0;

        switch($report_type){

            // Patient Reports
                case 'patient_report':
                    $patient_no     =   '1=1';
                    $patient_name   =   '1=1';
                    $gender         =   '1=1';
                    $date           =   '1=1';
                    $blood_group    =   '1=1';

                    if($request->patient_no){
                        $patient_no = "p.patient_no iLIKE '%$request->patient_no%' ";
                    }
                    if($request->patient_name){
                        $patient_name = "p.name iLIKE '%$request->patient_name%' ";
                    }
                    if($request->gender_id){
                        $gender =  "mg.id = ". $request->gender_id;
                    }
                    if($request->from_date && $request->to_date ){
                        $date =  "p.registered_date  BETWEEN ' ". $request->from_date  ." ' AND ' ".$request->to_date ."'";
                    }
                    if($request->blood_group ){
                        $blood_group =  "mbg.id = ". $request->blood_group;
                    }                              

                    $patient = DB::table('patients as p')->select('p.patient_no', 'p.name', 'p.age', 'p.registered_date', 'mg.name as gender_name', 'mbg.type as blood_group_name')
                                                        ->leftjoin('mst_genders as mg', 'mg.id', '=','p.gender_id')
                                                        ->leftjoin('mst_blood_groups as mbg', 'mbg.id', '=', 'p.blood_group_id')
                                                        ->whereRaw($patient_no)
                                                        ->whereRaw($date)
                                                        ->whereRaw($blood_group)
                                                        ->whereRaw($patient_name)
                                                        ->whereRaw($gender)
                                                        ->get();

                    $this->data['columns'] = ['S.N', 'Patient No', 'Name', 'Age/Sex', 'Registered Date', 'Blood Group'];
                    foreach($patient as $data){
                        $i++;
                        $output[] = [
                            'S.N'               =>  $i,
                            'Patient No'        =>  $data->patient_no,
                            'Name'              =>  $data->name,
                            'Age/Sex'           =>  $data->age .'/'. $data->gender_name,
                            'Registered Date'   =>  $data->registered_date,
                            'Blood Group'       =>  $data->blood_group_name,
                        ];
                    }

                    $this->data['output'] = $output;
                break;

            // covid_test_report
                case 'covid_test_report':
                    $bill_no        =   '1=1';
                    $date           =   '1=1';
                    $patient_name   =   '1=1';
                    $result_type    =   '1=1';

                    if($request->bill_no){
                        $bill_no = " lb.bill_no iLIKE '%$request->bill_no%' ";
                    }

                    if($request->patient_name){
                        $patient_name = " lb.customer_name iLIKE '%$request->patient_name%' ";
                    }
                    if($request->result_type){
                        $result_type = "lptr.result_value iLIKE '%$request->result_type%' ";
                    }
                    if($request->from_date && $request->to_date ){
                        $date =  "lb.generated_date  BETWEEN ' ". $request->from_date  ." ' AND ' ".$request->to_date ."'";
                    }

                    $covid_report = DB::table('lab_bills as lb')->select("lb.generated_date", 'lb.patient_id', 'lb.customer_name', 'lb.bill_no', 'lptr.lab_item_id', 'lptr.result_value as test_name')
                                    ->leftjoin('lab_bill_items as lbi', 'lbi.lab_bill_id', '=', 'lb.id')
                                    ->leftjoin('lab_panels as lp', 'lp.id' ,'=', 'lbi.lab_panel_id')
                                    ->leftjoin('lab_patient_test_data as lptd', 'lptd.patient_id', '=', 'lb.patient_id')
                                    ->leftjoin('lab_patient_test_results as lptr', 'lptr.patient_test_data_id', '=', 'lptd.id')
                                    ->leftjoin('lab_mst_items as lmi', 'lmi.id', '=', 'lptr.lab_item_id')
                                    ->where('lptr.lab_item_id', '=', 5)
                                    ->where('lp.id', '=', 1)
                                    ->whereRaw($bill_no)
                                    ->whereRaw($date)
                                    ->whereRaw($patient_name)
                                    ->whereRaw($result_type)
                                    ->get();

                    $this->data['columns'] = ['S.N', 'Date', 'Name', 'Bill No', 'Test'];
                    foreach($covid_report as $data){
                        $i++;
                        $output[] = [
                            'S.N'       =>  $i,
                            'Date'      =>  $data->generated_date,
                            'Name'      =>  $data->customer_name,
                            'Bill No'   =>  $data->bill_no,
                            'Test Name' =>  $data->test_name,
                        ];
                    }

                    $this->data['output'] = $output;
                break;

            // Cash Reports
                case 'cash_report':
                    $date   = '1=1';
                    $sum    =  0;
                    if($request->from_date && $request->to_date ){
                        $date =  "generated_date  BETWEEN ' ". $request->from_date  ." ' AND ' ".$request->to_date ."'";
                    }

                    $cash_report = DB::table('lab_bills')->select(DB::raw("SUM(total_net_amount) as net_amount"),"generated_date")
                                        ->groupby('generated_date')
                                        ->where('payment_method_id', 1)
                                        ->whereRaw($date)
                                        ->get();
                    $this->data['columns'] = ['S.N', 'Date', 'Amount'];
                    foreach($cash_report as $data){
                        $i++;
                        $output[] = [
                            'S.N'       =>  $i,
                            'Date'      =>  $data->generated_date,
                            'Amount'    =>  $data->net_amount
                        ];
                        $total = $sum+=$data->net_amount;

                    }
                    if(!isset($total)){
                        $total = 0;
                    }
                    $this->data['total']    =   $total;
                    $this->data['output']   =   $output;

                break;

            // Overall Collection Details
                case 'overall_collection_details':
                    $sum            =   0;
                    $bill_no        =   '1=1';
                    $date           =   '1=1';
                    $patient_name   =   '1=1';
                    $payment_mode   =   '1=1';
                    $user           =   '1=1';

                    if($request->from_date && $request->to_date ){
                        $date =  "p.registered_date  BETWEEN ' ". $request->from_date  ." ' AND ' ".$request->to_date ."'";
                    }
                    if($request->bill_no){
                        $bill_no = " lb.bill_no iLIKE '%$request->bill_no%' ";
                    }
                    if($request->patient_name){
                        $patient_name = " lb.customer_name iLIKE '%$request->patient_name%' ";
                    }
                    if($request->payment_mode){
                        if($request->payment_mode == 6){

                        }
                        $payment_mode =  "lb.payment_method_id = ". $request->payment_mode;
                    }
                    if($request->user){
                        $user =  "lb.created_by = ". $request->user;
                    }

                    $overall_collection_details = DB::table('lab_bills as lb')->select('lb.created_by','lb.total_net_amount', 'lb.generated_date', 'lb.customer_name', 'lb.bill_no', 'mpm.title as payment_mode','u.name as name')
                                    ->leftjoin('mst_payment_methods as mpm', 'mpm.id', '=', 'lb.payment_method_id')
                                    ->leftjoin('users as u', 'u.id', '=', 'lb.created_by')
                                    ->where('lb.payment_method_id', '<>', 6)
                                    ->whereRaw($bill_no)
                                    ->whereRaw($date)
                                    ->whereRaw($patient_name)
                                    ->whereRaw($payment_mode)
                                    ->whereRaw($user)
                                    ->get();

                    $this->data['columns'] = ['S.N', 'Date', 'Name', 'Bill No', 'Mode','User', 'Amount'];
                    foreach($overall_collection_details as $data){
                        $i++;
                        $output[] = [
                            'S.N'       =>  $i,
                            'Date'      =>  $data->generated_date,
                            'Name'      =>  $data->customer_name,
                            'Bill No'   =>  $data->bill_no,
                            'Mode'      =>  $data->payment_mode,
                            'User'      =>  $data->name,
                            'Amount'    =>  $data->total_net_amount,
                        ];
                        $total = $sum+=$data->total_net_amount;
                    }
                    if(!isset($total)){
                        $total = 0;
                    }
                    $this->data['total']    = $total;
                    $this->data['output'] = $output;
                break;

            // Credit Reports
                case 'credit_report':
                    $sum                =   0;
                    $paid_total         =   0;
                    $bill_no            =   '1=1';
                    $date               =   '1=1';
                    $patient_name       =   '1=1';
                    $credit_approver    =   '1=1';

                    if($request->from_date && $request->to_date ){
                        $date =  "lb.generated_date  BETWEEN ' ". $request->from_date  ." ' AND ' ".$request->to_date ."'";
                    }
                    if($request->bill_no){
                        $bill_no = " lb.bill_no iLIKE '%$request->bill_no%' ";
                    }
                    if($request->patient_name){
                        $patient_name = " lb.customer_name iLIKE '%$request->patient_name%' ";
                    }
                    if($request->credit_approver){
                        $credit_approver = "hme.full_name = '". $request->credit_approver ."'";
                    }

                    $credit_report = DB::table('lab_bills as lb')->select(DB::raw("SUM(lb.total_net_amount) as net_amount"),
                                            DB::raw("SUM(lb.total_paid_amount) as paid_amount"),
                                            "lb.generated_date", "lb.customer_name",
                                            "lb.address", 'lb.bill_no', 'hme.full_name')
                                        ->leftjoin('hr_mst_employees as hme', 'hme.id', '=', 'lb.credit_approved_by')
                                        ->groupby('lb.generated_date', 'lb.customer_name', 'lb.address', 'lb.bill_no')
                                        ->where([['lb.payment_method_id',6], ['lb.is_paid', false]])
                                        ->whereRaw($bill_no)
                                        ->whereRaw($date)
                                        ->whereRaw($patient_name)
                                        ->whereRaw($credit_approver)
                                        ->groupBy('lb.total_net_amount','lb.total_paid_amount', 'lb.generated_date', 'lb.customer_name',
                                                    'lb.address', 'lb.bill_no', 'hme.full_name')
                                        ->get();
                    $this->data['columns'] = ['S.N', 'Date', 'Bill No', 'Patient Name', 'Address', 'Paid Amount', 'Net Amount'];
                    foreach($credit_report as $data){
                        $i++;
                        $output[] = [
                            'S.N'           =>  $i,
                            'Date'          =>  $data->generated_date,
                            'Bill No'       =>  $data->bill_no,
                            'Patient Name'  =>  $data->customer_name,
                            'Address'       =>  $data->address,
                            'Paid Amount'   =>  $data->paid_amount,
                            'Net Amount'    =>  $data->net_amount,
                        ];
                        $total = $sum+=$data->net_amount;
                        $paid_total = $sum+=$data->paid_amount;
                    }

                    if(!isset($total)){
                        $total = 0;
                    }
                    if(!isset($paid_total)){
                        $paid_total = 0;
                    }
                    $this->data['total']  = $total;
                    $this->data['paid_total']  = $paid_total;
                    $this->data['output'] = $output;
                break;

            // Bill Reports
                case 'bill_report':
                    $sum            =   0;
                    $bill_no        =   '1=1';
                    $date           =   '1=1';
                    $is_paid        =   '1=1';
                    $patient_name   =   '1=1';

                    if($request->from_date && $request->to_date ){
                        $date =  "lb.generated_date  BETWEEN ' ". $request->from_date  ." ' AND ' ".$request->to_date ."'";
                    }
                    if($request->bill_no){
                        $bill_no = " lb.bill_no iLIKE '%$request->bill_no%' ";
                    }
                    if($request->patient_name){
                        $patient_name = " lb.customer_name iLIKE '%$request->patient_name%' ";
                    }

                    if($request->is_paid == '0'){
                        $is_paid =  "lb.is_paid = '". 0 ."'";
                    }elseif($request->is_paid=='1'){
                        $is_paid =  "lb.is_paid = '". 1 ."'";
                    }

                    $bill_report = DB::table('lab_bills as lb')->select('lb.generated_date', 'lb.customer_name', 'lb.is_paid', 'mpm.title as payment_mode', 'lb.total_gross_amount',
                                        'lb.total_discount_amount', 'lb.total_net_amount', 'lb.address', 'lb.bill_no')
                                        ->leftjoin('hr_mst_employees as hme', 'hme.id', '=', 'lb.credit_approved_by')
                                        ->leftjoin('mst_payment_methods as mpm', 'mpm.id', '=', 'lb.payment_method_id')
                                        ->whereRaw($bill_no)
                                        ->whereRaw($date)
                                        ->whereRaw($is_paid)
                                        ->whereRaw($patient_name)
                                        ->get();
                    $this->data['columns'] = ['S.N', 'Date', 'Bill No', 'Mode', 'Patient Name', 'Gross Amount',  'Discount Amount', 'Is Paid', 'Net Amount'];
                    foreach($bill_report as $data){
                        $i++;
                        $output[] = [
                            'S.N'               =>  $i,
                            'Date'              =>  $data->generated_date,
                            'Bill No'           =>  $data->bill_no,
                            'Mode'              =>  $data->payment_mode,
                            'Patient Name'      =>  $data->customer_name,
                            'Gross Amount'      =>  $data->total_gross_amount,
                            'Discount Amount'   =>  $data->total_discount_amount,
                            'Is Paid'           =>  $data->is_paid == 1 ? 'TRUE' : 'FALSE',
                            'Net Amount'        =>  $data->total_net_amount,
                        ];
                        $total = $sum+=$data->total_net_amount;
                    }
                    if(!isset($total)){
                        $total = 0;
                    }
                    $this->data['total']  = $total;
                    $this->data['output'] = $output;
                break;

            // Bill Reports By Referral
                case 'referral_report':
                    $sum        =   0;
                    $bill_no    =   '1=1';
                    $date       =   '1=1';
                    $referals   =   '1=1';

                    if($request->from_date && $request->to_date ){
                        $date =  "lb.generated_date  BETWEEN ' ". $request->from_date  ." ' AND ' ".$request->to_date ."'";
                    }
                    if($request->bill_no){
                        $bill_no = " lb.bill_no iLIKE '%$request->bill_no%' ";
                    }
                    if($request->referals ){
                        $referals =  "mr.id = ". $request->referals;
                    }

                    $referral_report = DB::table('lab_bills as lb')->select('lb.generated_date', 'lb.customer_name', 'lb.is_paid', 'mpm.title as payment_mode', 'lb.total_gross_amount',
                                        'lb.total_discount_amount', 'lb.total_net_amount', 'lb.address', 'lb.bill_no', 'mr.name as referred_name')
                                        ->leftjoin('hr_mst_employees as hme', 'hme.id', '=', 'lb.credit_approved_by')
                                        ->leftjoin('mst_payment_methods as mpm', 'mpm.id', '=', 'lb.payment_method_id')
                                        ->leftjoin('mst_referrals as mr', 'mr.id', '=', 'lb.referred_by')
                                        ->where('lb.referred_by', '>', 1)
                                        ->whereRaw($bill_no)
                                        ->whereRaw($date)
                                        ->whereRaw($referals)
                                        ->get();
                    $this->data['columns'] = ['S.N', 'Date', 'Bill No', 'Mode', 'Patient Name', 'Is Paid', 'Referred By', 'Gross Amount', 'Discount Amount', 'Net Amount'];
                    foreach($referral_report as $data){
                        $i++;
                        $output[] = [
                            'S.N'               =>  $i,
                            'Date'              =>  $data->generated_date,
                            'Bill No'           =>  $data->bill_no,
                            'Mode'              =>  $data->payment_mode,
                            'Patient Name'      =>  $data->customer_name,
                            'Is Paid'           =>  $data->is_paid == 1 ? 'TRUE' : 'FALSE',
                            'Referred By'       =>  $data->referred_name,
                            'Gross Amount'      =>  $data->total_gross_amount,
                            'Discount Amount'   =>  $data->total_discount_amount,
                            'Net Amount'        =>  $data->total_net_amount,
                        ];
                        $total = $sum+=$data->total_net_amount;
                    }
                    if(!isset($total)){
                        $total = 0;
                    }
                    $this->data['total']    =   $total;
                    $this->data['output']   =   $output;
                break;

            // discount_report
                case 'discount_report':
                    $sum                =   0;
                    $bill_no            =   '1=1';
                    $date               =   '1=1';
                    $dis_patient_name   =   '1=1';
                    $dis_approver       =   '1=1';

                    if($request->from_date && $request->to_date ){
                        $date =  "lb.generated_date  BETWEEN ' ". $request->from_date  ." ' AND ' ".$request->to_date ."'";
                    }
                    if($request->bill_no){
                        $bill_no = " lb.bill_no iLIKE '%$request->bill_no%' ";
                    }
                    if($request->dis_patient_name){
                        $dis_patient_name = " lb.customer_name iLIKE '%$request->dis_patient_name%' ";
                    }
                    if($request->dis_approver){
                        $dis_approver = "hme.full_name = ". $request->dis_approver;
                    }

                    $discount_report = DB::table('lab_bills as lb')->select('lb.generated_date', 'lb.customer_name', 'lb.is_paid', 'mpm.title as payment_mode lb.total_gross_amount',
                                        'lb.total_discount_amount', 'lb.total_net_amount', 'lb.address', 'lb.bill_no', 'hme.full_name')
                                        ->leftjoin('hr_mst_employees as hme', 'hme.id', '=', 'lb.credit_approved_by')
                                        ->leftjoin('mst_payment_methods as mpm', 'mpm.id', '=', 'lb.payment_method_id')
                                        ->where('lb.total_discount_amount', '>', 0)
                                        ->whereRaw($bill_no)
                                        ->whereRaw($date)
                                        ->whereRaw($dis_patient_name)
                                        ->whereRaw($dis_approver)
                                        ->get();
                    $this->data['columns'] = ['S.N', 'Date', 'Bill No', 'Mode', 'Patient Name', 'Gross Amount', 'Discount Amount', 'Net Amount', 'Is Paid'];
                    foreach($discount_report as $data){
                        $i++;
                        $output[] = [
                            'S.N'               =>  $i,
                            'Date'              =>  $data->generated_date,
                            'Bill No'           =>  $data->bill_no,
                            'Mode'              =>  $data->payment_mode,
                            'Patient Name'      =>  $data->customer_name,
                            'Gross Amount'      =>  $data->total_gross_amount,
                            'Discount Amount'   =>  $data->total_discount_amount,
                            'Net Amount'        =>  $data->total_net_amount,
                            'Is Paid'           =>  $data->is_paid == 1 ? 'TRUE' : 'FALSE',
                        ];
                        $total = $sum+=$data->total_net_amount;
                    }
                    if(!isset($total)){
                        $total  =   0;
                    }
                    $this->data['total']    =   $total;
                    $this->data['output']   =   $output;
                break;

            // cancel_bill_report
                case 'cancel_bill_report':
                    $sum                =   0;
                    $bill_no            =   '1=1';
                    $date               =   '1=1';
                    $patient_name       =   '1=1';
                    $bill_cancel_date   =   '1=1';

                    if($request->from_date && $request->to_date ){
                        $date =  "lb.cancelled_datetime  BETWEEN ' ". $request->from_date  ." ' AND ' ".$request->to_date ."'";
                    }
                    if($request->bill_no){
                        $bill_no = " lb.bill_no iLIKE '%$request->bill_no%' ";
                    }
                    if($request->patient_name){
                        $patient_name = " lb.customer_name iLIKE '%$request->patient_name%' ";
                    }
                    if($request->bill_cancel_date){
                        $bill_cancel_date = "CAST(lb.cancelled_datetime AS DATE) = '". $request->bill_cancel_date."'";
                    }
                    
                    $cancel_bill_report = DB::table('lab_bills as lb')->select('lb.generated_date', 'lb.customer_name', 'lb.is_paid', 'mpm.title as payment_mode',
                                        'lb.total_net_amount', 'lb.address', 'lb.bill_no', 'lb.is_cancelled', 'lb.cancelled_datetime', 'lb.remarks')
                                        ->leftjoin('hr_mst_employees as hme', 'hme.id', '=', 'lb.credit_approved_by')
                                        ->leftjoin('mst_payment_methods as mpm', 'mpm.id', '=', 'lb.payment_method_id')
                                        ->where('lb.is_cancelled', '=', true)
                                        ->whereRaw($bill_no)
                                        ->whereRaw($date)
                                        ->whereRaw($patient_name)
                                        ->whereRaw($bill_cancel_date)
                                            ->get();
                    $this->data['columns'] = ['S.N', 'Date', 'Bill No', 'Patient Name', 'Is Cancelled', 'Cancelled Datetime', 'Remarks', 'Net Amount'];
                    foreach($cancel_bill_report as $data){
                        $i++;
                        $output[] = [
                            'S.N'                => $i,
                            'Date'               =>$data->generated_date,
                            'Bill No'            =>$data->bill_no,
                            'Patient Name'       =>$data->customer_name,
                            'Is Cancelled'       =>$data->is_cancelled == 1 ? 'TRUE' : 'FALSE',
                            'Cancelled Datetime' =>$data->cancelled_datetime,
                            'Remarks'            =>$data->remarks,
                            'Net Amount'         =>$data->total_net_amount,
                        ];
                        $total = $sum+=$data->total_net_amount;
                    }
                    if(!isset($total)){
                        $total  =   0;
                    }
                    $this->data['total']    =   $total;
                    $this->data['output']   =   $output;
                break;

            // department_wise_test_report
                case 'department_wise_test_report':
                    $bill_no        =   '1=1';
                    $date           =   '1=1';
                    $department     =   '1=1';
                    $patient_name   =   '1=1';
                    $item           =   '1=1';

                    if($request->from_date && $request->to_date ){
                        $date =  "lb.generated_date  BETWEEN ' ". $request->from_date  ." ' AND ' ".$request->to_date ."'";
                    }
                    if($request->bill_no){
                        $bill_no = " lb.bill_no iLIKE '%$request->bill_no%' ";
                    }
                    if($request->patient_name){
                        $patient_name = " lb.customer_name iLIKE '%$request->patient_name%' ";
                    }
                    if($request->department){
                        $department =  "lmc.id = ". $request->department;
                    }
                    if($request->item){
                        $item = "lmi.id = ". $request->item;
                    }

                    $department_wise_test_report = DB::table('lab_bills as lb')->select('lb.generated_date', 'lb.customer_name', 
                                        'lb.bill_no', 'lmc.title as category', 'lmi.name as item')
                                        ->leftjoin('hr_mst_employees as hme', 'hme.id', '=', 'lb.credit_approved_by')
                                        ->leftjoin('mst_payment_methods as mpm', 'mpm.id', '=', 'lb.payment_method_id')
                                        ->leftjoin('lab_bill_items as lbi', 'lbi.lab_bill_id', '=', 'lb.id')
                                        ->leftjoin('lab_mst_items as lmi', 'lbi.lab_item_id', '=', 'lmi.id')
                                        ->leftjoin('lab_mst_categories as lmc', 'lmi.lab_category_id', '=', 'lmc.id')
                                        ->where('lmc.id', '!=', null)
                                        ->whereRaw($bill_no)
                                        ->whereRaw($date)
                                        ->whereRaw($patient_name)
                                        ->whereRaw($department)
                                        ->whereRaw($item)
                                        ->get();
                    $this->data['columns'] = ['S.N', 'Date', 'Bill No', 'Patient Name',  'Department', 'Item'];
                    foreach($department_wise_test_report as $data){
                        $i++;
                        $output[] = [
                            'S.N'           =>  $i,
                            'Date'          =>  $data->generated_date,
                            'Bill No'       =>  $data->bill_no,
                            'Patient Name'  =>  $data->customer_name,
                            'Department'    =>  $data->category,
                            'Item'          =>  $data->item,
                        ];
                        $total = $sum+=$data->total_net_amount;
                    }
                    if(!isset($total)){
                        $total  =   0;
                    }
                    $this->data['total']    =   $total;
                    $this->data['output']   =   $output;
                break;

            // test_price_according_to_referral
                case 'test_price_according_to_referral':
                    $sum            =   0;
                    $bill_no        =   '1=1';
                    $date           =   '1=1';
                    $patient_name   =   '1=1';
                    $referred_by    =   '1=1';

                    if($request->from_date && $request->to_date ){
                        $date =  "lb.generated_date  BETWEEN ' ". $request->from_date  ." ' AND ' ".$request->to_date ."'";
                    }
                    if($request->bill_no){
                        $bill_no = " lb.bill_no iLIKE '%$request->bill_no%' ";
                    }
                    if($request->patient_name){
                        $patient_name = "lb.customer_name iLIKE '%$request->patient_name%' ";
                    }
                    if($request->referred_by){
                        $referred_by =  "mr.id = ". $request->referred_by;
                    }

                    $test_price_according_to_referral = DB::table('lab_bills as lb')->select('lb.generated_date','lb.customer_name','mpm.title as payment_mode',
                                        'lb.total_net_amount','lb.bill_no','mr.name as referred_name','mr.contact_person as contact_person','mr.discount_percentage as discount_percentage',
                                        'lb.total_gross_amount','lb.total_net_amount',)
                                        ->leftjoin('hr_mst_employees as hme', 'hme.id' ,'=', 'lb.credit_approved_by')
                                        ->leftjoin('mst_payment_methods as mpm', 'mpm.id' ,'=', 'lb.payment_method_id')
                                        ->leftjoin('mst_referrals as mr', 'mr.id' ,'=', 'lb.referred_by')
                                        ->where('lb.referred_by','>',1)
                                        ->whereRaw($bill_no)
                                        ->whereRaw($patient_name)
                                        ->whereRaw($referred_by)
                                        ->whereRaw($date)
                                        ->get();
                    $this->data['columns'] = ['S.N','Date','Bill No','Patient Name','Referred By','Contact Person','Discount Percentage','Net Amount'];
                    foreach($test_price_according_to_referral as $data){
                        $i++;
                        $output[] = [
                            'S.N'                    => $i,
                            'Date'                   =>$data->generated_date,
                            'Bill No'                =>$data->bill_no,
                            'Patient Name'           =>$data->customer_name,
                            'Referred By'            =>$data->referred_name,
                            'Contact Person'         =>$data->contact_person,
                            'Discount Percentage'    =>$data->discount_percentage,
                            'Net Amount'             =>$data->total_net_amount,

                        ];
                        $total = $sum+=$data->total_net_amount;
                    }
                    if(!isset($total)){
                        $total  =   0;
                    }
                    $this->data['total']    =   $total;
                    $this->data['output']   =   $output;
                break;

            // due_collection_report
                case 'due_collection_report':
                    $sum            =   0;
                    $bill_no        =   '1=1';
                    $date           =   '1=1';
                    $patient_name   =   '1=1';
                    $due_collector  =   '1=1';

                    if($request->from_date && $request->to_date ){
                        $date =  "lb.generated_date  BETWEEN ' ". $request->from_date  ." ' AND ' ".$request->to_date ."'";
                    }
                    if($request->bill_no){
                        $bill_no = " lb.bill_no iLIKE '%$request->bill_no%' ";
                    }
                    if($request->patient_name){
                        $patient_name = " lb.customer_name iLIKE '%$request->patient_name%' ";
                    }
                    if($request->due_collector){
                        $due_collector = "lb.credit_approved_by = '". $request->due_collector ."'";
                    }

                    $due_collection_report = DB::table('lab_bills as lb')->select(DB::raw("SUM(lb.total_net_amount) as net_amount"),"lb.generated_date","lb.customer_name"
                                        ,"lb.address",'lb.bill_no', 'hme.full_name', 'lb.due_received_datetime')
                                        ->leftjoin('hr_mst_employees as hme',  'hme.id', '=', 'lb.credit_approved_by')
                                        ->groupby('lb.generated_date','lb.customer_name','lb.address','lb.bill_no')
                                        ->where('lb.is_paid', true)
                                        ->whereNotNull('lb.due_received_by')
                                        ->whereNotNull('lb.credit_approved_by')
                                        ->whereRaw($bill_no)
                                        ->whereRaw($date)
                                        ->whereRaw($patient_name)
                                        ->whereRaw($due_collector)
                                        ->groupBy('lb.total_net_amount','lb.generated_date','lb.customer_name'
                                        ,'lb.address','lb.bill_no','hme.full_name','lb.due_received_datetime')
                                        ->get();
                    $this->data['columns'] = ['S.N', 'Date', 'Bill No', 'Patient Name', 'Collected DateTime', 'Amount'];
                    foreach($due_collection_report as $data){
                        $i++;
                        $output[] = [
                            'S.N'                   =>  $i,
                            'Date'                  =>  $data->generated_date,
                            'Bill No'               =>  $data->bill_no,
                            'Patient Name'          =>  $data->customer_name,
                            'Collected DateTime'    =>  $data->due_received_datetime,
                            'Amount'                =>  $data->net_amount,
                        ];
                        $total = $sum+=$data->net_amount;
                    }
                    if(!isset($total)){
                        $total  =   0;
                    }
                    $this->data['total']    =   $total;
                    $this->data['output']   =   $output;
                break;

            // collection_report
                // case 'collection_report':
                //     $date = '1=1';
                //     $patient_name = '1=1';
                //     $payment_mode = '1=1';
                //     $sum = 0;
                //     if($request->from_date && $request->to_date ){
                //         $date =  "p.registered_date  BETWEEN ' ". $request->from_date  ." ' AND ' ".$request->to_date ."'";
                //     }
                //     if($request->payment_mode){
                //         $payment_mode =  "payment_method_id = ". $request->payment_mode;
                //     }
                //     if($request->patient_name){
                //         $patient_name = "customer_name iLIKE '%$request->patient_name%' ";
                //     }

                //     $collection_report = DB::table('lab_bills')->select(DB::raw("SUM(total_net_amount) as net_amount"),"generated_date","customer_name","payment_method_id")
                //                         ->groupby('generated_date','customer_name','payment_method_id','customer_name')
                //                         ->whereRaw($date)
                //                         ->whereRaw($patient_name)
                //                         ->whereRaw($payment_mode)
                //                         ->get();
                //     $this->data['columns'] = ['S.N','Date','Patient','Amount'];
                //     foreach($collection_report as $data){
                //         $i++;
                //         $output[] = [
                //             'S.N'    => $i,
                //             'Date'   =>$data->generated_date,
                //             'Patient'   =>$data->customer_name,
                //             'Amount' =>$data->net_amount
                //         ];
                //         $total = $sum+=$data->net_amount;
                //     }
                //     if(!isset($total)){
                //         $total = 0;
                //     }
                //     $this->data['output']   = $output;
                //     $this->data['total']    = $total;
                // break;

            default:
            $this->data['columns'] = [];
            $this->data['output'] = $output;
        }

        if($request->is_print == "true"){
            $html = view('reports.report_print', $this->data)->render();
            $pdf = PdfPrint::printPortrait($html, 'report.pdf');
            return response($pdf);

        }elseif($request->is_excel == "true"){
            $data = $this->data;
            return Excel::download(new ReportExcel($data), 'report.xlsx');

        }else{
            return view('reports.common_report', $this->data);
        }

     
    }
}
