<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePmsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phr_mst_units', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('client_id');
            $table->string('code',20);
            $table->string('name_en',200);
            $table->string('name_lc',200);
            $table->timestamps();
            $table->boolean('is_active')->nullable()->default(true);

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->unique('code','uq_phr_mst_units_code');

            $table->foreign('client_id','fk_phr_mst_units_client_id')->references('id')->on('app_clients');
        });

        Schema::create('phr_mst_categories', function (Blueprint $table) { 
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('client_id');
            $table->string('code',20);
            $table->string('title_en',200);
            $table->string('title_lc',200);
            $table->string('description_en',500)->nullable();
            $table->string('description_lc',500)->nullable();
            $table->boolean('is_active')->nullable()->default(true);
            $table->timestamps();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->unique('code','uq_phr_mst_categories_code');
            $table->foreign('client_id','fk_phr_mst_categories_client_id')->references('id')->on('app_clients');

        });

        Schema::create('phr_mst_pharmaceuticals', function (Blueprint $table) { 
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('client_id');
            $table->string('code',20);
            $table->string('name',200);
            $table->string('address',300)->nullable();
            $table->string('email',200)->nullable();
            $table->string('contact_person',200)->nullable();
            $table->string('contact_number',50)->nullable();
            $table->string('website',200)->nullable();
            $table->boolean('is_active')->nullable()->default(true);
            $table->timestamps();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->unique('code','uq_phr_mst_pharmaceuticals_code');
            $table->foreign('client_id','fk_phr_mst_pharmaceuticals_client_id')->references('id')->on('app_clients');

        });

        Schema::create('phr_mst_suppliers', function (Blueprint $table) { 
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('client_id');
            $table->string('code',20);
            $table->string('name',200);
            $table->string('supplier_logo')->nullable();
            $table->string('description',500)->nullable();
            $table->string('address',300)->nullable();
            $table->string('email',200)->nullable();
            $table->string('contact_person',200)->nullable();
            $table->string('phone_number',50)->nullable();
            $table->string('website',200)->nullable();
            $table->boolean('is_active')->nullable()->default(true);
            $table->timestamps();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->unique('code','uq_phr_mst_suppliers_code');
            $table->foreign('client_id','fk_pphr_mst_suppliers_client_id')->references('id')->on('app_clients');

        });

        Schema::create('phr_items', function (Blueprint $table) { 
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('client_id');
            $table->string('code',20);
            $table->unsignedSmallInteger('supplier_id')->nullable();
            $table->unsignedSmallInteger('category_id')->nullable();
            $table->unsignedSmallInteger('pharmaceutical_id')->nullable();
            $table->string('brand_name',200);
            $table->string('generic_name',200)->nullable();

            $table->string('strength',300)->nullable();
            $table->string('form',300)->nullable();
            $table->string('disease_group_coverage',300)->nullable();

            $table->unsignedSmallInteger('current_stock');
            $table->unsignedSmallInteger('stock_unit_id');
            $table->unsignedSmallInteger('stock_alert_minimun')->default(0);
            
            $table->boolean('is_banned')->default(false);
            $table->boolean('is_deprecated')->default(false);
            $table->boolean('is_free')->default(false);

            $table->string('description')->nullable();
            $table->boolean('is_active')->nullable()->default(true);
            $table->timestamps();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->unique('code','uq_phr_items_code');
            $table->index('brand_name','idx_phr_items_brand_name');
            $table->index('generic_name','idx_phr_items_generic_name');


            $table->foreign('supplier_id','fk_phr_items_supplier_id')->references('id')->on('phr_mst_suppliers');
            $table->foreign('category_id','fk_phr_items_category_id')->references('id')->on('phr_mst_categories');
            $table->foreign('pharmaceutical_id','fk_phr_items_pharmaceutical_id')->references('id')->on('phr_mst_pharmaceuticals');
            $table->foreign('stock_unit_id','fk_phr_items_group_unit_id')->references('id')->on('phr_mst_units');
            $table->foreign('client_id','fk_phr_items_client_id')->references('id')->on('app_clients');

        });

        Schema::create('phr_item_units', function (Blueprint $table) { 
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('client_id');
            $table->unsignedSmallInteger('item_id')->nullable();
            $table->unsignedSmallInteger('unit_id')->nullable();
            $table->double('price');
            $table->boolean('is_active')->nullable()->default(true);
            $table->timestamps();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->foreign('item_id','fk_phr_item_units_item+id')->references('id')->on('phr_items');
            $table->foreign('unit_id','fk_phr_item_units_unit_id')->references('id')->on('phr_mst_units');
            $table->foreign('client_id','fk_phr_item_units_client_id')->references('id')->on('app_clients');
        });
        Schema::create('phr_mst_generic_names', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('client_id');
            $table->string('code',20);
            $table->string('name',500);
            $table->boolean('is_active')->nullable()->default(true);
            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

    
            $table->unique('code','uq_phr_mst_generic_names_code');
            $table->foreign('client_id','fk_phr_mst_generic_names_client_id')->references('id')->on('app_clients');

        });
        Schema::create('phr_item_stocks', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('client_id');
            $table->unsignedSmallInteger('item_id');
            $table->string('batch_number');
            $table->unsignedSmallInteger('quantity');
            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->foreign('client_id','fk_phr_item_stocks_client_id')->references('id')->on('app_clients');
            $table->foreign('item_id','fk_phr_item_stocks_item_id')->references('id')->on('phr_items');
        });

        Schema::create('mst_brands', function (Blueprint $table) {

            $table->increments('id');
            $table->string('code');
            $table->unsignedSmallInteger('client_id');
            $table->string('name_en');
            $table->string('name_lc')->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->unsignedInteger('deleted_uq_code')->default(1);


            $table->unique(['code', 'deleted_uq_code','client_id'], 'uq_mst_brands_code_deleted_uq_code');
            $table->unique(['name_lc', 'deleted_uq_code','client_id'], 'uq_mst_brands_name_lc_deleted_uq_code');
            $table->unique(['name_en', 'deleted_uq_code','client_id'], 'uq_mst_brands_name_en_deleted_uq_code');
            $table->foreign('client_id','fk_mst_brands_client_id')->references('id')->on('app_clients');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phr_mst_categories');
        Schema::dropIfExists('phr_mst_pharmaceuticals');
        Schema::dropIfExists('phr_mst_suppliers');
        Schema::dropIfExists('phr_items');
        Schema::dropIfExists('phr_item_units');
        Schema::dropIfExists('phrmstgenericname');
        Schema::dropIfExists('phr_item_stocks');
        Schema::dropIfExists('phr_mst_units');
        Schema::dropIfExists('mst_brands');

    }
}
