<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use App\Models\Lab\LabMstItems;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Lab\LabMstCategories;
use Illuminate\Support\Facades\Schema;
use App\Models\CoreMaster\MstLabMethod;
use App\Models\CoreMaster\MstLabSample;

class MasterTableSeeder extends Seeder
{
    public function run()
    {
        $this->clean_tables();

        $this->referalSeeder();
        $this->mst_department();
        $this->mst_sub_department();
    }

    private function clean_tables(){

        DB::table('mst_referrals')->delete();
        DB::table('hr_mst_departments')->delete();
        DB::table('hr_mst_sub_departments')->delete();
    }

    private function referalSeeder()
    {
        DB::table('mst_referrals')->insert([
            array('id'=>1,'client_id'=>2,'code'=>'1','name'=>'SELF','referral_type'=>1,'contact_person'=>'self','phone'=>'','email'=>'','address'=>'','discount_percentage'=>0),
            array('id'=>2,'client_id'=>2,'code'=>'2','name'=>'Prayag Marg Medical Hall','referral_type'=>3,'contact_person'=>'Arjun Kc','phone'=>'9818192420','email'=>'arjunkc966@gmail.com','address'=>'Shantinagar','discount_percentage'=>40),
            array('id'=>3,'client_id'=>2,'code'=>'3','name'=>'Dotel Pharmacy','referral_type'=>3,'contact_person'=>'Radhika Bajgai','phone'=>'9841068649','email'=>'radhika@gmail.com','address'=>'Patan','discount_percentage'=>40),
        ]);
        DB::statement("SELECT SETVAL('mst_referrals_id_seq',3)");
    }

    private function mst_department()
    {
        DB::table('hr_mst_departments')->insert([
            array('id'=>1,'client_id'=>2,'code'=>'1','title'=>'Administration'),
            array('id'=>2,'client_id'=>2,'code'=>'2','title'=>'Laboratory'),
        ]);
        DB::statement("SELECT SETVAL('hr_mst_departments_id_seq',2)");
    }

    private function mst_sub_department()
    {
        DB::table('hr_mst_sub_departments')->insert([
            array('id'=>1,'client_id'=>2,'department_id'=>1,'code'=>'1','title'=>'Account'),
            array('id'=>2,'client_id'=>2,'department_id'=>1,'code'=>'2','title'=>'Store'),
            array('id'=>3,'client_id'=>2,'department_id'=>1,'code'=>'3','title'=>'Admin Officer'),
            array('id'=>4,'client_id'=>2,'department_id'=>1,'code'=>'4','title'=>'Reception'),
            array('id'=>5,'client_id'=>2,'department_id'=>1,'code'=>'5','title'=>'Front Desk Officer'),
            array('id'=>6,'client_id'=>2,'department_id'=>1,'code'=>'6','title'=>'Admin Assistant'),
            array('id'=>7,'client_id'=>2,'department_id'=>1,'code'=>'7','title'=>'Reporting Officer'),
            array('id'=>10,'client_id'=>2,'department_id'=>2,'code'=>'8','title'=>'Molecular Lab'),
            array('id'=>11,'client_id'=>2,'department_id'=>2,'code'=>'9','title'=>'Hematology Lab'),
            array('id'=>12,'client_id'=>2,'department_id'=>2,'code'=>'10','title'=>'Microbiology'),
            array('id'=>13,'client_id'=>2,'department_id'=>2,'code'=>'11','title'=>'Immunology'),
            array('id'=>14,'client_id'=>2,'department_id'=>2,'code'=>'12','title'=>'Bio-Chemistry'),
        ]);
        DB::statement("SELECT SETVAL('hr_mst_sub_departments_id_seq',14)");
    }
    
}
