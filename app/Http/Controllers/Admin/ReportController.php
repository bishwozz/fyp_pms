<?php

namespace App\Http\Controllers\Admin;

use App\Models\LabBill;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function getData($report_type)
    {
        $this->data['report_type'] = trim($report_type, "{}");
        return view('reports.report_filter', $this->data);
    }

    public function getLmsReportData(Request $request)
    {
        $report_type = $request->report_type;
        $this->data['report_name'] = str_replace('_', ' ', ucwords(trim($report_type, "{}"), '_'));
        $output = [];
        $i = 0;

        switch($report_type){
            /// Patient Reports
            case 'patient_report':
                $patient = Patient::with('gender')->get();
                $this->data['columns'] = ['S.N','Patient No','Name','Age/Sex','Visit Date'];
                foreach($patient as $data){
                    $i++;
                    $output[] = [
                        'S.N'          => $i,
                        'Patient No'   =>$data->patient_no,
                        'Name'         =>$data->name,
                        'Age/Sex'       =>$data->age .'/'. $data->gender->name,
                        'Visit Date'    =>$data->registered_date,
                    ];
                }

                $this->data['output'] = $output;
            break;

            case 'covid_test_report':
                $covid_report = DB::table('lab_bills as lb')->select("lb.generated_date",'lb.customer_name','lb.bill_no','lp.name as test_name')
                                ->leftjoin('lab_bill_items as lbi', 'lbi.lab_bill_id' ,'=', 'lb.id')
                                ->leftjoin('lab_panels as lp', 'lp.id' ,'=', 'lbi.lab_panel_id')
                                 ->where('lab_panel_id','=',2)->get();
                $this->data['columns'] = ['S.N','Date','Name','Bill No','Test'];
                foreach($covid_report as $data){
                    $i++;
                    $output[] = [
                        'S.N'          => $i,
                        'Date'   =>$data->generated_date,
                        'Name'         =>$data->customer_name,
                        'Bill No'         =>$data->bill_no,
                        'Test Name'         =>$data->test_name,
                    ];
                }

                $this->data['output'] = $output;
            break;

            /// Cash Reports
            case 'cash_report':
                     $cash_report = DB::table('lab_bills')->select(DB::raw("SUM(total_net_amount) as net_amount"),"generated_date")
                                         ->groupby('generated_date')
                                         ->where('payment_method_id',1)->get();
                    $this->data['columns'] = ['S.N','Date','Amount'];
                    foreach($cash_report as $data){
                        $i++;
                        $output[] = [
                            'S.N'          => $i,
                            'Date'   =>$data->generated_date,
                            'Amount'         =>$data->net_amount
                        ];
                    }
    
                    $this->data['output'] = $output;
            break;

                 /// Overall Collection Details
                 case 'overall_collection_details':
                    $overall_collection_details = DB::table('lab_bills as lb')->select('lb.total_net_amount','lb.generated_date','lb.customer_name','lb.bill_no','mpm.title as payment_mode')
                                   ->leftjoin('mst_payment_methods as mpm', 'mpm.id' ,'=', 'lb.payment_method_id')
                                  ->get();
    
                  $this->data['columns'] = ['S.N','Date','Amount','Name','Bill No','Mode'];
                  foreach($overall_collection_details as $data){
                    $i++;
                    $output[] = [
                        'S.N'          => $i,
                        'Date'   =>$data->generated_date,
                        'Amount'         =>$data->total_net_amount,
                        'Name'         =>$data->customer_name,
                        'Bill No'         =>$data->bill_no,
                        'Mode'         =>$data->payment_mode,
                    ];
                 }
    
                   $this->data['output'] = $output;
                 break;
    

            /// Credit Reports
            case 'credit_report':
                $credit_report = DB::table('lab_bills as lb')->select(DB::raw("SUM(lb.total_net_amount) as net_amount"),"lb.generated_date","lb.customer_name"
                                    ,"lb.address",'lb.bill_no')
                                    ->leftjoin('hr_mst_employees as hme', 'hme.id' ,'=', 'lb.credit_approved_by')
                                    ->groupby('lb.generated_date','lb.customer_name','lb.address','lb.bill_no')
                                    ->where([['lb.payment_method_id',6],['lb.is_paid', false]])
                                    ->get();
               $this->data['columns'] = ['S.N','Date','Bill No','Amount','Patient Name','Address'];
               foreach($credit_report as $data){
                   $i++;
                   $output[] = [
                       'S.N'          => $i,
                       'Date'   =>$data->generated_date,
                       'Bill No'         =>$data->bill_no,
                       'Amount'         =>$data->net_amount,
                       'Patient Name'         =>$data->customer_name,
                       'Address'         =>$data->address
                   ];
               }

               $this->data['output'] = $output;
             break;

               /// Bill Reports
            case 'bill_report':
                $bill_report = DB::table('lab_bills as lb')->select('lb.generated_date','lb.customer_name','lb.is_paid','mpm.title as payment_mode','lb.total_gross_amount',
                                    'lb.total_discount_amount','lb.total_net_amount','lb.address','lb.bill_no')
                                    ->leftjoin('hr_mst_employees as hme', 'hme.id' ,'=', 'lb.credit_approved_by')
                                    ->leftjoin('mst_payment_methods as mpm', 'mpm.id' ,'=', 'lb.payment_method_id')
                                    ->get();
               $this->data['columns'] = ['S.N','Date','Bill No','Mode','Patient Name','Gross Amount','Discount Amount','Net Amount','Is Paid'];
               foreach($bill_report as $data){
                   $i++;
                   $output[] = [
                       'S.N'          => $i,
                       'Date'   =>$data->generated_date,
                       'Bill No'         =>$data->bill_no,
                       'Mode'         =>$data->payment_mode,
                       'Patient Name'         =>$data->customer_name,
                       'Gross Amount'         =>$data->total_gross_amount,
                       'Discount Amount'         =>$data->total_discount_amount,
                       'Net Amount'         =>$data->total_net_amount,
                       'Is Paid'         =>$data->is_paid == 1 ? 'TRUE' : 'FALSE',
                   ];
               }

               $this->data['output'] = $output;
             break;

                /// Bill Reports By Referral
            case 'referral_report':
                $referral_report = DB::table('lab_bills as lb')->select('lb.generated_date','lb.customer_name','lb.is_paid','mpm.title as payment_mode','lb.total_gross_amount',
                                    'lb.total_discount_amount','lb.total_net_amount','lb.address','lb.bill_no','mr.name as referred_name')
                                    ->leftjoin('hr_mst_employees as hme', 'hme.id' ,'=', 'lb.credit_approved_by')
                                    ->leftjoin('mst_payment_methods as mpm', 'mpm.id' ,'=', 'lb.payment_method_id')
                                    ->leftjoin('mst_referrals as mr', 'mr.id' ,'=', 'lb.referred_by')
                                    ->where('lb.referred_by','>',1)
                                    ->get();
               $this->data['columns'] = ['S.N','Date','Bill No','Mode','Patient Name','Gross Amount','Discount Amount','Net Amount','Is Paid','Referred By'];
               foreach($referral_report as $data){
                   $i++;
                   $output[] = [
                       'S.N'          => $i,
                       'Date'   =>$data->generated_date,
                       'Bill No'         =>$data->bill_no,
                       'Mode'         =>$data->payment_mode,
                       'Patient Name'         =>$data->customer_name,
                       'Gross Amount'         =>$data->total_gross_amount,
                       'Discount Amount'         =>$data->total_discount_amount,
                       'Net Amount'         =>$data->total_net_amount,
                       'Is Paid'         =>$data->is_paid == 1 ? 'TRUE' : 'FALSE',
                       'Referred By'         =>$data->referred_name,
                   ];
               }

               $this->data['output'] = $output;
             break;

             

             default:
             $this->data['columns'] = [];
             $this->data['output'] = $output;

        }




        return view('reports.common_report', $this->data);
     
    }
}
