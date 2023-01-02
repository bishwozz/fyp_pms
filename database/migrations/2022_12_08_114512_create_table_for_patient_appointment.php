<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableForPatientAppointment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_appointments', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('client_id');
            $table->unsignedSmallInteger('department_id')->nullable();
            $table->unsignedSmallInteger('doctor_id')->nullable();
            $table->string('full_name',100);
            $table->unsignedSmallInteger('gender_id');
            $table->unsignedSmallInteger('age')->nullable();
            $table->string('city',50)->nullable();
            $table->string('cell_phone',50);
            $table->string('email',50)->nullable();
            $table->string('remarks',500)->nullable();
            $table->date('appointment_date');
            $table->string('appointment_date_bs',10)->nullable();
            $table->unsignedSmallInteger('appointment_status')->default(0);
            $table->unsignedSmallInteger('approved_by')->nullable();


            $table->timestamps();
            $table->unsignedSmallInteger('created_by')->nullable();
            $table->unsignedSmallInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);


            $table->foreign('client_id','fk_patient_appointments_client_id')->references('id')->on('app_clients');
            $table->foreign('gender_id','fk_patient_appointments_gender_id')->references('id')->on('mst_genders');
            $table->foreign('department_id','fk_patient_appointments_department_id')->references('id')->on('hr_mst_departments');
            $table->foreign('doctor_id','fk_patient_appointments_doctor_id')->references('id')->on('hr_mst_employees');
            $table->foreign('approved_by','fk_patient_appointments_approved_by')->references('id')->on('hr_mst_employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_appointments');
    }
}
