<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->clean_tables();
        // $this->phr_mst_unit();
        // $this->phr_mst_categories();
        $this->mst_discount_modes();
        // $this->sup_status();

    }

    private function clean_tables(){
        DB::table('phr_mst_units')->delete();
        DB::table('phr_mst_categories')->delete();
        DB::table('sup_status')->delete();
        DB::table('mst_discount_modes')->delete();

    }

    private function phr_mst_unit(){
        $client_id = DB::table('app_clients')->where('code', 'sys')->pluck('id')->first();

        DB::table('phr_mst_units')->insert([
            array('id' => 1,'client_id' => $client_id,'code' => '01','name_en' => 'Piece','name_lc' => 'वटा', 'count' => '1','is_active' => true),
            array('id' => 2,'client_id' => $client_id,'code' => '02','name_en' => 'Bottle','name_lc' => 'बोत्तल', 'count' => '1','is_active' => true),
            array('id' => 3,'client_id' => $client_id,'code' => '03','name_en' => 'Packet','name_lc' => 'प्याकेट', 'count' => NULL,'is_active' => true)
        ]);

        $arr=[
            array('id' => 4,'client_id' => $client_id,'code' => '04','name_en' => 'Phile_10', 'name_lc' => '१० को पत्ता', 'count' => '10','is_active' => true),
            array('id' => 5,'client_id' => $client_id,'code' => '05','name_en' => 'Phile_15','name_lc' => '१५ को पत्ता', 'count' => '15', 'is_active' => true),
        ];
        
        
        DB::table('phr_mst_units')->insert($arr);
        DB::statement("SELECT SETVAL('phr_mst_units_id_seq',100)");
    }
    private function phr_mst_categories(){
        $client_id = DB::table('app_clients')->where('code', 'sys')->pluck('id')->first();

        DB::table('phr_mst_categories')->insert([
            array('id' => 1,'client_id' => $client_id,'code' => '01','title_en' =>'SURGICAL APPLIANCES' , 'title_lc' => 'SURGICAL APPLIANCES'),
            array('id' => 2,'client_id' => $client_id,'code' => '02','title_en' =>'I.V. FLUIDS' , 'title_lc' => 'I.V. FLUIDS'),
            array('id' => 3,'client_id' => $client_id,'code' => '03','title_en' =>'CARDIAC DRUGS' , 'title_lc' => 'CARDIAC DRUGS'),
            array('id' => 4,'client_id' => $client_id,'code' => '04','title_en' =>'ANTI-MICROBIALS' , 'title_lc' => 'ANTI-MICROBIALS'),
            array('id' => 5,'client_id' => $client_id,'code' => '05','title_en' =>'ACCINES, ANTISERA & IMMUNOLOGICALS' , 'title_lc' => 'ACCINES, ANTISERA & IMMUNOLOGICALS'),
            array('id' => 6,'client_id' => $client_id,'code' => '06','title_en' =>'INSULIN' , 'title_lc' => 'INSULIN'),
            array('id' => 7,'client_id' => $client_id,'code' => '07','title_en' =>'OTC' , 'title_lc' => 'OTC'),
            array('id' => 8,'client_id' => $client_id,'code' => '08','title_en' =>'MEDICAL DEVICES' , 'title_lc' => 'MEDICAL DEVICES'),
            array('id' => 9,'client_id' => $client_id,'code' => '09','title_en' =>'COSMETICS' , 'title_lc' => 'COSMETICS'),
            array('id' => 10,'client_id' => $client_id,'code' => '10','title_en' =>'AYURVEDIC & HERBAL ITEMS' , 'title_lc' => 'AYURVEDIC & HERBAL ITEMS'),
            array('id' => 11,'client_id' => $client_id,'code' => '11','title_en' =>'NEUTRACEUTICALS' , 'title_lc' => 'NEUTRACEUTICALS'),
            array('id' => 12,'client_id' => $client_id,'code' => '12','title_en' =>'TOILETRIES' , 'title_lc' => 'TOILETRIES'),
            array('id' => 13,'client_id' => $client_id,'code' => '13','title_en' =>'ANAESTHETICS' , 'title_lc' => 'ANAESTHETICS'),
            array('id' => 14,'client_id' => $client_id,'code' => '14','title_en' =>'PSYCHOTROPIC  SCHEDULE III,IV' , 'title_lc' => 'PSYCHOTROPIC  SCHEDULE III,IV'),
            array('id' => 15,'client_id' => $client_id,'code' => '15','title_en' =>'ONCO MEDICINE' , 'title_lc' => 'ONCO MEDICINE'),
            array('id' => 16,'client_id' => $client_id,'code' => '16','title_en' =>'OPTHALMICS' , 'title_lc' => 'OPTHALMICS'),
            array('id' => 17,'client_id' => $client_id,'code' => '17','title_en' =>'HORMONES' , 'title_lc' => 'HORMONES'),
            array('id' => 18,'client_id' => $client_id,'code' => '18','title_en' =>'ANTIDOTE' , 'title_lc' => 'ANTIDOTE'),
            array('id' => 19,'client_id' => $client_id,'code' => '19','title_en' =>'NEURO MEDICINE' , 'title_lc' => 'NEURO MEDICINE'),
            array('id' => 20,'client_id' => $client_id,'code' => '20','title_en' =>'CONTRAST MEDIA' , 'title_lc' => 'CONTRAST MEDIA'),
            array('id' => 21,'client_id' => $client_id,'code' => '21','title_en' =>'ANTI-DIABETIC' , 'title_lc' => 'ANTI-DIABETIC'),
            array('id' => 22,'client_id' => $client_id,'code' => '22','title_en' =>'ANTI-VIRAL' , 'title_lc' => 'ANTI-VIRAL'),
            array('id' => 23,'client_id' => $client_id,'code' => '23','title_en' =>'VITAMINS AND MINERALS' , 'title_lc' => 'VITAMINS AND MINERALS'),
            array('id' => 24,'client_id' => $client_id,'code' => '24','title_en' =>'IMMUNOSUPPRESSANT' , 'title_lc' => 'IMMUNOSUPPRESSANT'),
            array('id' => 25,'client_id' => $client_id,'code' => '25','title_en' =>'NARCOTIC' , 'title_lc' => 'NARCOTIC'),
            array('id' => 26,'client_id' => $client_id,'code' => '26','title_en' =>'SURGICAL ITEMS' , 'title_lc' => 'SURGICAL ITEMS'),
            array('id' => 27,'client_id' => $client_id,'code' => '27','title_en' =>'GENERAL MEDICINE' , 'title_lc' => 'GENERAL MEDICINE'),
        ]);
        DB::statement("SELECT SETVAL('phr_mst_categories_id_seq',100)");
       
    }

    private function mst_discount_modes(){

        DB::table('mst_discount_modes')->insert([
            ['id' => 1, 'code' => '01', 'name_en' => '%', 'name_lc' => ' %', 'is_active' => 'true',  'created_at' => Carbon::now()->format('d-m-Y') ],
            ['id' => 2, 'code' => '02', 'name_en' => 'NRS', 'name_lc' => ' NRS', 'is_active' => 'true',  'created_at' => Carbon::now()->format('d-m-Y')],
        ]);
        DB::statement("SELECT SETVAL('mst_discount_modes_id_seq',100)");


    }
    // public function sup_status(){
    //     DB::table('sup_status')->insert([
    //         array('id' => 1, 'code' => '1', 'name_en' => 'created', 'created_at' => Carbon::now()->format('d-m-Y')),
    //         array('id' => 2, 'code' => '2', 'name_en' => 'approved', 'created_at' => Carbon::now()->format('d-m-Y')),
    //         array('id' => 3, 'code' => '3', 'name_en' => 'cancelled', 'created_at' => Carbon::now()->format('d-m-Y')),
    //         array('id' => 4, 'code' => '4', 'name_en' => 'partial_return', 'created_at' => Carbon::now()->format('d-m-Y')),
    //         array('id' => 5, 'code' => '5', 'name_en' => 'full_return', 'created_at' => Carbon::now()->format('d-m-Y')),
    //     ]);

    //     DB::statement("SELECT SETVAL('sup_status_id_seq',100)");
    // }
}
