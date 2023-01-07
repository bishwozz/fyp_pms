<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->clean_tables();
       
        // $this->payment_methods();
    }

    private function clean_tables(){
        DB::table('mst_payment_methods')->delete();
    }

    private function payment_methods(){
        DB::table('mst_payment_methods')->insert([
            array('id' => 1,'code'=>'cash', 'title' => 'Cash & Direct'),
            array('id' => 2,'code'=>'fonepay', 'title' => 'FonePay'),
            array('id' => 3,'code'=>'card', 'title' => 'Card Payment'),
            array('id' => 4,'code'=>'esewa', 'title' => 'ESewa'),
            array('id' => 5,'code'=>'khalti', 'title' => 'Khalti'),
            array('id' => 6,'code'=>'credit', 'title' => 'Credit'),
            array('id' => 7,'code'=>'other', 'title' => 'Other')
        ]);
    }
}
