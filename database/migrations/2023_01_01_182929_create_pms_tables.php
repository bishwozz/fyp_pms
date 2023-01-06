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
        Schema::create('phr_mst_units', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('client_id');
            $table->string('code',20);
            $table->string('name_en',200);
            $table->string('name_lc',200)->nullable();
            $table->unsignedInteger('count')->nullable();
            $table->timestamps();
            $table->boolean('is_active')->nullable()->default(true);

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_uq_code')->default(1);
            $table->unique(['code','deleted_uq_code'],'uq_mst_brands_code');



            $table->unique('code','uq_phr_mst_units_code');

            $table->foreign('client_id','fk_phr_mst_units_client_id')->references('id')->on('app_clients');
        });

        Schema::create('phr_mst_categories', function (Blueprint $table) { 
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('client_id');
            $table->string('code',20);
            $table->string('title_en',200);
            $table->string('title_lc',200)->nullable();
            $table->string('description',500)->nullable();
            $table->boolean('is_active')->nullable()->default(true);
            $table->timestamps();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_uq_code')->default(1);
            $table->unique(['code','deleted_uq_code'],'uq_phr_mst_categories_code');



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
            $table->unsignedInteger('deleted_uq_code')->default(1);
            $table->unique(['code','deleted_uq_code'],'uq_hr_phr_mst_pharmaceuticals_code');
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
            $table->unsignedInteger('deleted_uq_code')->default(1);
            $table->unique(['code','deleted_uq_code'],'uq_hr_phr_mst_suppliers_code');

            

            $table->unique('code','uq_phr_mst_suppliers_code');
            $table->foreign('client_id','fk_pphr_mst_suppliers_client_id')->references('id')->on('app_clients');

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
            $table->unsignedInteger('deleted_uq_code')->default(1);
            $table->unique(['code','deleted_uq_code'],'uq_phr_mst_generic_names_code');
            $table->foreign('client_id','fk_phr_mst_generic_names_client_id')->references('id')->on('app_clients');

        });


        Schema::create('phr_items', function (Blueprint $table) { 
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('client_id');
            $table->string('code',20);
            $table->unsignedSmallInteger('supplier_id')->nullable();
            $table->unsignedSmallInteger('category_id')->nullable();
            $table->unsignedSmallInteger('pharmaceutical_id')->nullable();
            $table->unsignedSmallInteger('brand_id');
            $table->string('name',100)->nullable();
            $table->string('tax_vat')->nullable();

            $table->unsignedSmallInteger('unit_id');
            $table->unsignedSmallInteger('stock_alert_minimun')->default(0);
            
            $table->boolean('is_deprecated')->default(false);
            $table->boolean('is_free')->default(false);
            $table->boolean('is_active')->nullable()->default(true);
            $table->string('description')->nullable();
            $table->timestamps();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->index('brand_id','idx_phr_items_brand_name');
            $table->unsignedInteger('deleted_uq_code')->default(1);
            $table->unique(['code','deleted_uq_code'],'uq_phr_items_code');

            $table->foreign('supplier_id','fk_phr_items_supplier_id')->references('id')->on('phr_mst_suppliers');
            $table->foreign('category_id','fk_phr_items_category_id')->references('id')->on('phr_mst_categories');
            $table->foreign('pharmaceutical_id','fk_phr_items_pharmaceutical_id')->references('id')->on('phr_mst_pharmaceuticals');
            $table->foreign('unit_id','fk_phr_items_group_unit_id')->references('id')->on('phr_mst_units');
            $table->foreign('client_id','fk_phr_items_client_id')->references('id')->on('app_clients');
            $table->foreign('brand_id','fk_phr_items_brand_id')->references('id')->on('mst_brands');

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
            $table->unsignedInteger('deleted_uq_code')->default(1);
            $table->unique(['id','deleted_uq_code'],'uq_phr_item_units_id');


            $table->foreign('item_id','fk_phr_item_units_item+id')->references('id')->on('phr_items');
            $table->foreign('unit_id','fk_phr_item_units_unit_id')->references('id')->on('phr_mst_units');
            $table->foreign('client_id','fk_phr_item_units_client_id')->references('id')->on('app_clients');
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
            $table->unsignedInteger('deleted_uq_code')->default(1);
            $table->unique(['id','deleted_uq_code'],'uq_phr_item_stocks_id');



            $table->foreign('client_id','fk_phr_item_stocks_client_id')->references('id')->on('app_clients');
            $table->foreign('item_id','fk_phr_item_stocks_item_id')->references('id')->on('phr_items');
        });

        Schema::create('sup_status', function (Blueprint $table) {

            $table->increments('id');
            $table->string('code');
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


            $table->unique(['code','deleted_uq_code'],'uq_sup_status_code_deleted_uq_code');
            $table->unique(['name_lc','deleted_uq_code'],'uq_sup_status_name_lc_deleted_uq_code');
            $table->unique(['name_en','deleted_uq_code'],'uq_sup_status_name_en_deleted_uq_code');
        });

        ////v purchase
        Schema::create('purchase_order_details', function (Blueprint $table) {

            $table->increments('id');
            $table->string('purchase_order_num')->nullable();
            $table->string('po_date')->nullable();
            $table->string('expected_delivery')->nullable();
            $table->string('approved_by')->nullable();
            $table->float('gross_amt')->nullable();
            $table->float('discount_amt')->nullable();
            $table->float('tax_amt')->nullable();
            $table->float('other_charges')->nullable();
            $table->float('net_amt')->nullable();
            $table->string('comments')->nullable();

            $table->timestamps();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('status_id');
            $table->unsignedSmallInteger('client_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('phr_mst_suppliers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('client_id')->references('id')->on('app_clients')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('status_id')->references('id')->on('sup_status')
                   ->onDelete('restrict')->onUpdate('cascade');
            $table->dateTime('deleted_at')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->unsignedInteger('deleted_uq_code')->default(1);
            $table->unique(['id','deleted_uq_code'],'uq_purchase_order_details_id');

     });

     Schema::create('mst_discount_modes', function (Blueprint $table) {

        $table->increments('id');
        $table->string('code',20);
        $table->string('name_en',100);
        $table->string('name_lc',100);
        $table->string('description',1000)->nullable();
        $table->boolean('is_active')->default(true);
        $table->boolean('is_super_data')->default(false)->nullable();

        $table->unsignedInteger('created_by')->nullable();
        $table->unsignedInteger('updated_by')->nullable();
        $table->unsignedInteger('deleted_by')->nullable();
        $table->boolean('is_deleted')->nullable();
        $table->timestamp('deleted_at')->nullable();
        $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);
        $table->timestamps();
        $table->unique(['code','deleted_uq_code'],'uq_hr_mst_discount_modes_code');

        
    });

     Schema::create('purchase_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('client_id')->nullable();
            $table->unsignedBigInteger('po_id');
            $table->integer('purchase_qty')->nullable();
            $table->integer('free_qty')->nullable();
            $table->integer('total_qty')->nullable();
            $table->float('discount')->nullable();
            $table->float('purchase_price')->nullable();
            $table->float('sales_price')->nullable();
            $table->float('item_amount')->nullable();
            $table->float('tax_vat')->nullable();
            $table->unsignedBigInteger('items_id')->nullable();
            $table->unsignedBigInteger('discount_mode_id')->nullable();
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('app_clients')->cascadeOnDelete()->cascadeOnUpdate();

            $table->foreign('discount_mode_id')->references('id')->on('mst_discount_modes')
                ->onDelete('restrict')->onUpdate('cascade');

            $table->foreign('items_id')->references('id')->on('phr_items')
                ->onDelete('restrict')->onUpdate('cascade');

            $table->foreign('po_id')->references('id')->on('purchase_order_details')
                ->onDelete('restrict')->onUpdate('cascade');


            $table->dateTime('deleted_at')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->unsignedInteger('deleted_uq_code')->default(1);
            $table->unique(['id','deleted_uq_code'],'uq_hr_purchase_items_code_id');

            
     });

        //purchase return migration

        Schema::create('purchase_returns', function (Blueprint $table) {

        $table->increments('id');
        $table->boolean('return_type')->nullable();
        $table->float('gross_amt')->nullable();
        $table->float('discount_amt')->nullable();
        $table->float('taxable_amount')->nullable();
        $table->float('tax_amt')->nullable();
        $table->float('other_charges')->nullable();
        $table->float('net_amt')->nullable();
        $table->string('comments')->nullable();
        $table->unsignedSmallInteger('client_id')->nullable();
        $table->unsignedBigInteger('supplier_id');
        $table->unsignedBigInteger('return_reason_id');
        $table->string('return_no')->nullable();
        $table->string('return_date')->nullable();
        $table->unsignedBigInteger('approved_by')->nullable();
        $table->unsignedBigInteger('status_id');

        $table->timestamps();
        $table->unsignedInteger('created_by')->nullable();
        $table->unsignedInteger('updated_by')->nullable();
        $table->unsignedInteger('deleted_by')->nullable();
        $table->unsignedInteger('deleted_uq_code')->default(1);
        $table->dateTime('deleted_at')->nullable();
        
        $table->foreign('client_id')->references('id')->on('app_clients')->cascadeOnDelete()->cascadeOnUpdate();
        $table->foreign('supplier_id')->references('id')->on('phr_mst_suppliers')->onDelete('restrict')->onUpdate('cascade');
        $table->foreign('status_id')->references('id')->on('sup_status')->onDelete('restrict')->onUpdate('cascade');
        $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        $table->foreign('approved_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        $table->unique(['id','deleted_uq_code'],'uq_purchase_returns_id');
        
    });
            //End of purchase return migration


            //return purchase items migration starts
        Schema::create('purchase_return_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_qty')->nullable();
            $table->integer('free_qty')->nullable();
            $table->integer('return_qty')->nullable();
            $table->integer('total_qty')->nullable();
            $table->float('discount')->nullable();
            $table->float('purchase_price')->nullable();
            $table->float('sales_price')->nullable();
            $table->float('item_amount')->nullable();
            $table->integer('batch_qty')->nullable();
            $table->string('batch_no')->nullable();
            $table->float('tax_vat')->nullable();
            
            $table->text('purchase_return',500)->nullable();
            $table->unsignedBigInteger('discount_mode_id')->nullable();
            $table->unsignedBigInteger('phr_items_id')->nullable();
            $table->unsignedSmallInteger('client_id')->nullable();


            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->unsignedInteger('deleted_uq_code')->default(1);

            $table->foreign('discount_mode_id')->references('id')->on('mst_discount_modes')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('phr_items_id')->references('id')->on('phr_items')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('client_id')->references('id')->on('app_clients')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->unique(['id','deleted_uq_code'],'uq_purchase_return_items_id');
        
        });

            Schema::create('mst_sequences', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('name_en');
            $table->string('name_lc')->nullable();
            $table->integer('sequence_type');
            $table->string('sequence_code');
            $table->integer('starting_no')->nullable();
            $table->boolean('is_consumed')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('client_id')->nullable();
            $table->unsignedSmallInteger('created_by')->nullable();
            $table->unsignedSmallInteger('updated_by')->nullable();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->unsignedInteger('deleted_uq_code')->default(1);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('app_clients')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unique(['code','deleted_uq_code'],'uq_mst_sequences_code');
        
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
