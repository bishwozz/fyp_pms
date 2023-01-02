<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableForMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_lab_samples', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('code',20);
            $table->string('name',200);

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->unique(['code','deleted_uq_code'],'uq_mst_lab_samples_code');
            $table->unique(['name','deleted_uq_code'],'uq_mst_lab_samples_name');
        });

        Schema::create('mst_lab_methods', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('code',20);
            $table->string('name',200);

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->unique(['code','deleted_uq_code'],'uq_mst_lab_methods_code');
            $table->unique(['name','deleted_uq_code'],'uq_mst_lab_methods_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mst_lab_samples');
        Schema::dropIfExists('mst_lab_methods');
    }
}
