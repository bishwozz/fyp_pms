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
            $table->string('name_lc',200);
            $table->unsignedInteger('count')->nullable();
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


        Schema::create('phr_items', function (Blueprint $table) { 
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('client_id');
            $table->string('code',20);
            $table->unsignedSmallInteger('supplier_id')->nullable();
            $table->unsignedSmallInteger('category_id')->nullable();
            $table->unsignedSmallInteger('pharmaceutical_id')->nullable();
            $table->unsignedSmallInteger('brand_id');
            $table->unsignedSmallInteger('generic_id')->nullable();
            $table->float('price')->nullable();

            $table->unsignedSmallInteger('unit_id');
            $table->unsignedSmallInteger('stock_alert_minimun')->default(0);
            
            $table->boolean('is_deprecated')->default(false);
            $table->boolean('is_free')->default(false);
            $table->boolean('is_active')->nullable()->default(true);
            $table->string('description')->nullable();
            $table->timestamps();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->unique('code','uq_phr_items_code');
            $table->index('brand_id','idx_phr_items_brand_name');
            $table->index('generic_id','idx_phr_items_generic_name');

            $table->foreign('supplier_id','fk_phr_items_supplier_id')->references('id')->on('phr_mst_suppliers');
            $table->foreign('category_id','fk_phr_items_category_id')->references('id')->on('phr_mst_categories');
            $table->foreign('pharmaceutical_id','fk_phr_items_pharmaceutical_id')->references('id')->on('phr_mst_pharmaceuticals');
            $table->foreign('unit_id','fk_phr_items_group_unit_id')->references('id')->on('phr_mst_units');
            $table->foreign('client_id','fk_phr_items_client_id')->references('id')->on('app_clients');

            $table->foreign('brand_id','fk_phr_items_brand_id')->references('id')->on('mst_brands');
            $table->foreign('generic_id','fk_phr_items_generic_id')->references('id')->on('phr_mst_generic_names');

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

            $table->foreign('client_id')->references('id')->on('app_clients')->cascadeOnDelete()->cascadeOnUpdate();


            $table->foreign('requested_store_id')->references('id')->on('mst_stores')
                   ->onDelete('restrict')->onUpdate('cascade');

            $table->foreign('status_id')->references('id')->on('sup_status')
                   ->onDelete('restrict')->onUpdate('cascade');


            $table->dateTime('deleted_at')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->unsignedInteger('deleted_uq_code')->default(1);
     });

     Schema::create('purchase_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('po_id');
            $table->unsignedSmallInteger('store_id')->nullable();


            $table->integer('purchase_qty')->nullable();
            $table->integer('free_qty')->nullable();
            $table->integer('total_qty')->nullable();
            $table->float('discount')->nullable();
            $table->float('purchase_price')->nullable();
            $table->float('sales_price')->nullable();
            $table->float('item_amount')->nullable();
            $table->float('tax_vat')->nullable();
            $table->unsignedSmallInteger('sup_org_id')->nullable();

            $table->unsignedBigInteger('items_id')->nullable();

            $table->unsignedBigInteger('discount_mode_id')->nullable();


            $table->timestamps();

            $table->foreign('po_id')->references('id')->on('purchase_order_details')
                   ->onDelete('restrict')->onUpdate('cascade');






            $table->foreign('sup_org_id')->references('id')->on('sup_organizations')->cascadeOnDelete()->cascadeOnUpdate();

            $table->foreign('discount_mode_id')->references('id')->on('mst_discount_modes')
                   ->onDelete('restrict')->onUpdate('cascade');

            $table->foreign('items_id')->references('id')->on('mst_items')
                   ->onDelete('restrict')->onUpdate('cascade');

            $table->foreign('store_id')->references('id')->on('mst_stores')->cascadeOnDelete()->cascadeOnUpdate();




            $table->dateTime('deleted_at')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->unsignedInteger('deleted_uq_code')->default(1);
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
                    $table->unsignedBigInteger('grn_id')->nullable();
                    $table->unsignedSmallInteger('sup_org_id')->nullable();
                    $table->unsignedBigInteger('store_id')->nullable();
                    $table->unsignedBigInteger('supplier_id');
                    $table->unsignedBigInteger('return_reason_id');
                    $table->string('return_no')->nullable();
                    $table->unsignedBigInteger('requested_store_id')->nullable();

                    $table->string('return_date')->nullable();
                    $table->unsignedBigInteger('approved_by')->nullable();
                    $table->unsignedBigInteger('status_id');

                    $table->timestamps();
                    $table->unsignedInteger('created_by')->nullable();
                    $table->unsignedInteger('updated_by')->nullable();
                    $table->unsignedInteger('deleted_by')->nullable();
                    $table->unsignedInteger('deleted_uq_code')->default(1);
                    $table->dateTime('deleted_at')->nullable();
                    
                    $table->foreign('sup_org_id')->references('id')->on('sup_organizations')->cascadeOnDelete()->cascadeOnUpdate();
                    $table->foreign('store_id')->references('id')->on('mst_stores')->onDelete('restrict')->onUpdate('cascade');
                    $table->foreign('supplier_id')->references('id')->on('mst_suppliers')->onDelete('restrict')->onUpdate('cascade');
                    $table->foreign('return_reason_id')->references('id')->on('return_reasons')->onDelete('restrict')->onUpdate('cascade');
                    $table->foreign('requested_store_id')->references('id')->on('mst_stores')->onDelete('restrict')->onUpdate('cascade');
                    $table->foreign('grn_id')->references('id')->on('grns')->onDelete('restrict')->onUpdate('cascade');
                    $table->foreign('status_id')->references('id')->on('sup_status')->onDelete('restrict')->onUpdate('cascade');
                    $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
                    $table->foreign('approved_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
                    
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
                    
                    $table->unsignedInteger('purchase_return_id')->nullable();
                    $table->unsignedBigInteger('discount_mode_id')->nullable();
                    $table->unsignedBigInteger('mst_items_id')->nullable();
                    $table->unsignedSmallInteger('sup_org_id')->nullable();
                    $table->unsignedSmallInteger('store_id')->nullable();


                    $table->timestamps();
                    $table->unsignedInteger('created_by')->nullable();
                    $table->unsignedInteger('updated_by')->nullable();
                    $table->unsignedInteger('deleted_by')->nullable();
                    $table->dateTime('deleted_at')->nullable();
                    $table->unsignedInteger('deleted_uq_code')->default(1);

                    $table->foreign('discount_mode_id')->references('id')->on('mst_discount_modes')->onDelete('restrict')->onUpdate('cascade');
                    $table->foreign('mst_items_id')->references('id')->on('mst_items')->onDelete('restrict')->onUpdate('cascade');
                    $table->foreign('sup_org_id')->references('id')->on('sup_organizations')->cascadeOnDelete()->cascadeOnUpdate();
                    $table->foreign('purchase_return_id')->references('id')->on('purchase_returns')->cascadeOnDelete()->cascadeOnUpdate();
                    $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
                    $table->foreign('store_id')->references('id')->on('mst_stores')->cascadeOnDelete()->cascadeOnUpdate();

                    
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
