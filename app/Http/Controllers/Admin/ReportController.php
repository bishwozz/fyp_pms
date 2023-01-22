<?php

namespace App\Http\Controllers\Admin;

use App\Models\LabBill;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Base\Helpers\PdfPrint;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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
        // dd($report_type,$request->all());

        switch($report_type){

            case 'purchase_report':
                $gross_amount            =   0;
                $net_amount            =   0;
                $discount            =   0;
                $date           =   '1=1';

                if($request->from_date && $request->to_date ){
                    $date =  "po_date BETWEEN '". $request->from_date  ."' AND '".$request->to_date ."'";
                }

                $purchase_report = DB::table('purchase_order_details')
                                ->select(DB::raw("SUM(net_amt) as net_amt"),'po_date','purchase_order_num' )
                                ->where('status_id', '=', 2)
                                 ->where('client_id','=',2)
                                 ->whereRaw($date)
                                 ->get();
                $this->data['columns'] = ['S.N','Date','Purchase Order Number','Net Amount'];
                foreach($purchase_report as $data){
                    $i++;
                    $output[] = [
                        'S.N'          => $i,
                        'Date'   =>$data->po_date,
                        'Purchase Order Number'         =>$data->purchase_order_num,
                        'Net Amount'         =>$data->net_amt,
                        // 'Test Name'         =>$data->test_name,
                    ];
                    $total = $net_amount+=$data->net_amt;
                    // $discount_amount = $discount+=$data->total_discount_amount;
                    // $gross_amount = $gross_amount+=$data->total_gross_amount;
                }

                if(!isset($total)){
                    $total = 0;
                }
                // if(!isset($discount_amount)){
                //     $discount_amount = 0;
                // }
                // if(!isset($gross_amount)){
                //     $gross_amount = 0;
                // }
                $this->data['total']  = $total;
                // $this->data['discount_amount']  = $discount_amount;
                // $this->data['gross_amount']  = $gross_amount;

                $this->data['output'] = $output;
            break;

            /// Sales Reports
            case 'sales_report':
                $gross_amount            =   0;
                $net_amount            =   0;
                $discount            =   0;
                $date           =   '1=1';

                if($request->from_date && $request->to_date ){
                    $date =  "transaction_date_ad BETWEEN '". $request->from_date  ."' AND '".$request->to_date ."'";
                }

                $sales_report = DB::table('sales')->select(DB::raw("SUM(net_amt) as net_amt"),"transaction_date_ad")
                                    ->groupby('transaction_date_ad','net_amt')
                                    ->where('status_id', '=', 2)
                                    ->where('client_id','=',2)
                                    ->whereRaw($date)
                                    ->get();
                $this->data['columns'] = ['S.N','Date','Amount'];
                foreach($sales_report as $data){
                    $i++;
                    $output[] = [
                        'S.N'          => $i,
                        'Date'   =>$data->transaction_date_ad,
                        'Amount'         =>$data->net_amt
                    ];
                    $total = $net_amount+=$data->net_amt;
                }
                if(!isset($total)){
                    $total = 0;
                }
                $this->data['total']  = $total;
                $this->data['output'] = $output;
            break;

             default:
             $this->data['columns'] = [];
             $this->data['output'] = $output;

        }




        if($request->is_print == "true"){
            $html = view('reports.report_print', $this->data)->render();
            $pdf = PdfPrint::printLandscape($html, 'report.pdf');
            return response($pdf);

        }elseif($request->is_excel == "true"){
            $data = $this->data;
            return Excel::download(new ReportExcel($data), 'report.xlsx');

        }else{
            return view('reports.common_report', $this->data);
        }
     
    }
}
