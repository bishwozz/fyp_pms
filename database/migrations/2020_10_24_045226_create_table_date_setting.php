<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDateSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('date_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date_ad');
            $table->string('date_bs');
            $table->integer('year_bs');
            $table->integer('month_bs');
            $table->string('days_bs');
            $table->integer('year_ad');
            $table->integer('month_ad');
            $table->string('days_ad');            
            $table->timestamps(); 
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('date_settings');
    }
}
