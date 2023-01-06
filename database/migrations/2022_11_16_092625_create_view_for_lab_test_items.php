<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewForLabTestItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // DB::statement("DROP VIEW IF EXISTS vw_lab_test_items");
        
        // DB::statement("CREATE VIEW vw_lab_test_items as 
        // SELECT row_number() OVER () AS id,a.* from(
        //     SELECT lmi.code as code, lmi.id as item_id,null as panel_id,lmi.client_id as client_id,lmi.name as test_name,lmc.title as category_name,
        //     lmi.price as test_amount,'ITEM' as test_check
        //     from lab_mst_items lmi
        //     left join lab_mst_categories lmc on lmc.id = lmi.lab_category_id
        //     where lmi.is_testable = true
        //     and lmi.is_active = true
        //     UNION 
        //     SELECT lp.code as code, null as item_id,lp.id as panel_id,lp.client_id as client_id,lp.name as test_name,lmc.title as category_name,
        //     lp.charge_amount as test_amount,'PANEL' as test_check
        //     from lab_panels lp
        //     left join lab_mst_categories lmc on lmc.id = lp.lab_category_id
        //     where lp.is_active = true
        //  )a
        // ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
