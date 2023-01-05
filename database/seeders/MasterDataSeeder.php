<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();
        DB::table('sup_status')->insert([
            array('id' => 1, 'code' => '1', 'name_en' => 'created', 'created_at' => $now),
            array('id' => 2, 'code' => '2', 'name_en' => 'approved', 'created_at' => $now),
            array('id' => 3, 'code' => '3', 'name_en' => 'cancelled', 'created_at' => $now),
            array('id' => 4, 'code' => '4', 'name_en' => 'partial_return', 'created_at' => $now),
            array('id' => 5, 'code' => '5', 'name_en' => 'full_return', 'created_at' => $now),
        ]);
    }
}
