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
            ['id' => 4,'name' => 'salesperson', 'field_name' => 'Sales Person', 'guard_name' => 'backpack','created_at'=>$now,'updated_at'=>$now],
        ]);

        DB::statement("SELECT SETVAL('roles_id_seq',10)");

    }
}
