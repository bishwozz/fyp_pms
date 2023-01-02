<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        $client_id = DB::table('app_clients')->where('code', 'sys')->pluck('id')->first();
        $bidh_client_id = DB::table('app_clients')->where('code', 'bidh_lab')->pluck('id')->first();

        DB::table('users')->insert([
            array(
                'id' => 1,
                'client_id' => $client_id,
                'name' => 'System Admin',
                'email' => 'super_admin@gmail.com',
                'password' => bcrypt('Super@5678'),
                'created_at'=>$now,
                'updated_at'=>$now,
            ),
            array(
                'id' => 2,
                'client_id' => $bidh_client_id,
                'name' => 'Bidh Lab Admin',
                'email' => 'bidh@gmail.com',
                'password' => bcrypt('Admin@1234'),
                'created_at'=>$now,
                'updated_at'=>$now,
            )
        ]);

        DB::statement("SELECT SETVAL('users_id_seq',2)");


        //call artisan commands
        Artisan::call('generate:permissions');
        // Artisan::call('disable:backpack_pro');

        $permissions = Permission::all();
        $super_admin_role = Role::find(1);
        $bidh_admin_role = Role::find(2);
        $admin = Role::find(3);
        $reception = Role::find(4);
        $doctor = Role::find(5);
        $lab_admin = Role::find(6);
        $lab_technician = Role::find(7);
        $lab_technologist = Role::find(8);

        $super_admin_role->givePermissionTo($permissions);
        $bidh_admin_role->givePermissionTo($permissions);
        $admin->givePermissionTo($permissions);

        $reception->givePermissionTo(
            'list patient','create patient','update patient',
            'list patientappointment','create patientappointment','update patientappointment',
            'list patientbilling','create patientbilling','update patientbilling',
            'list labpatienttestdata','create labpatienttestdata','update labpatienttestdata',
        );
        $doctor->givePermissionTo(
            'list labpatienttestdata','create labpatienttestdata','update labpatienttestdata',
            'list labpatienttestresult','create labpatienttestresult','update labpatienttestresult',
        );
        $lab_admin->givePermissionTo(
            'list mstbank','create mstbank','update mstbank',
            'list patient','create patient','update patient',
            'list patientappointment','create patientappointment','update patientappointment',
            'list referral','create referral','update referral',
            'list user','create user','update user',
            'list patientbilling','create patientbilling','update patientbilling',
            'list hrmstdepartments','create hrmstdepartments','update hrmstdepartments',
            'list hrmstemployees','create hrmstemployees','update hrmstemployees',
            'list hrmstsubdepartments','create hrmstsubdepartments','update hrmstsubdepartments',
            'list labpanel','create labpanel','update labpanel',
            'list labpatienttestdata','create labpatienttestdata','update labpatienttestdata',
            'list labpatienttestresult','create labpatienttestresult','update labpatienttestresult',

        );
        $lab_technician->givePermissionTo(
            'list labpatienttestdata','create labpatienttestdata',

        );
        $lab_technologist->givePermissionTo(
            'list labpatienttestdata','create labpatienttestdata',
        );

        //assign role for superadmin
        $user = User::findOrFail(1);
        $bidh_user = User::findOrFail(2);

        $user->assignRoleCustom("superadmin", $user->id);
        $bidh_user->assignRoleCustom("clientadmin", $bidh_user->id);
    }
}
