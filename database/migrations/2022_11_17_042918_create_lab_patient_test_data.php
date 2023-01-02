<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabPatientTestData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('lab_patient_test_data', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('client_id');

            $table->unsignedSmallInteger('patient_id')->nullable();
            $table->unsignedSmallInteger('bill_id');
            $table->unsignedSmallInteger('category_id');

            $table->unsignedSmallInteger('collection_status')->default(0);
            $table->timestamp('collection_datetime')->nullable();
            
            $table->unsignedSmallInteger('reported_status')->default(0);
            $table->timestamp('reported_datetime')->nullable();

            $table->unsignedSmallInteger('dispatch_status')->default(0);
            $table->timestamp('dispatched_datetime')->nullable();

            $table->unsignedSmallInteger('lab_technician_id')->nullable();
            $table->unsignedSmallInteger('approve_status')->default(0);
            $table->timestamp('approved_datetime')->nullable();

            $table->unsignedSmallInteger('doctor_id')->nullable();

            $table->string('order_no',20)->nullable();
            $table->string('sample_no',20)->nullable();
            
            $table->timestamps();
            $table->text('comment')->nullable();

            $table->unsignedSmallInteger('created_by')->nullable();
            $table->unsignedSmallInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->foreign('patient_id','fk_lab_patient_test_data_patient_id')->references('id')->on('patients');
            $table->foreign('client_id','fk_lab_patient_test_data_client_id')->references('id')->on('app_clients');
        
            $table->foreign('lab_technician_id','fk_lab_patient_test_data_lab_technician_id')->references('id')->on('hr_mst_employees');
            $table->foreign('doctor_id','fk_lab_patient_test_data_doctor_id')->references('id')->on('hr_mst_employees');
            $table->foreign('bill_id','fk_lab_patient_test_data_bill_id')->references('id')->on('lab_bills');

        });

        Schema::create('lab_patient_test_results', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('patient_test_data_id')->nullable();

            $table->unsignedSmallInteger('lab_panel_id')->nullable();
            $table->unsignedSmallInteger('lab_group_id')->nullable();
            $table->unsignedSmallInteger('lab_item_id')->nullable();
            $table->string('flag')->nullable();
            
            $table->string('result_value')->nullable();
            $table->text('methodology')->nullable();
            $table->string('barcode',100)->nullable();
            $table->timestamps();
          

            $table->foreign('lab_panel_id','fk_lab_patient_test_results_lab_panel_id')->references('id')->on('lab_panels');
            $table->foreign('lab_group_id','fk_lab_patient_test_results_lab_group_id')->references('id')->on('lab_groups');
            $table->foreign('lab_item_id','fk_lab_patient_test_results_lab_item_id')->references('id')->on('lab_mst_items');
            $table->foreign('patient_test_data_id','fk_lab_patient_test_results_patient_test_data_id')->references('id')->on('lab_patient_test_data');
        });

           Schema::table('lab_mst_categories',function (Blueprint $table){
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lab_patient_test_data');
    }
}
