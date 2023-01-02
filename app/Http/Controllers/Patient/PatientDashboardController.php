<?php

namespace App\Http\Controllers\Patient;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Lab\LabPatientTestData;

class PatientDashboardController extends Controller
{
    public function index(){
        $user = backpack_user();
        $patient = Patient::find($user->patient_id);
        if(!$patient){
            return abort(404);
        }
        $data = [
            'patient' => $patient,
        ];
        return view('patient_end.dashboard',$data);
    }
    public function getReportList(Request $request){
        $reports = DB::table('lab_patient_test_data as lptd')
                        ->select('lptd.id','lmc.title as category','d.full_name as doctor','lt.full_name as lab_technician','lptd.order_no','lptd.reported_datetime',
                        'mr.name as referral','lptd.approved_datetime')
                        ->leftJoin('lab_mst_categories as lmc','lmc.id','lptd.category_id')
                        ->leftJoin('hr_mst_employees as lt','lt.id','lptd.lab_technician_id')
                        ->leftJoin('hr_mst_employees as d','d.id','lptd.doctor_id')
                        ->leftJoin('lab_bills as lb','lb.id','lptd.bill_id')
                        ->leftJoin('mst_referrals as mr','mr.id','lb.referred_by')
                        ->where('lptd.patient_id',$request->patient_id)->where('lptd.dispatch_status',1);
        // if($request->search){
        //     $reports = $reports->orWhere('d.full_name','ilike','%'.$request->search.'%');
        //     $reports = $reports->orWhere('lt.full_name','ilike','%'.$request->search.'%');
        //     $reports = $reports->orWhere('lptd.order_no','ilike','%'.$request->search.'%');
        //     $reports = $reports->orWhere('mr.name','ilike','%'.$request->search.'%');
        // }
        $reports=$reports->get();
        return response(view('patient_end.inc.report',['reports'=>$reports])->render());
    }
}
