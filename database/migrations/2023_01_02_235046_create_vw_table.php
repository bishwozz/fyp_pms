<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVwTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS vw_lab_test_items");
        DB::statement("DROP VIEW IF EXISTS vw_items");
        
        // DB::statement("CREATE VIEW vw_items as 
        // SELECT row_number() OVER () AS id,a.* from(
        //     SELECT  lmi.id as item_id,lmi.client_id as client_id,lmi.code,
        //     lmi.price as amount
        //     from phr_items lmi
        //     Where lmi.is_active = true
        //  )a
        // ");
    }


    // SELECT row_number() OVER () AS id,a.* from(
    //     SELECT lmi.current_stock as qty, lmi.id as item_id,lmi.client_id as client_id,lmi.name,
    //     lmi.price as amount
    //     from phr_items lmi
    //     Where lmi.is_active = true
    //     AND lmi.current_stock > 0
    //  )a
    // ");

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS vw_lab_test_items");
        DB::statement("DROP VIEW IF EXISTS vw_items");
    }
}
