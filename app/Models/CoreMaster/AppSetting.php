<?php

namespace App\Models\CoreMaster;

use App\Base\BaseModel;
use App\Models\AppClient;
use Illuminate\Support\Str;
use App\Models\CoreMaster\MstFiscalYear;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Intervention\Image\ImageManagerStatic as Image;

class AppSetting extends BaseModel
{
    use CrudTrait;

    protected $table = 'app_settings';
    protected $guarded = ['id','created_at','updated_at'];
    protected $fillable = ['code','client_id','office_name','address_name','phone','fax','email','registration_number','pan_vat_no',
                            'letter_head_title_1','letter_head_title_2','letter_head_title_3','letter_head_title_4',
                            'client_logo','client_stamp','remarks','is_active','fiscal_year_id',
                        'purchase_order_seq_key','bill_seq_key','order_seq_key','sample_seq_key'];

    public function client(){
        return $this->belongsTo(AppClient::class,'client_id','id');
    }

    public function fiscalYear(){
        return $this->belongsTo(MstFiscalYear::class,'fiscal_year_id','id');
    }
    
    public function setClientLogoAttribute($value)
    {
        $attribute_name = "client_logo";
        $disk = "uploads";
        $user = backpack_user();
        if($user->isClientUser()){
            $client_id = $user->client_id;
        }else{
            $client_id = $this->client_id;
        }
        $destination_path  = 'ClientLogo/client-'.$client_id.'/';
        // $destination_path = str_replace("###Employee_ID###", $employee_id, $path);
        // $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);  
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
    }
    public function setClientStampAttribute($value)
    {
        $attribute_name = "client_stamp";
        $disk = "uploads";

        $user = backpack_user();
        if($user->isClientUser()){
            $client_id = $user->client_id;
        }else{
            $client_id = $this->client_id;
        }

        $destination_path  = 'ClientStamp/client-'.$client_id.'/';
        // $destination_path = str_replace("###Employee_ID###", $employee_id, $path);
        // $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);  
        if ($value==null) {
            // delete the image from disk
            \Storage::disk($disk)->delete($this->{$attribute_name});
    
            // set null in the database column
            $this->attributes[$attribute_name] = null;
        }
    
    
        if (Str::startsWith($value, 'data:image'))
        {
            // 0. Make the image
            $image = Image::make($value)->encode('png', 90);
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
    }

    public static function boot()
    {
        parent::boot();
        static::deleted(function ($obj) {
            Storage::disk('uploads')->delete($obj->photo_path);
        });
    }
}
