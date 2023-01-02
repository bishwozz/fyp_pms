<?php

namespace App\Http\Controllers\Admin;

use Image;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\LabBill;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\MstBloodGroup;
use App\Base\BaseCrudController;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use App\Models\CoreMaster\MstGender;
use Illuminate\Support\Facades\File;
use App\Models\Billing\PatientBilling;
use App\Models\HrMaster\HrMstEmployees;
use Illuminate\Support\Facades\Storage;
use App\Models\CoreMaster\MstFedDistrict;
use App\Models\CoreMaster\MstFedProvince;

/**
 * Class PatientCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PatientCrudController extends BaseCrudController
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
        $this->crud->setModel(\App\Models\Patient::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/patient');
        $this->crud->setEntityNameStrings('patient', 'patients');
        $this->checkPermission(['getAllPatients'=>'getAllPatients',
                                'searchPatient'=>'searchPatient',
                                'listAllPatients'=>'list',
                                'getPatientInfo'=>'list'
        ]);
        $this->now=Carbon::now()->todatetimestring();
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    public function getAllPatients(){
        $data['items'] = Patient::where('client_id',$this->user->client_id)->where('is_emergency',false)->orderByDesc('created_at')->get();

        return view('patient_registration.patient-lists',$data);
    }
    
    //return all patients with certain info
    public function listAllPatients(Request $request)
    {
        $qs = $request->qs;
        $patients='';
        if(!empty($qs)){
            $patients = Patient::select('id','name','patient_no','cell_phone')
                                ->where('name','iLike',"%$qs%")
                                ->orWhere('patient_no','iLike',"%$qs%")
                                ->orWhere('cell_phone','iLike',"%$qs%")
                                ->get();

            return response()->json(['status'=>'success','patients'=>$patients]);                    
        }else{
            return response()->json(['status'=>'fail']);                    
        };

    }

    
    public function create()
    {
        $this->getData();
        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.add').' '.$this->crud->entity_name;
        return view('patient_registration.patient_registration', $this->data);
    }

    public function edit($id)
    {
        $this->getData();
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['patient'] = Patient::find($id);
        // dd($this->data);
        return view('patient_registration.patient_registration',  $this->data);
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
            $prefix_key = appSetting()->patient_seq_key;
            $query = $this->crud->model->where('client_id',$this->user->client_id)->latest('created_at')->pluck('patient_no')->first();
            $patient_no = $prefix_key.'1';
            if ($query != null) {
                $explode = explode('-',$query);
                $num = end($explode);
                $patient_no = $prefix_key.(intval($num) + 1);
                
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
            'province_id'   => $request->province_id,
            'district_id'   => $request->district_id,
            'local_level_id' => $request->local_level_id,
            'street_address' => $request->street_address,
            'ward_no'       => $request->ward_no,
            'age'           => $request->age,
            'cell_phone'    => $request->cell_phone,
            'email'         => $request->email,
            'has_insurance' => $has_insurance,
            'created_by'    => $this->user->id,
            'created_at'    => $this->now,
            'updated_at'    => $this->now,
            'registered_date' => $date_ad,
            'registered_date_bs' => $date_bs,
            // 'is_referred' => $is_referred,
            // 'hospital_id'=> $is_referred === 1? $request->hospital_id : null,
            // 'referrer_doctor_name'=> $is_referred === 1? $request->referrer_doctor_name : null,
            'patient_type'   => $request->patient_type,
            'citizenship_no'   => $request->citizenship_no,
            'voter_no'   => $request->voter_no,
            'national_id_no'   => $request->national_id_no,
            'nationality'   => $request->nationality,
            'passport_no'   => $request->passport_no,
            'blood_group_id'   => $request->blood_group_id,
            'age_unit'   => $request->age_unit,
            'id_type'   => $request->id_type,
            'salutation_id'   => $request->salutation_id,
            'marital_status'   => $request->marital_status,
        ];
        DB::beginTransaction();
        try {
            $patient  = Patient::create($data);
            // $name_to_email = str_replace(' ','_',mb_strtolower($request->name));
            // $email = $name_to_email.'@gmail.com';
            // $emailCheck = User::where('email',$email)->get();
            // if(count($emailCheck)){
            //     $latestEmail = User::where('email','like',$name_to_email.'%')->max('email');
            //     $last_part = substr($latestEmail,strlen($name_to_email),strlen($latestEmail));
            //     $email_count = intval(substr($last_part,0, strlen($last_part) - 10))+1;
            //     $email = $name_to_email.$email_count.'@gmail.com';
            // }
            $user = User::updateOrCreate(
                    ['patient_id'=>$patient->id],
                    [
                        'client_id' => backpack_user()->client_id,
                        'name' => $request->name,
                        'username' => $patient->patient_no,
                        'email' => isset($request->email) ?$request->email : '',
                        'password' => bcrypt('P@t'.rand(10000,99999)),
                    ]
                );
                $patient_role = Role::where('name','patient')->first();
                if($patient_role){
                    //insert or update role after user creation
                    DB::table('model_has_roles')->updateOrInsert([
                        'role_id' => $patient_role->id,
                        'model_type' => 'App\Models\User',
                        'model_id' => $user->id,
                    ]);
                }
            DB::commit();
            if($request->hasFile('photo_name')){
                $path = $request->file('photo_name')->storeAs('public/uploads/patient/profile/',$fileName);
            }
            if($webcam_image != null){
                \Storage::disk($disk)->put($destination_path.$filename, $image->stream());
            }
            $url = backpack_url('/patient');
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
            $redirectTo = backpack_url('/patient');
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
