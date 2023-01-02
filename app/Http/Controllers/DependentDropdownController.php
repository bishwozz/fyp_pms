<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Modules\Doctors\Entities\Doctors;
use App\Models\Patient;
use App\Models\CoreMaster\MstFedDistrict;
use App\Models\CoreMaster\MstFedProvince;
use App\Models\CoreMaster\MstFedLocalLevel;
use Backpack\CRUD\app\Http\Controllers\BaseController;



class DependentDropdownController extends BaseController
{
   
    public function getdistrict($id)
    {
        $district = MstFedDistrict::where('province_id', $id)->whereRaw("id in (SELECT distinct district_id from mst_fed_local_levels)")->get();
        return response()->json($district);
    }
    public function getlocal_level($id)
    {
        $local_level = MstFedLocalLevel::where('district_id', $id)->get();
        return response()->json($local_level);
    }

    public function getdistrictlocallevel(Request $request)
    {
        $patient_id = $request->patientId;
        $patient = Patient::find($patient_id);
        $district = MstFedDistrict::find($patient->district_id);
        $local_level = MstFedLocalLevel::find($patient->local_level_id);

        return response()->json(['district'=>$district,'local_level'=>$local_level]);
    }

    // public function getDoctor($id)
    // {
    //     $doctor = Doctors::where('doctor_department_id', $id)->get();
    //     return response()->json($doctor);
    // }
  
}
