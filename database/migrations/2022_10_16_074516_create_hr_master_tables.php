<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrMasterTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('hr_mst_employees', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('client_id');
            $table->unsignedInteger('emp_no');
            $table->unsignedInteger('salutation_id');
            $table->string('full_name',250);
            $table->unsignedSmallInteger('gender_id');
            $table->string('date_of_birth_bs',10);
            $table->date('date_of_birth_ad');
            $table->string('qualification',200)->nullable();
            $table->unsignedSmallInteger('department_id');
            $table->unsignedSmallInteger('sub_department_id');
            $table->string('address',200)->nullable();
            $table->boolean('is_other_country')->default(0);
            $table->unsignedSmallInteger('country_id')->nullable();
            $table->unsignedSmallInteger('province_id')->nullable();
            $table->unsignedSmallInteger('district_id')->nullable();
            $table->unsignedSmallInteger('local_level_id')->nullable();
            $table->string('ward_no',10)->nullable();
            $table->string('mobile',10)->nullable();
            $table->string('email',100)->nullable();
            $table->boolean('is_credit_approver')->default(false);
            $table->boolean('is_discount_approver')->default(false);
            $table->boolean('is_result_approver')->default(false);

            $table->boolean('allow_user_login')->default(false);
            $table->unsignedSmallInteger('role_id')->nullable();

            $table->boolean('is_active')->default(true);
            $table->string('signature')->nullable();
            $table->string('photo_name')->nullable();
            $table->text('document')->nullable();
            $table->string('nmc_nhpc_number',32)->nullable();

            $table->timestamps();
            $table->unsignedSmallInteger('display_order')->nullable()->default(0);
            $table->unsignedSmallInteger('created_by')->nullable();
            $table->unsignedSmallInteger('updated_by')->nullable();

            $table->unique('emp_no','uq_hr_mst_employees_code');
            $table->index('emp_no','idx_hr_employees_emp_no');
            $table->index('full_name','idx_hr_employees_full_name');

            $table->foreign('client_id','fk_hr_mst_employees_client_id')->references('id')->on('app_clients');
            $table->foreign('gender_id','fk_hr_mst_employees_gender_id')->references('id')->on('mst_genders');
            $table->foreign('country_id','fk_hr_mst_employees_country_id')->references('id')->on('mst_countries');
            $table->foreign('province_id','fk_hr_mst_employees_province_id')->references('id')->on('mst_fed_provinces');
            $table->foreign('district_id','fk_hr_mst_employees_district_id')->references('id')->on('mst_fed_districts');
            $table->foreign('local_level_id','fk_hr_mst_employees_local_level_id')->references('id')->on('mst_fed_local_levels');
            $table->foreign('role_id','fk_hr_mst_employees_role_id')->references('id')->on('roles');

            $table->foreign('created_by','fk_hr_mst_employees_created_by')->references('id')->on('users');
            $table->foreign('updated_by','fk_hr_mst_employees_updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_uq_code')->default(1);
        });

        Schema::table('users',function (Blueprint $table){
            $table->unsignedSmallInteger('employee_id')->nullable();
            $table->foreign('employee_id','fk_users_employee_id')->references('id')->on('hr_mst_employees')->onDelete('cascade');
        });

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
