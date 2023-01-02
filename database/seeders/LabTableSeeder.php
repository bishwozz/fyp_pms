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

class LabTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->categorySeeder();
        $this->labMethodSeeder();
        $this->labSampleSeeder();
        $this->itemsSeeder();
        $this->labGroupSeeder();
        $this->labGroupItemsSeeder();
        $this->labPanelSeeder();
        $this->labPanelGroupSeeder();
    }

    private function categorySeeder(){
        DB::table('lab_mst_categories')->insert([
            array('id' => 1, 'code'=>1, 'title' => 'General', 'description' => 'General Category', 'is_active' => true, 'client_id' => 2),
            array('id' => 2, 'code'=>2, 'title' => 'Haematology', 'description' => 'Haematology', 'is_active' => true, 'client_id' => 2),
            array('id' => 3, 'code'=>3, 'title' => 'Immunology', 'description' => 'Immunology', 'is_active' => true, 'client_id' => 2),
            array('id' => 4, 'code'=>4, 'title' => 'Biochemistry', 'description' => 'Biochemistry', 'is_active' => true, 'client_id' => 2),
            array('id' => 5, 'code'=>5, 'title' => 'Bacteriology', 'description' => 'Bacteriology', 'is_active' => true, 'client_id' => 2),
            array('id' => 6, 'code'=>6, 'title' => 'Virology', 'description' => 'General', 'is_active' => true, 'client_id' => 2),
            array('id' => 7, 'code'=>7, 'title' => 'Parasitology', 'description' => 'Parasitology', 'is_active' => true, 'client_id' => 2),
            array('id' => 8, 'code'=>8, 'title' => 'Hormone/ Endocrine', 'description' => 'Hormone/ Endocrine Category', 'is_active' => true, 'client_id' => 2),
            array('id' => 9, 'code'=>9, 'title' => 'Drug Analysis', 'description' => 'Drug Analysis Category', 'is_active' => true, 'client_id' => 2),
            array('id' => 10, 'code'=>10, 'title' => 'Histopathology/ Cytology', 'description' => 'Histopathology/ Cytology Category', 'is_active' => true, 'client_id' => 2),
            array('id' => 11, 'code'=>11, 'title' => 'Immuno-Histo Chemistry', 'description' => 'Immuno-Histo Chemestry Category', 'is_active' => true, 'client_id' => 2),
            array('id' => 12, 'code'=>12, 'title' => 'Molecular Pathology', 'description' => 'Molecular Pathology', 'is_active' => true, 'client_id' => 2),
            array('id' => 13, 'code'=>13, 'title' => 'MICROBIOLOGY', 'description' => 'MICROBIOLOGY', 'is_active' => true, 'client_id' => 2),
            array('id' => 14, 'code'=>14, 'title' => 'Serology & Immunology', 'description' => 'Serology & Immunology', 'is_active' => true, 'client_id' => 2),
        ]);
        DB::statement("SELECT SETVAL('lab_mst_categories_id_seq',14)");
    }


    private function labMethodSeeder()
    {
        DB::table('mst_lab_methods')->insert([
            array('id'=>1,'code'=>1,'name'=>'Automated'),
            array('id'=>2,'code'=>2,'name'=>'Manual'),
            array('id'=>3,'code'=>3,'name'=>'Automated/Manual'),
            array('id'=>4,'code'=>4,'name'=>'.'),
            array('id'=>5,'code'=>5,'name'=>'Cyanmeth-Hb'),
            array('id'=>6,'code'=>6,'name'=>'Electrochemiluminescence'),
            array('id'=>7,'code'=>7,'name'=>'CLIA'),
            array('id'=>8,'code'=>8,'name'=>'RT PCR'),
            array('id'=>9,'code'=>9,'name'=>'ISE Direct'),
            array('id'=>10,'code'=>10,'name'=>'Spectrophotometry'),
            array('id'=>11,'code'=>11,'name'=>'Immunoturbidimetric'),
            array('id'=>12,'code'=>12,'name'=>'LATEX AGGLUTINATION'),
            array('id'=>13,'code'=>13,'name'=>'Westergren'),
            array('id'=>14,'code'=>14,'name'=>'Microscopic'),
            array('id'=>15,'code'=>15,'name'=>'Lateral Flow Immunoassay'),

        ]);
        DB::statement("SELECT SETVAL('mst_lab_methods_id_seq',15)");

    }

    private function labSampleSeeder()
    {
        DB::table('mst_lab_samples')->insert([
            array('id'=>1,'code'=>1,'name'=>'Peripheral Blood'),
            array('id'=>2,'code'=>2,'name'=>'Seum'),
            array('id'=>3,'code'=>3,'name'=>'Nasopharyngeal/Oropharyngeal'),
            array('id'=>4,'code'=>4,'name'=>'Whole Blood-EDTA'),
            array('id'=>5,'code'=>5,'name'=>'Stool'),
            array('id'=>6,'code'=>6,'name'=>'Urine'),
            array('id'=>7,'code'=>7,'name'=>'Serum/EDTA Plasma'),
        ]);
        DB::statement("SELECT SETVAL('mst_lab_samples_id_seq',7)");

    }

    private function itemsSeeder()
    {
        DB::table('lab_mst_items')->insert([
            array('id'=>1,'client_id'=>2,'code'=>'ANTIGEN','lab_category_id'=>12,'name'=>'COVID-19 (SARS-CoV-2) ANTIGEN','reference_from_value'=>'','reference_from_to'=>'','unit'=>'','price'=>'1000','is_testable'=>1,'result_field_type'=>2,'result_field_options'=>'[{"result_field_options":"POSITIVE "},{"result_field_options":"NEGATIVE"}]','sample_id'=>3,'method_id'=>8,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>2,'client_id'=>2,'code'=>'N gene','lab_category_id'=>12,'name'=>'N gene','reference_from_value'=>'','reference_from_to'=>'','unit'=>'','price'=>'0','is_testable'=>0,'result_field_type'=>1,'result_field_options'=>'[]','sample_id'=>3,'method_id'=>8,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>3,'client_id'=>2,'code'=>'E gene','lab_category_id'=>12,'name'=>'E- gene','reference_from_value'=>'','reference_from_to'=>'','unit'=>'','price'=>'0','is_testable'=>0,'result_field_type'=>1,'result_field_options'=>'[]','sample_id'=>3,'method_id'=>8,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>4,'client_id'=>2,'code'=>'RdRp','lab_category_id'=>12,'name'=>'RdRp  gene','reference_from_value'=>'','reference_from_to'=>'','unit'=>'','price'=>'0','is_testable'=>0,'result_field_type'=>1,'result_field_options'=>'[]','sample_id'=>3,'method_id'=>8,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>5,'client_id'=>2,'code'=>'Final Result','lab_category_id'=>12,'name'=>'FINAL RESULT','reference_from_value'=>'','reference_from_to'=>'','unit'=>'','price'=>'0','is_testable'=>0,'result_field_type'=>1,'result_field_options'=>'[]','sample_id'=>3,'method_id'=>8,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>6,'client_id'=>2,'code'=>'Hemoglobin','lab_category_id'=>2,'name'=>'Hemogolbin','reference_from_value'=>'12','reference_from_to'=>'15','unit'=>'g/dl','price'=>'0','is_testable'=>1,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>1,'method_id'=>5,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>7,'client_id'=>2,'code'=>'PCV','lab_category_id'=>2,'name'=>'PCV','reference_from_value'=>'36','reference_from_to'=>'46','unit'=>'%','price'=>'0','is_testable'=>0,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>1,'method_id'=>1,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>8,'client_id'=>2,'code'=>'Leukocyte','lab_category_id'=>2,'name'=>'Total Leukocyte Count','reference_from_value'=>'4000','reference_from_to'=>'11000','unit'=>'/cumm','price'=>'0','is_testable'=>1,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>1,'method_id'=>1,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>9,'client_id'=>2,'code'=>'Neutrophils','lab_category_id'=>2,'name'=>'Neutrophils','reference_from_value'=>'40','reference_from_to'=>'75','unit'=>'%','price'=>'0','is_testable'=>0,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>1,'method_id'=>3,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>10,'client_id'=>2,'code'=>'Lymphocytes','lab_category_id'=>2,'name'=>'Lymphocytes','reference_from_value'=>'20','reference_from_to'=>'50','unit'=>'%','price'=>'0','is_testable'=>0,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>1,'method_id'=>3,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>11,'client_id'=>2,'code'=>'Monocytes','lab_category_id'=>2,'name'=>'Monocytes','reference_from_value'=>'2','reference_from_to'=>'10','unit'=>'%','price'=>'0','is_testable'=>0,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>1,'method_id'=>3,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>12,'client_id'=>2,'code'=>'Eosinophil','lab_category_id'=>2,'name'=>'Eosinophil','reference_from_value'=>'1','reference_from_to'=>'6','unit'=>'%','price'=>'0','is_testable'=>0,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>1,'method_id'=>3,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>13,'client_id'=>2,'code'=>'Basophils','lab_category_id'=>2,'name'=>'Basophils','reference_from_value'=>'','reference_from_to'=>'1','unit'=>'%','price'=>'0','is_testable'=>0,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>1,'method_id'=>3,'is_special_reference'=>0,'special_reference'=>'1'),
            array('id'=>14,'client_id'=>2,'code'=>'RBC','lab_category_id'=>2,'name'=>'RBC','reference_from_value'=>'3.8','reference_from_to'=>'4.8','unit'=>'million/ul','price'=>'0','is_testable'=>1,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>1,'method_id'=>1,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>15,'client_id'=>2,'code'=>'MCV','lab_category_id'=>2,'name'=>'MCV','reference_from_value'=>'80','reference_from_to'=>'100','unit'=>'fl','price'=>'0','is_testable'=>0,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>1,'method_id'=>1,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>16,'client_id'=>2,'code'=>'MCH','lab_category_id'=>2,'name'=>'MCH','reference_from_value'=>'27','reference_from_to'=>'32','unit'=>'pg','price'=>'0','is_testable'=>0,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>1,'method_id'=>1,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>17,'client_id'=>2,'code'=>'MCHC','lab_category_id'=>2,'name'=>'MCHC','reference_from_value'=>'31','reference_from_to'=>'38','unit'=>'%','price'=>'0','is_testable'=>0,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>1,'method_id'=>1,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>18,'client_id'=>2,'code'=>'Platelet Count','lab_category_id'=>2,'name'=>'Platelet Count','reference_from_value'=>'150000','reference_from_to'=>'450000','unit'=>'/ul','price'=>'0','is_testable'=>1,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>1,'method_id'=>1,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>19,'client_id'=>2,'code'=>'ESR','lab_category_id'=>2,'name'=>'Erythrocyte Sedimentation Rate','reference_from_value'=>'0','reference_from_to'=>'15','unit'=>'mm/hr','price'=>'0','is_testable'=>1,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>4,'method_id'=>13,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>20,'client_id'=>2,'code'=>'Other','lab_category_id'=>13,'name'=>'Other','reference_from_value'=>'','reference_from_to'=>'','unit'=>'','price'=>'0','is_testable'=>1,'result_field_type'=>1,'result_field_options'=>'[]','sample_id'=>5,'method_id'=>2,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>21,'client_id'=>2,'code'=>'RBC(M)','lab_category_id'=>13,'name'=>'RBC(M)','reference_from_value'=>'','reference_from_to'=>'','unit'=>'/HPF','price'=>'0','is_testable'=>1,'result_field_type'=>1,'result_field_options'=>'[]','sample_id'=>5,'method_id'=>2,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>22,'client_id'=>2,'code'=>'OVA','lab_category_id'=>13,'name'=>'OVA','reference_from_value'=>'','reference_from_to'=>'','unit'=>'','price'=>'0','is_testable'=>1,'result_field_type'=>1,'result_field_options'=>'[]','sample_id'=>5,'method_id'=>14,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>23,'client_id'=>2,'code'=>'Cyst','lab_category_id'=>13,'name'=>'Cyst','reference_from_value'=>'','reference_from_to'=>'','unit'=>'','price'=>'0','is_testable'=>1,'result_field_type'=>1,'result_field_options'=>'[]','sample_id'=>5,'method_id'=>14,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>24,'client_id'=>2,'code'=>'Parasite','lab_category_id'=>13,'name'=>'Parasite','reference_from_value'=>'','reference_from_to'=>'','unit'=>'','price'=>'0','is_testable'=>1,'result_field_type'=>1,'result_field_options'=>'[]','sample_id'=>5,'method_id'=>14,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>25,'client_id'=>2,'code'=>'UFP','lab_category_id'=>13,'name'=>'Undigested Food Particles','reference_from_value'=>'','reference_from_to'=>'','unit'=>'','price'=>'0','is_testable'=>1,'result_field_type'=>1,'result_field_options'=>'[]','sample_id'=>5,'method_id'=>2,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>26,'client_id'=>2,'code'=>'Pus Cells','lab_category_id'=>13,'name'=>'Pus Cells','reference_from_value'=>'','reference_from_to'=>'','unit'=>'/HPF','price'=>'0','is_testable'=>1,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>5,'method_id'=>14,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>27,'client_id'=>2,'code'=>'Stool-Mucus','lab_category_id'=>13,'name'=>'Stool-Mucus','reference_from_value'=>'','reference_from_to'=>'','unit'=>'','price'=>'0','is_testable'=>1,'result_field_type'=>1,'result_field_options'=>'[]','sample_id'=>5,'method_id'=>2,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>28,'client_id'=>2,'code'=>'Stool-Blood','lab_category_id'=>13,'name'=>'Stool-Blood','reference_from_value'=>'','reference_from_to'=>'','unit'=>'','price'=>'0','is_testable'=>1,'result_field_type'=>1,'result_field_options'=>'[]','sample_id'=>5,'method_id'=>2,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>29,'client_id'=>2,'code'=>'Consistancy','lab_category_id'=>13,'name'=>'Consistancy','reference_from_value'=>'','reference_from_to'=>'','unit'=>'','price'=>'0','is_testable'=>1,'result_field_type'=>1,'result_field_options'=>'[]','sample_id'=>5,'method_id'=>2,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>30,'client_id'=>2,'code'=>'Color','lab_category_id'=>13,'name'=>'Color','reference_from_value'=>'','reference_from_to'=>'','unit'=>'','price'=>'0','is_testable'=>1,'result_field_type'=>1,'result_field_options'=>'[]','sample_id'=>6,'method_id'=>null,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>32,'client_id'=>2,'code'=>'FT3','lab_category_id'=>14,'name'=>'FreeT3 (FT3)','reference_from_value'=>'2.7','reference_from_to'=>'5.2','unit'=>'pg/ml','price'=>'0','is_testable'=>0,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>2,'method_id'=>7,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>33,'client_id'=>2,'code'=>'FT4','lab_category_id'=>14,'name'=>'Free T4 (FT4)','reference_from_value'=>'0.78','reference_from_to'=>'2.19','unit'=>'FT4','price'=>'0','is_testable'=>0,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>2,'method_id'=>7,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>34,'client_id'=>2,'code'=>'TSH','lab_category_id'=>14,'name'=>'TSH (Thyroid Stimulating Hormone)','reference_from_value'=>'0.54','reference_from_to'=>'4.72','unit'=>'Electrochemiluminescence)','price'=>'0','is_testable'=>0,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>2,'method_id'=>6,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>35,'client_id'=>2,'code'=>'Transparency','lab_category_id'=>13,'name'=>'Transparency','reference_from_value'=>'','reference_from_to'=>'','unit'=>'','price'=>'0','is_testable'=>1,'result_field_type'=>1,'result_field_options'=>'[]','sample_id'=>6,'method_id'=>null,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>36,'client_id'=>2,'code'=>'pH','lab_category_id'=>13,'name'=>'pH','reference_from_value'=>'','reference_from_to'=>'','unit'=>'','price'=>'0','is_testable'=>0,'result_field_type'=>1,'result_field_options'=>'[]','sample_id'=>6,'method_id'=>2,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>37,'client_id'=>2,'code'=>'Sugar','lab_category_id'=>13,'name'=>'Sugar','reference_from_value'=>'','reference_from_to'=>'','unit'=>'','price'=>'0','is_testable'=>0,'result_field_type'=>1,'result_field_options'=>'[]','sample_id'=>6,'method_id'=>2,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>38,'client_id'=>2,'code'=>'Protein','lab_category_id'=>13,'name'=>'Proetein','reference_from_value'=>'','reference_from_to'=>'','unit'=>'','price'=>'0','is_testable'=>0,'result_field_type'=>1,'result_field_options'=>'[]','sample_id'=>6,'method_id'=>2,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>39,'client_id'=>2,'code'=>'Epithelial Cells','lab_category_id'=>13,'name'=>'Epithelial Cells','reference_from_value'=>'','reference_from_to'=>'','unit'=>'/HPF','price'=>'0','is_testable'=>1,'result_field_type'=>1,'result_field_options'=>'[]','sample_id'=>6,'method_id'=>14,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>40,'client_id'=>2,'code'=>'Crystals','lab_category_id'=>13,'name'=>'Crystals','reference_from_value'=>'','reference_from_to'=>'','unit'=>'/HPF','price'=>'0','is_testable'=>0,'result_field_type'=>1,'result_field_options'=>'[]','sample_id'=>6,'method_id'=>14,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>41,'client_id'=>2,'code'=>'Casts','lab_category_id'=>13,'name'=>'Casts','reference_from_value'=>'','reference_from_to'=>'','unit'=>'/HPF','price'=>'0','is_testable'=>0,'result_field_type'=>1,'result_field_options'=>'[]','sample_id'=>6,'method_id'=>14,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>42,'client_id'=>2,'code'=>'Bacteria','lab_category_id'=>13,'name'=>'Bacteria','reference_from_value'=>'','reference_from_to'=>'','unit'=>'','price'=>'0','is_testable'=>0,'result_field_type'=>1,'result_field_options'=>'[]','sample_id'=>6,'method_id'=>14,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>43,'client_id'=>2,'code'=>'Bilirubin Total','lab_category_id'=>4,'name'=>'Bilirubin Total','reference_from_value'=>'','reference_from_to'=>'1.2','unit'=>'mg/dl','price'=>'0','is_testable'=>1,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>2,'method_id'=>10,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>44,'client_id'=>2,'code'=>'Bilirubin Direct','lab_category_id'=>4,'name'=>'Bilirubin Direct','reference_from_value'=>'0.0','reference_from_to'=>'0.3','unit'=>'mg/dl','price'=>'0','is_testable'=>1,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>2,'method_id'=>10,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>45,'client_id'=>2,'code'=>'ALT / SGPT','lab_category_id'=>4,'name'=>'Alanine aminotransferase - (ALT / SGPT)','reference_from_value'=>'0','reference_from_to'=>'45','unit'=>'U/L','price'=>'0','is_testable'=>1,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>2,'method_id'=>10,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>46,'client_id'=>2,'code'=>'AST/SGOT)','lab_category_id'=>4,'name'=>'Aspartate Aminotransferase (AST/SGOT)','reference_from_value'=>'0','reference_from_to'=>'35','unit'=>'U/L','price'=>'0','is_testable'=>1,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>2,'method_id'=>10,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>47,'client_id'=>2,'code'=>'ALP','lab_category_id'=>4,'name'=>'Alkaline Phosphatase (ALP)','reference_from_value'=>'','reference_from_to'=>'','unit'=>'U/L','price'=>'0','is_testable'=>1,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>2,'method_id'=>10,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>48,'client_id'=>2,'code'=>'Total Protein','lab_category_id'=>4,'name'=>'Total Protein','reference_from_value'=>'','reference_from_to'=>'','unit'=>'g/dl','price'=>'0','is_testable'=>1,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>2,'method_id'=>10,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>49,'client_id'=>2,'code'=>'Albumin','lab_category_id'=>4,'name'=>'Albumin','reference_from_value'=>'','reference_from_to'=>'','unit'=>'g/dl','price'=>'0','is_testable'=>1,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>2,'method_id'=>10,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>50,'client_id'=>2,'code'=>'Dengue','lab_category_id'=>14,'name'=>'Dengue Antibody Detection (IgG)','reference_from_value'=>'','reference_from_to'=>'','unit'=>'','price'=>'0','is_testable'=>1,'result_field_type'=>2,'result_field_options'=>'[{"result_field_options":"POSITIVE"},{"result_field_options":"NEGATIVE"}]','sample_id'=>7,'method_id'=>15,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>51,'client_id'=>2,'code'=>'IgM','lab_category_id'=>14,'name'=>'Dengue Antibody Detection (IgM)','reference_from_value'=>'','reference_from_to'=>'','unit'=>'','price'=>'0','is_testable'=>1,'result_field_type'=>2,'result_field_options'=>'[{"result_field_options":"POSITIVE"},{"result_field_options":"NEGATIVE"}]','sample_id'=>7,'method_id'=>15,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>52,'client_id'=>2,'code'=>'NS1','lab_category_id'=>14,'name'=>'Dengue NS 1 Antigen','reference_from_value'=>'','reference_from_to'=>'','unit'=>'','price'=>'0','is_testable'=>1,'result_field_type'=>2,'result_field_options'=>'[{"result_field_options":"POSITIVE"},{"result_field_options":"NEGATIVE"}]','sample_id'=>2,'method_id'=>15,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>53,'client_id'=>2,'code'=>'Urea','lab_category_id'=>4,'name'=>'Urea','reference_from_value'=>'15','reference_from_to'=>'45','unit'=>'mg/dl','price'=>'0','is_testable'=>1,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>2,'method_id'=>10,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>54,'client_id'=>2,'code'=>'Creatinine','lab_category_id'=>4,'name'=>'Creatinine','reference_from_value'=>'0.6','reference_from_to'=>'1.1','unit'=>'mg/dl','price'=>'0','is_testable'=>1,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>2,'method_id'=>10,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>55,'client_id'=>2,'code'=>'Sodium','lab_category_id'=>4,'name'=>'Sodium','reference_from_value'=>'135','reference_from_to'=>'145','unit'=>'mmol/L','price'=>'0','is_testable'=>1,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>2,'method_id'=>9,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>56,'client_id'=>2,'code'=>'Potassium','lab_category_id'=>4,'name'=>'Potassium','reference_from_value'=>'3.5','reference_from_to'=>'5.5','unit'=>'mmol/L','price'=>'0','is_testable'=>1,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>2,'method_id'=>9,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>57,'client_id'=>2,'code'=>'Fasting','lab_category_id'=>4,'name'=>'Fasting','reference_from_value'=>'70','reference_from_to'=>'110','unit'=>'mg/dl','price'=>'90','is_testable'=>1,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>2,'method_id'=>10,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>58,'client_id'=>2,'code'=>'PP','lab_category_id'=>4,'name'=>'PP','reference_from_value'=>'70','reference_from_to'=>'140','unit'=>'mg/dl','price'=>'90','is_testable'=>1,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>2,'method_id'=>10,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>59,'client_id'=>2,'code'=>'Semi-Quantitative','lab_category_id'=>14,'name'=>'Semi-Quantitative','reference_from_value'=>'UPTO10','reference_from_to'=>'','unit'=>'mg/L','price'=>'600','is_testable'=>1,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>2,'method_id'=>12,'is_special_reference'=>0,'special_reference'=>''),
            array('id'=>60,'client_id'=>2,'code'=>'Vitamin D','lab_category_id'=>14,'name'=>'VITAMIN D','reference_from_value'=>'','reference_from_to'=>'','unit'=>'ng/ml','price'=>'2500','is_testable'=>1,'result_field_type'=>0,'result_field_options'=>'[]','sample_id'=>null,'method_id'=>7,'is_special_reference'=>1,'special_reference'=>'<p>Deficient : &lt; 10 ng/ml</p><p>Insufficient : 10 - 29 ng/ml</p><p>Sufficient : 29 - 100 ng/ml</p><p>Potential intoxication : &gt; 100 ng/ml<br></p>'),
        ]);

        DB::statement("SELECT SETVAL('lab_mst_items_id_seq',60)");

    }

    private function labGroupSeeder()
    {
        DB::table('lab_groups')->insert([
            array('id'=>1,'client_id'=>2,'code'=>'1','lab_category_id'=>2,'name'=>'Differential Counts'),
            array('id'=>2,'client_id'=>2,'code'=>'2','lab_category_id'=>13,'name'=>'Microscopic Examination'),
            array('id'=>3,'client_id'=>2,'code'=>'3','lab_category_id'=>13,'name'=>'Physical Examination'),
            array('id'=>4,'client_id'=>2,'code'=>'4','lab_category_id'=>13,'name'=>'Physical Examination'),
            array('id'=>5,'client_id'=>2,'code'=>'5','lab_category_id'=>13,'name'=>'Chemical Examination'),
            array('id'=>6,'client_id'=>2,'code'=>'6','lab_category_id'=>13,'name'=>'Microscopic Examination'),

        ]);
        DB::statement("SELECT SETVAL('lab_groups_id_seq',6)");

    }
    private function labGroupItemsSeeder()
    {
        DB::table('lab_group_items')->insert([
            array('id'=>1,'lab_item_id'=>9,'lab_group_id'=>1),
            array('id'=>2,'lab_item_id'=>10,'lab_group_id'=>1),
            array('id'=>3,'lab_item_id'=>11,'lab_group_id'=>1),
            array('id'=>4,'lab_item_id'=>12,'lab_group_id'=>1),
            array('id'=>5,'lab_item_id'=>13,'lab_group_id'=>1),
            array('id'=>10,'lab_item_id'=>20,'lab_group_id'=>2),
            array('id'=>11,'lab_item_id'=>21,'lab_group_id'=>2),
            array('id'=>12,'lab_item_id'=>22,'lab_group_id'=>2),
            array('id'=>13,'lab_item_id'=>23,'lab_group_id'=>2),
            array('id'=>14,'lab_item_id'=>24,'lab_group_id'=>2),
            array('id'=>15,'lab_item_id'=>25,'lab_group_id'=>2),
            array('id'=>16,'lab_item_id'=>26,'lab_group_id'=>2),
            array('id'=>18,'lab_item_id'=>27,'lab_group_id'=>3),
            array('id'=>19,'lab_item_id'=>28,'lab_group_id'=>3),
            array('id'=>20,'lab_item_id'=>29,'lab_group_id'=>3),
            array('id'=>21,'lab_item_id'=>30,'lab_group_id'=>3),
            array('id'=>22,'lab_item_id'=>30,'lab_group_id'=>4),
            array('id'=>23,'lab_item_id'=>35,'lab_group_id'=>4),
            array('id'=>24,'lab_item_id'=>36,'lab_group_id'=>5),
            array('id'=>25,'lab_item_id'=>37,'lab_group_id'=>5),
            array('id'=>26,'lab_item_id'=>38,'lab_group_id'=>5),
            
        ]);
        DB::statement("SELECT SETVAL('lab_group_items_id_seq',26)");

    }

    private function labPanelSeeder()
    {
        DB::table('lab_panels')->insert([
            array('id'=>1,'client_id'=>2,'code'=>'SARS-COVID-2','name'=>'SARS-COVID-2','charge_amount'=>1500,'lab_category_id'=>12),
            array('id'=>2,'client_id'=>2,'code'=>'CBC','name'=>'Complete Blood Count (CBC)','charge_amount'=>450,'lab_category_id'=>2),
            array('id'=>3,'client_id'=>2,'code'=>'Stool R/E','name'=>'Stool - Routine Examination','charge_amount'=>110,'lab_category_id'=>13),
            array('id'=>5,'client_id'=>2,'code'=>'TFT','name'=>'Thyroid Function Tes','charge_amount'=>950,'lab_category_id'=>14),
            array('id'=>6,'client_id'=>2,'code'=>'LFT','name'=>'Liver Function Test','charge_amount'=>950,'lab_category_id'=>4),
            array('id'=>7,'client_id'=>2,'code'=>'Dengue','name'=>'DENGUE FEVER COMBINED PANEL','charge_amount'=>1200,'lab_category_id'=>14),
            array('id'=>12,'client_id'=>2,'code'=>'RFT','name'=>'Renal Function Test','charge_amount'=>750,'lab_category_id'=>4),
            array('id'=>13,'client_id'=>2,'code'=>'Blood Sugar f','name'=>'Blood Sugar - Fasting','charge_amount'=>90,'lab_category_id'=>4),
            array('id'=>14,'client_id'=>2,'code'=>'Blood Sugar  PP','name'=>'Blood Sugar - PP','charge_amount'=>90,'lab_category_id'=>4),
            array('id'=>15,'client_id'=>2,'code'=>'CRP','name'=>'C-Reactive Protein (CRP)','charge_amount'=>600,'lab_category_id'=>14),
            
        ]);
        DB::statement("SELECT SETVAL('lab_panels_id_seq',15)");

    }

    private function labPanelGroupSeeder()
    {
        DB::table('lab_panel_groups_items')->insert([
            array('id'=>1,'lab_panel_id'=>1,'lab_group_id'=>null,'lab_item_id'=>2),
            array('id'=>2,'lab_panel_id'=>1,'lab_group_id'=>null,'lab_item_id'=>3),
            array('id'=>3,'lab_panel_id'=>1,'lab_group_id'=>null,'lab_item_id'=>4),
            array('id'=>4,'lab_panel_id'=>1,'lab_group_id'=>null,'lab_item_id'=>5),
            array('id'=>13,'lab_panel_id'=>3,'lab_group_id'=>3,'lab_item_id'=>null),
            array('id'=>14,'lab_panel_id'=>3,'lab_group_id'=>2,'lab_item_id'=>null),
            array('id'=>16,'lab_panel_id'=>2,'lab_group_id'=>null,'lab_item_id'=>6),
            array('id'=>17,'lab_panel_id'=>2,'lab_group_id'=>null,'lab_item_id'=>7),
            array('id'=>18,'lab_panel_id'=>2,'lab_group_id'=>null,'lab_item_id'=>8),
            array('id'=>19,'lab_panel_id'=>2,'lab_group_id'=>null,'lab_item_id'=>14),
            array('id'=>20,'lab_panel_id'=>2,'lab_group_id'=>null,'lab_item_id'=>15),
            array('id'=>21,'lab_panel_id'=>2,'lab_group_id'=>null,'lab_item_id'=>16),
            array('id'=>22,'lab_panel_id'=>2,'lab_group_id'=>null,'lab_item_id'=>17),
            array('id'=>23,'lab_panel_id'=>2,'lab_group_id'=>null,'lab_item_id'=>18),
            array('id'=>24,'lab_panel_id'=>2,'lab_group_id'=>1,'lab_item_id'=>null),
            array('id'=>25,'lab_panel_id'=>5,'lab_group_id'=>null,'lab_item_id'=>34),
            array('id'=>26,'lab_panel_id'=>5,'lab_group_id'=>null,'lab_item_id'=>33),
            array('id'=>27,'lab_panel_id'=>5,'lab_group_id'=>null,'lab_item_id'=>32),
            array('id'=>28,'lab_panel_id'=>6,'lab_group_id'=>null,'lab_item_id'=>43),
            array('id'=>29,'lab_panel_id'=>6,'lab_group_id'=>null,'lab_item_id'=>44),
            array('id'=>30,'lab_panel_id'=>6,'lab_group_id'=>null,'lab_item_id'=>45),
            array('id'=>31,'lab_panel_id'=>6,'lab_group_id'=>null,'lab_item_id'=>46),
            array('id'=>32,'lab_panel_id'=>6,'lab_group_id'=>null,'lab_item_id'=>47),
            array('id'=>33,'lab_panel_id'=>6,'lab_group_id'=>null,'lab_item_id'=>48),
            array('id'=>34,'lab_panel_id'=>6,'lab_group_id'=>null,'lab_item_id'=>49),
            array('id'=>35,'lab_panel_id'=>7,'lab_group_id'=>null,'lab_item_id'=>51),
            array('id'=>36,'lab_panel_id'=>7,'lab_group_id'=>null,'lab_item_id'=>52),
            array('id'=>37,'lab_panel_id'=>7,'lab_group_id'=>null,'lab_item_id'=>50),
            array('id'=>38,'lab_panel_id'=>12,'lab_group_id'=>null,'lab_item_id'=>53),
            array('id'=>39,'lab_panel_id'=>12,'lab_group_id'=>null,'lab_item_id'=>54),
            array('id'=>40,'lab_panel_id'=>12,'lab_group_id'=>null,'lab_item_id'=>55),
            array('id'=>41,'lab_panel_id'=>12,'lab_group_id'=>null,'lab_item_id'=>56),
            array('id'=>43,'lab_panel_id'=>13,'lab_group_id'=>null,'lab_item_id'=>57),
            array('id'=>44,'lab_panel_id'=>14,'lab_group_id'=>null,'lab_item_id'=>58),
            array('id'=>45,'lab_panel_id'=>15,'lab_group_id'=>null,'lab_item_id'=>59),
            
        ]);
        DB::statement("SELECT SETVAL('lab_panel_groups_items_id_seq',45)");

    }


    
}
