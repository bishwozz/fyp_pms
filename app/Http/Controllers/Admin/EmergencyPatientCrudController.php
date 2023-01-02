<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Patient;
use App\Models\SalesBill;
use Illuminate\Http\Request;
use App\Models\MstBloodGroup;
use App\Base\BaseCrudController;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use App\Models\CoreMaster\MstGender;
use App\Http\Requests\PatientRequest;
use App\Models\CoreMaster\MstFedDistrict;
use App\Models\CoreMaster\MstFedProvince;
use Illuminate\Support\Facades\File;

/**
 * Class EmergencyPatientCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EmergencyPatientCrudController extends BaseCrudController
{
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    protected $user = NULL;
    protected $now = NULL;
    public function setup()
    {
        $this->user = backpack_user();
        $this->crud->setModel(Patient::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/emergency-patient');
        $this->crud->setEntityNameStrings('Emergency Registration', 'Emergency Registrations');
        $this->checkPermission();
        $this->now=Carbon::now()->todatetimestring();
    }


    public function getAllPatients(){
        $data['items'] = Patient::where('client_id',$this->user->client_id)->where('is_emergency',true)->get();

        return view('patient_registration.emergency-patient-lists',$data);
    }

    // For Custom Search
    public function searchPatient(Request $request) {
        $patient_no = $request->patient_no;
        $patient_name = $request->patient_name;
        $bill_no    = $request->bill_no;
        $flag    = $request->flag;

        $client_id = backpack_user()->client_id;

        if(!empty($bill_no)) {
            $sales_bill = SalesBill::where('client_id',$client_id);
            $patient = $sales_bill->where('bill_no', "ilike", "%$bill_no%");
            $items = $patient->get();
            return view('billing::billing.new_customer_billing_filter', compact('items'));
        }
        else {
            $patient = Patient::where('client_id', $client_id)->where('is_emergency', true);
        }
        
        if(!empty($patient_no)) {
            $patient = $patient->where('patient_no', "ilike", "%$patient_no%");
        }

        if(!empty($patient_name)) {
            $patient = $patient->where('name', "ilike", "%$patient_name%");
        }

        $patient = $patient->orderBy('created_at','DESC');

        $data['items'] = $patient->get();

        if($flag === 'all_patients'){
            return view('patient_registration.all_patient_list_filter',$data);
        }
        if($flag === 'patient_billing') {
            return view('billing.patient_billing_list_filter', $data);
        }

        if($flag === 'patient_pharmacy'){
            return view('pharmacy.phr_search_filter', $data);
        }
    }

    public function create()
    {
        $this->getData();
        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.add').' '.$this->crud->entity_name;
        return view('patient_registration.emergency_patient_registration', $this->data);
    }

    public function edit($id)
    {
        $this->getData();
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['patient'] = Patient::find($id);
        return view('patient_registration.emergency_patient_registration',  $this->data);
    }

    private function getData()
    {
        $genders = MstGender::all();
        $fed_district = MstFedDistrict::all();
        $province = MstFedProvince::all();
        $blood_groups = MstBloodGroup::all();
        $patient_types = Patient::$patient_types;
        $id_types = Patient::$id_types;
        $marital_status = Patient::$marital_status;
        $salutation_ids = Patient::$salutation_ids;
        $age_units = Patient::$age_units;
        $is_referred = Patient::$is_referred;

        $this->data['genders'] = $genders;
        $this->data['fed_district'] = $fed_district;
        $this->data['province'] = $province;
        $this->data['blood_groups'] = $blood_groups;
        $this->data['patient_types'] = $patient_types;
        $this->data['marital_status'] = $marital_status;
        $this->data['salutation_ids'] = $salutation_ids;
        $this->data['id_types'] = $id_types;
        $this->data['age_units'] = $age_units;
        $this->data['is_referred'] = $is_referred;
        return $this->data;
    }

    public function store()
    {
        $this->crud->hasAccessOrFail('create');
        // $user = backpack_user();
        
        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        $date_bs = convert_bs_from_ad();
        $date_ad = Carbon::now()->toDateString();
        $webcam_image = $request->image;

        
        if ($request->has('patient_no')) {
            $prefix_key = $this->user->clientEntity->prefix_key;
            $query = $this->crud->model->where('client_id',$this->user->client_id)->latest('created_at')->pluck('patient_no')->first();
            $patient_no = $prefix_key.'-P-1';
            if ($query != null) {
                $explode = explode('-',$query);
                $num = end($explode);
                $patient_no = $prefix_key.'-P-'.(intval($num) + 1);
                
            }
        }
        $has_insurance = !empty($request->has_insurance);
        $is_ipd = !empty($request->is_ipd);
        $is_referred = !empty($request->is_referred);


        $is_ipd_patient = $is_ipd === true ? true : false;
        if($request->hasFile('photo_name')){
            //Get name of file with extension
            $fileNameWithExt = $request->file('photo_name')->getClientOriginalName();
            //Get just file name
            // $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            //Get just ext
            $extension = $request->file('photo_name')->getClientOriginalExtension();
            //Filename to store
            $fileName = $request->name.'_'.time().'.'.$extension;
            //Upload image
            $fileNameToStore = 'uploads/patient/profile/'.$fileName;
        }else{
            if($webcam_image != null){
                    $disk = "uploads";
                    $destination_path = '/patient/profile/';
                    $image = Image::make($webcam_image)->encode('jpg', 90);
                    $filename = $request->name.'_'.time().'.'.'png';
                    $fileNameToStore = 'uploads/patient/profile/'.$filename;

            }else{
               $fileNameToStore = $request->photo_name;
            }
        }
        $data = [
            'client_id'     => backpack_user()->client_id,
            'patient_no'    => $patient_no,
            'photo_name'    => $fileNameToStore,
            'name'          => $request->name,
            'gender_id'     => $request->gender_id,
            'date_of_birth' => $request->date_of_birth,
            'date_of_birth_bs' => $request->date_of_birth_bs,
            'street_address' => $request->street_address,
            'age'           => $request->age,
            'age_unit'   => $request->age_unit,
            'cell_phone'    => $request->cell_phone,
            'created_by'    => $this->user->id,
            'created_at'    => $this->now,
            'updated_at'    => $this->now,
            'registered_date' => $date_ad,
            'registered_date_bs' => $date_bs,
            'is_referred' => $is_referred,
            'hospital_id'=> $is_referred === 1? $request->hospital_id : null,
            'referrer_doctor_name'=> $is_referred === 1? $request->referrer_doctor_name : null,
            'patient_type'   => $request->patient_type,
            'national_id_no'   => $request->national_id_no,
            'passport_no'   => $request->passport_no,
            'salutation_id'   => $request->salutation_id,
            'marital_status'   => $request->marital_status,
            'is_emergency' =>true,
        ];
        DB::beginTransaction();
        try {
            $patient  = Patient::create($data);
            DB::commit();
            if($request->hasFile('photo_name')){
                $path = $request->file('photo_name')->storeAs('public/uploads/patient/profile/',$fileName);
            }
            if($webcam_image != null){
                \Storage::disk($disk)->put($destination_path.$filename, $image->stream());
            }
            $url = backpack_url('emergency-patient');
            // show a success message
            Alert::success(trans('backpack::crud.insert_success'))->flash();
            return response()->json([
                'status' => true,
                'url' => $url,
                'message' => trans('backpack::crud.insert_success')
            ]);

        } catch (\Throwable $th) {
            DB::rollback();
            // show a success message
            Alert::success($th->getMessage())->flash();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 404);
        }
    }

    public function update() {
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();
        $request->request->set('updated_by', $this->user->id);
        
        $patientId = $request->patient_id;
        $has_insurance = !empty($request->has_insurance);
        $is_referred = !empty($request->is_referred);
        $patient=Patient::where('id', $patientId)->first();
        $webcam_image = $request->image;

        if($request->hasFile('photo_name')){
            //Get name of file with extension
            $fileNameWithExt = $request->file('photo_name')->getClientOriginalName();
            //Get just file name
            // $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            //Get just ext
            $extension = $request->file('photo_name')->getClientOriginalExtension();
            //Filename to store
            $fileName = $request->name.'_'.time().'.'.$extension;
            //Upload image
            $fileNameToStore = 'uploads/patient/profile/'.$fileName;
        }else{
            if($webcam_image != null){
                    $disk = "uploads";
                    $destination_path = '/patient/profile/';
                    $image = Image::make($webcam_image)->encode('jpg', 90);
                    $filename = $request->name.'_'.time().'.'.'png';
                    $fileNameToStore = 'uploads/patient/profile/'.$filename;

            }else{
               $fileNameToStore = $request->photo_name;
            }
        }

        $data = [
            'name'              => $request->name,
            'gender_id'         => $request->gender_id,
            'photo_name'    => $fileNameToStore,
            'date_of_birth'     => $request->date_of_birth,
            'date_of_birth_bs'  => $request->date_of_birth_bs,
            'province_id'       => $request->province_id,
            'district_id'       => $request->district_id,
            'local_level_id'    => $request->local_level_id,
            'street_address'    => $request->street_address,
            'cell_phone'        => $request->cell_phone,
            'email'             => $request->email,
            'ward_no'           => $request->ward_no,
            'age'               => $request->age,
            'has_insurance'     => $has_insurance,
            'updated_by'        => $request->updated_by,
            'updated_at'        => Carbon::now()->todatetimestring(),
            'is_referred' => $is_referred,
            'hospital_id'=> $is_referred === 1? $request->hospital_id : null,
            'referrer_doctor_name'=> $is_referred === 1? $request->referrer_doctor_name : null,
            'patient_type'   => $request->patient_type,
            'citizenship_no'   => $request->citizenship_no,
            'voter_no'   => $request->voter_no,
            'national_id_no'   => $request->national_id_no,
            'nationality'   => $request->nationality,
            'passport_no'   => $request->passport_no,
            'blood_group_id'   => $request->blood_group_id,
        ];
        DB::beginTransaction();
        try {
            $patient->update($data);
            DB::commit();
            if($request->hasFile('photo_name')){
                $path = $request->file('photo_name')->storeAs('public/uploads/patient/profile/',$fileName);
            }
            if($webcam_image != null){
                \Storage::disk($disk)->put($destination_path.$filename, $image->stream());
            }
            Alert::success(trans('backpack::crud.update_success'))->flash();
            $redirectTo = backpack_url('emergency-patient');
            return response()->json([
                'status' => true,
                'url' => $redirectTo,
                'message' => trans('backpack::crud.insert_success')
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            Alert::error($th->getMessage())->flash();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 404);
        }

        //return redirect(backpack_url('/patient/patients/'.$patientId.'/make_appointment'));
    }
}
