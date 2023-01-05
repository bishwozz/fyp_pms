<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoreMasterTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_countries', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('code',20);
            $table->string('name',200);

            $table->timestamps();
            $table->unsignedSmallInteger('display_order')->nullable()->default(0);
            $table->unsignedSmallInteger('created_by')->nullable();
            $table->unsignedSmallInteger('updated_by')->nullable();


            $table->unique('code','uq_mst_country_code');
            $table->unique('name','uq_mst_country_name');

        });

        Schema::create('mst_fed_provinces', function (Blueprint $table) {
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


            $table->unique(['code','deleted_uq_code'],'uq_mst_fed_provinces_code');
            $table->unique(['name','deleted_uq_code'],'uq_mst_fed_provinces_name');

        });

        Schema::create('mst_fed_districts', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('province_id');
            $table->string('code',20);
            $table->string('name',200);

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->unique(['code','deleted_uq_code'],'uq_mst_fed_districts_code');
            $table->unique(['name','deleted_uq_code'],'uq_mst_fed_districts_name');
            $table->index('province_id','idx_mst_fed_districts_province_id');

            $table->foreign('province_id','fk_mst_fed_districts_province_id')->references('id')->on('mst_fed_provinces')->onDelete('cascade');

        });
        Schema::create('mst_fed_local_level_types', function (Blueprint $table) {
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

            $table->unique(['code','deleted_uq_code'],'uq_mst_fed_local_level_types_code');
            $table->unique(['name','deleted_uq_code'],'uq_mst_fed_local_level_types_name');

        });

        Schema::create('mst_fed_local_levels', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedSmallInteger('district_id');
            $table->string('code',20);
            $table->string('name',200);
            $table->unsignedSmallInteger('level_type_id');
            $table->string('gps_lat',20)->nullable();
            $table->string('gps_long',20)->nullable();
            $table->smallInteger('ward_count')->nullable();

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->unique(['code','deleted_uq_code'],'uq_mst_fed_local_levels_code');
            $table->foreign('district_id','fk_mst_fed_local_levels_district_id')->references('id')->on('mst_fed_districts')->onDelete('cascade');
            $table->foreign('level_type_id','fk_mst_fed_local_levels_level_type_id')->references('id')->on('mst_fed_local_level_types')->onDelete('cascade');

        });

        Schema::create('mst_nepali_months', function (Blueprint $table) {
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

            $table->unique(['code','deleted_uq_code'],'uq_mst_nepali_months_code');
            $table->unique(['name','deleted_uq_code'],'uq_mst_nepali_months_name');

        });
        Schema::create('mst_fiscal_years', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('code',20);
            $table->string('from_date_bs',10)->nullable();
            $table->date('from_date_ad')->nullable();
            $table->string('to_date_bs',10)->nullable();
            $table->date('to_date_ad')->nullable();

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->unique(['code','deleted_uq_code'],'uq_mst_fiscal_years_code');
            $table->unique(['from_date_bs','deleted_uq_code'],'uq_mst_fiscal_years_from_date_bs');
            $table->unique(['from_date_ad','deleted_uq_code'],'uq_mst_fiscal_years_from_date_ad');

        });

        Schema::create('mst_genders', function(Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('code',20);
            $table->string('name',200);
            $table->unsignedSmallInteger('display_order')->nullable()->default(0);

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->unique(['code','deleted_uq_code'],'uq_mst_genders_code');
            $table->unique(['name','deleted_uq_code'],'uq_mst_genders_name');
        });



        Schema::create('app_clients', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('code',20);
            $table->string('name',200);
            $table->unsignedSmallInteger('fed_local_level_id')->nullable();
            $table->string('admin_email',200)->nullable();
            $table->string('short_name',20)->nullable();
            $table->string('prefix_key',10)->nullable();
            $table->string('remarks',1000)->nullable();
            $table->boolean('is_active')->nullable()->default(true);
            
            $table->timestamps();
            $table->unsignedSmallInteger('created_by')->nullable();
            $table->unsignedSmallInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);
            
            $table->unique(['code','deleted_uq_code'],'uq_app_clients_code_deleted_uq_code');
            $table->unique(['name','deleted_uq_code'],'uq_app_clients_name_deleted_uq_code');
            
            $table->foreign('fed_local_level_id','fk_app_clients_fed_local_level_id')->references('id')->on('mst_fed_local_levels');
            $table->foreign('created_by','fk_app_clients_created_by')->references('id')->on('users');
            $table->foreign('updated_by','fk_app_clients_updated_by')->references('id')->on('users');
            $table->foreign('deleted_by','fk_app_clients_deleted_by')->references('id')->on('users');
            
        });
        Schema::table('users',function (Blueprint $table){
            $table->unsignedSmallInteger('client_id');
            $table->foreign('client_id','fk_users_client_id')->references('id')->on('app_clients');
        });
        Schema::create('app_settings', function(Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('client_id');
            $table->string('code',200);
            $table->string('office_name',200);
            $table->string('address_name',200);
            $table->string('phone',10)->nullable();
            $table->string('fax',100)->nullable();
            $table->string('email',100)->nullable();
            $table->string('letter_head_title_1',200)->nullable();
            $table->string('letter_head_title_2',200)->nullable();
            $table->string('letter_head_title_3',200)->nullable();
            $table->string('letter_head_title_4',200)->nullable();
            $table->unsignedSmallInteger('fiscal_year_id')->nullable();
            $table->string('client_logo')->nullable();
            $table->string('client_stamp')->nullable();
            $table->string('remarks',500)->nullable();
            $table->boolean('is_active')->nullable()->default(true);
            $table->string('registration_number')->nullable();
            $table->string('pan_vat_no',50)->nullable();

            $table->string('purchase_order_seq_key',20)->nullable();
            $table->string('bill_seq_key',20)->nullable();
            $table->string('order_seq_key',20)->nullable();
            $table->string('sample_seq_key',20)->nullable();

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->unique(['code','deleted_uq_code'],'uq_app_settings_code');
            $table->foreign('fiscal_year_id','fk_app_settings_fiscal_year_id')->references('id')->on('mst_fiscal_years')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('client_id','fk_app_settings_client_id')->references('id')->on('app_clients')->cascadeOnUpdate()->cascadeOnDelete();

        });

        Schema::create('mst_banks', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('code',20);
            $table->string('name',200);
            $table->timestamps();
            $table->unsignedSmallInteger('display_order')->nullable()->default(0);
            $table->unsignedSmallInteger('created_by')->nullable();
            $table->unsignedSmallInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_uq_code')->default(1);

            $table->unique(['code','deleted_uq_code'],'uq_mst_banks_code');

    
            $table->unique('name','uq_mst_banks_name');

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
