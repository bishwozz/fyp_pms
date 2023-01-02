<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('client_id');
            $table->string('patient_no',20);
            $table->string('name',100);
            $table->unsignedSmallInteger('gender_id');
            $table->unsignedSmallInteger('blood_group_id')->nullable();
            $table->date('date_of_birth');
            $table->string('date_of_birth_bs',10)->nullable();
            $table->unsignedSmallInteger('age')->nullable();
            $table->unsignedSmallInteger('age_unit')->nullable();
            $table->unsignedSmallInteger('id_type')->default(0);
            $table->string('citizenship_no',100)->nullable();
            $table->unsignedSmallInteger('country_id')->nullable();
            $table->unsignedSmallInteger('province_id')->nullable();
            $table->unsignedSmallInteger('district_id')->nullable();
            $table->unsignedSmallInteger('local_level_id')->nullable();
            $table->unsignedSmallInteger('ward_no')->nullable();
            $table->string('street_address',300);
            $table->string('cell_phone',50)->nullable();
            $table->string('email',250)->nullable();
            $table->boolean('has_insurance')->nullable()->default(false);
            $table->string('photo_name',200)->nullable();
            $table->string('remarks',500)->nullable();
            $table->string('passport_no',32)->nullable();
            $table->string('voter_no',32)->nullable();
            $table->string('national_id_no',32)->nullable();
            $table->string('nationality',100)->nullable();
            $table->unsignedSmallInteger('patient_type')->nullable();
            $table->unsignedInteger('patient_status')->nullable()->default(0);
            $table->boolean('is_emergency')->default(false);
            $table->date('registered_date')->nullable();
            $table->string('registered_date_bs',10)->nullable();
            $table->boolean('is_referred')->default(false);
            $table->unsignedSmallInteger('hospital_id')->nullable();
            $table->string('referrer_doctor_name',200)->nullable();
            $table->string('icmr_no',200)->nullable();
            $table->unsignedSmallInteger('salutation_id')->nullable();
            $table->unsignedSmallInteger('marital_status')->nullable();

            $table->timestamps();
            $table->unsignedSmallInteger('created_by')->nullable();
            $table->unsignedSmallInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->unique(['id','deleted_uq_code'],'uq_patients_id');
            $table->unique('patient_no','uq_patients_patient_no');
            $table->index('cell_phone','idx_patients_cell_phone');

            $table->foreign('client_id','fk_patients_client_id')->references('id')->on('app_clients');
            $table->foreign('province_id','fk_patients_province_id')->references('id')->on('mst_fed_provinces');
            $table->foreign('district_id','fk_patients_district_id')->references('id')->on('mst_fed_districts');
            $table->foreign('local_level_id','fk_patients_local_level_id')->references('id')->on('mst_fed_local_levels');
            $table->foreign('blood_group_id','fk_patients_blood_group_id')->references('id')->on('mst_blood_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
}
