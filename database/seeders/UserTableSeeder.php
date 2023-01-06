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
                'name' => 'Pharmacy Lab Admin',
                'email' => 'pharmacy@gmail.com',
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
        $phar_admin_role = Role::find(2);
        $admin = Role::find(3);
        $salesperson = Role::find(4);
       

        $super_admin_role->givePermissionTo($permissions);
        $phar_admin_role->givePermissionTo($permissions);
        $admin->givePermissionTo($permissions);

        // $salesperson->givePermissionTo();


        //assign role for superadmin
        $user = User::findOrFail(1);
        $phar_user = User::findOrFail(2);

        $user->assignRoleCustom("superadmin", $user->id);
        $phar_user->assignRoleCustom("clientadmin", $phar_user->id);
    }
}
