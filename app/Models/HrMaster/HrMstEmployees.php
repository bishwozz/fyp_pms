<?php

namespace App\Models\HrMaster;

use App\Models\Role;
// use App\Base\DataAccessPermission;
use App\Base\BaseModel;
use App\Doctors\Doctors;
use App\Models\AppClient;
use App\Models\CoreMaster\MstGender;
use App\Models\CoreMaster\MstCountry;
use App\Models\CoreMaster\MstFedDistrict;
use App\Models\CoreMaster\MstFedProvince;
use App\Models\HrMaster\HrMstDepartments;
use App\Models\CoreMaster\MstFedLocalLevel;
use App\Models\HrMaster\HrMstSubDepartments;
use App\Models\HrMaster\HrMstEmployeeCategory;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use App\Models\HrMaster\HrMstEmployeePositions;


class HrMstEmployees extends BaseModel
{
    use CrudTrait;

    // public $dataAccessPermission = DataAccessPermission::ShowClientWiseDataOnly;
    public static $salutation_options = [
        1=>'Mr.',
        2=>'Mrs.',
        3=>'Ms.',
        4=>'Master',
        5=>'Dr.',
        6=>'Assisant Prof. Dr.',
        7=>'Associate_Prof. Dr.',
        8=>'Professor Dr.',
    ];

    protected $table = 'hr_mst_employees';
    protected $guarded = ['id','created_by'];
    protected $fillable = ['emp_no','salutation_id','full_name','gender_id','date_of_birth_bs','date_of_birth_ad','qualification','department_id',
    'sub_department_id','is_other_country','province_id','district_id','local_level_id','country_id','address','mobile','email','is_discount_approver',
    'is_credit_approver','is_result_approver','is_active','display_order','ward_no','client_id','updated_by','signature','photo_name','document',
    'role_id','allow_user_login'];


    public function gender()
    {
        return $this->belongsTo(MstGender::class,'gender_id','id');
    }
    
    public function country()
    {
        return $this->belongsTo(MstCountry::class,'country_id','id');
    }

    public function province()
    {
        return $this->belongsTo(MstFedProvince::class,'province_id','id');
    }
    
    public function district()
    {
        return $this->belongsTo(MstFedDistrict::class,'district_id','id');
    }
    
    public function locallevel()
    {
        return $this->belongsTo(MstFedLocalLevel::class,'local_level_id','id');
    }
    
    public function employeeposition()
    {
        return $this->belongsTo(HrMstEmployeePositions::class,'employee_position_id','id');
    }
    
    public function department()
    {
        return $this->belongsTo(HrMstDepartments::class,'department_id','id');
    }

    public function subDepartment()
    {
        return $this->belongsTo(HrMstSubDepartments::class,'sub_department_id','id');
    }

    public function employeecategory()
    {
        return $this->belongsTo(HrMstEmployeeCategory::class,'employee_category_id','id');
    }

    public function isEmployeeDoctor(){
        return $this->hasOne(Doctors::class,'employee_id','id');
    }
    public function roleEntity(){
        return $this->belongsTo(Role::class,'role_id','id');
    }

    // public function province_district()
    // {
    //     if(isset($this->province_id) && isset($this->district_id)){
    //         return $this->province->name_lc.'<br>'.$this->district->name_lc;
    //     }else{
    //         return ' - '.'<br>'.' - ';
    //     }
    // }
    
    // public function local_address()
    // {
    //     if(isset($this->locallevel_id) && isset($this->ward_number)){
    //         return $this->locallevel->name_lc.'<br>'.$this->convertToNepaliNumber($this->ward_number);
    //     }else{
    //         return ' - '.'<br>'.' - ';
    //     }
    // }

    public function full_address()
    {
        if($this->is_other_country == false):
            return $this->locallevel->name_en.'-'.$this->ward_no.'<br>'.$this->district->name_en.' , '.$this->province->name_en;
        endif;    
    
    }
    public function setPhotoNameAttribute($value){
        $attribute_name = "photo_name";
        $disk = "uploads";

        $client_id= backpack_user()->client_id;
        $path  = 'client-###client_id###/employee_photo/';
        $destination_path = str_replace("###client_id###", $client_id, $path);

        if ($value==null) {
            // delete the image from disk
            \Storage::disk($disk)->delete($this->{$attribute_name});

            // set null in the database column
            $this->attributes[$attribute_name] = null;
        }


        if (\Str::startsWith($value, 'data:image'))
        {
            // 0. Make the image
            $image = \Image::make($value)->encode('jpg', 90);
            // 1. Generate a filename.
            $filename = md5($value.time()).'.jpg';
            // 2. Store the image on disk.
            \Storage::disk($disk)->put($destination_path.$filename, $image->stream());
            // 3. Save the public path to the database
        // but first, remove "public/" from the path, since we're pointing to it from the root folder
        // that way, what gets saved in the database is the user-accesible URL
            // $public_destination_path = Str::replaceFirst('public/', '', $destination_path);
            $this->attributes[$attribute_name] = $destination_path.$filename;
        }
        // $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    } 
    public function setSignatureAttribute($value){

        $attribute_name = "signature";
        $disk = "uploads";

        $client_id= backpack_user()->client_id;
        $path  = 'client-###client_id###/signature/';
        $destination_path = str_replace("###client_id###", $client_id, $path);


        if ($value==null) {
            // delete the image from disk
            \Storage::disk($disk)->delete($this->{$attribute_name});

            // set null in the database column
            $this->attributes[$attribute_name] = null;
        }


        if (\Str::startsWith($value, 'data:image'))
        {
            // 0. Make the image
            $image = \Image::make($value)->encode('png', 90);
            // 1. Generate a filename.
            $filename = md5($value.time()).'.png';
            // 2. Store the image on disk.
            \Storage::disk($disk)->put($destination_path.$filename, $image->stream());
            // 3. Save the public path to the database
        // but first, remove "public/" from the path, since we're pointing to it from the root folder
        // that way, what gets saved in the database is the user-accesible URL
            // $public_destination_path = Str::replaceFirst('public/', '', $destination_path);
            $this->attributes[$attribute_name] = $destination_path.$filename;
        }
        // $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    } 
    public function setDocumentAttribute($value){
        $attribute_name = "document";
        $disk = "uploads";

        $client_id= backpack_user()->client_id;
        $path  = 'client-###client_id###/employee_documents/';
        $destination_path = str_replace("###client_id###", $client_id, $path);
        
        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public static function boot()
    {
        parent::boot();
        static::deleted(function ($obj) {
            \Storage::disk('uploads')->delete($obj->photo_name);
        });
    }

}
