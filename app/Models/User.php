<?php

namespace App\Models;

use App\Models\Role;
use App\Models\AppClient;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use App\Models\HrMaster\HrMstEmployees;
use Illuminate\Notifications\Notifiable;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, CrudTrait, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'client_id',
        'employee_id',
        'username',
        'patient_id',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
        //assign role to user

    public function assignRoleCustom($role_name, $model_id){
        $roleModel = Role::where('name', $role_name)->first();
        if(!$roleModel){
            return "role doesnot exists";
        }else{
            DB::table('model_has_roles')->insert([
                'role_id' => $roleModel->id,
                'model_type' => 'App\Models\User',
                'model_id' => $model_id,
            ]);
        }

    }
    public static function getSystemUserId(){
        return AppClient::where('code','sys')->pluck('id')->first();
    }

    public function isSystemUser(){
        if(isset($this->client_id) && $this->clientEntity->code == "sys")
            return true;
        else {
            return false;
        }
    }

    public function isClientUser(){
        if(isset($this->client_id) && $this->clientEntity->code != "sys")
            return true;
        else {
            return false;
        }
    }

    public function clientEntity()
    {
        return $this->belongsTo(AppClient::class,'client_id','id');
    }

    public function employeeEntity() {
        return $this->belongsTo(HrMstEmployees::class,'employee_id','id');
    }
    public function roleName()
    {
        $role = DB::table('model_has_roles as mr')
                    ->select('r.field_name as role_name')
                    ->leftJoin('roles as r','r.id','mr.role_id')
                    ->where('model_id',backpack_user()->id)
                    ->first();
        return $role->role_name;            
    }

    public function routeNotificationForLog ($notifiable) {
        return 'identifier-from-notification-for-log: ' . $this->id;
    }

    public function isOrgUser()
    {
        if(isset($this->client_id)){
            return true;
        }else{
            return false;
        }
    }
}
