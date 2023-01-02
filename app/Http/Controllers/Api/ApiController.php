<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\PatientAppointment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Notifications\NewAppointment;
use App\Models\HrMaster\HrMstEmployees;

class ApiController extends Controller
{
    public function getEmployeeList()
    {
        $file_path = url('/').'/storage/uploads/';

        $salutation_options = HrMstEmployees::$salutation_options;

        $to_return_array['success']=true;
        $dt=DB::table('hr_mst_employees as he')
                ->join('hr_mst_departments as hd','he.department_id','hd.id')
                ->join('hr_mst_sub_departments as hsd','he.sub_department_id','hsd.id')
                ->select('he.id','he.salutation_id as salutation',DB::raw("concat('$file_path',he.photo_name) as image_path"),'he.full_name',
                    'hd.title as department','hsd.title as sub_department','he.qualification',)
                ->where('he.is_active',true)
                ->orderby('he.display_order')
                ->get();
        $datas =[];
        foreach($dt as $d){
            $d->full_name = $salutation_options[$d->salutation].' '.$d->full_name;
            $datas[$d->department][]=$d;
        }
        
        $to_return_array['message']='Records Listed Successfully';
        $to_return_array['total']=count($dt);
        $to_return_array['data']=$datas;
        return response()->json($to_return_array);
    }

    public function saveAppointment(Request $request)
    {
        $to_return_array['success']=false;
        $to_return_array['message']='Error occured. Please contact admin !';
        
        $data=[
            'client_id'=>2,
            'full_name'=>$request['name'],
            'gender_id'=>$request['gender'],
            'age'=>$request['age'],
            'city'=>$request['address'],
            'cell_phone'=>$request['phone'],
            'email'=>$request['email'],
            'remarks'=>$request['remarks'],
            'appointment_date'=>$request['appointment_date'],
            'appointment_date_bs'=>convert_bs_from_ad($request['appointment_date']),
            'appointment_status'=>0,
            'created_at'=>dateTimeNow()
        ];

        $create = DB::table('patient_appointments')->insertGetId($data);

        // dd($create);

        if($create)
        {
            // send notification
                // $notification = PatientAppointment::where('client_id',2)->where('full_name',$request['name'])
                // ->where('email',$request['email'])
                // ->first();
            $this->sendNotification($create);

            $to_return_array['success']=true;
            $to_return_array['message']='Patient Appointment Record added successfully !';
        }
        return response()->json($to_return_array);

    }
    public function sendNotification($id){

        $details = [
            'appointment_id' => $id
        ];

        $user = PatientAppointment::find($id);
        $user->notify(new NewAppointment($details));
    }
}
