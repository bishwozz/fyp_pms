<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        DB::table('roles')->insert([
            ['id' => 1,'name' => 'superadmin', 'field_name' => 'Super Admin', 'guard_name' => 'backpack','created_at'=>$now,'updated_at'=>$now],
            ['id' => 2,'name' => 'clientadmin', 'field_name' => 'Client Admin', 'guard_name' => 'backpack','created_at'=>$now,'updated_at'=>$now],
            ['id' => 3,'name' => 'admin', 'field_name' => 'Admin', 'guard_name' => 'backpack','created_at'=>$now,'updated_at'=>$now],
            ['id' => 4,'name' => 'reception', 'field_name' => 'Reception', 'guard_name' => 'backpack','created_at'=>$now,'updated_at'=>$now],
            ['id' => 5,'name' => 'doctor', 'field_name' => 'Doctor', 'guard_name' => 'backpack','created_at'=>$now,'updated_at'=>$now],
            ['id' => 6,'name' => 'lab_admin', 'field_name' => 'Lab Administration', 'guard_name' => 'backpack','created_at'=>$now,'updated_at'=>$now],
            ['id' => 7,'name' => 'lab_technician', 'field_name' => 'Lab Technician', 'guard_name' => 'backpack','created_at'=>$now,'updated_at'=>$now],
            ['id' => 8,'name' => 'lab_technologist', 'field_name' => 'Lab Technologist', 'guard_name' => 'backpack','created_at'=>$now,'updated_at'=>$now],
            ['id' => 9,'name' => 'referral', 'field_name' => 'Referral', 'guard_name' => 'backpack','created_at'=>$now,'updated_at'=>$now],
            ['id' => 10,'name' => 'patient', 'field_name' => 'Patient', 'guard_name' => 'backpack','created_at'=>$now,'updated_at'=>$now],
            ['id' => 11,'name' => 'finance', 'field_name' => 'Finance/Account', 'guard_name' => 'backpack','created_at'=>$now,'updated_at'=>$now],
        ]);

        DB::statement("SELECT SETVAL('roles_id_seq',10)");

    }
}
