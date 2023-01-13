<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtraTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_item_mst_supplier', function (Blueprint $table) {
            $table->foreignId('item_id')->nullable()->constrained('mst_items')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('mst_suppliers')->cascadeOnUpdate()->cascadeOnDelete();
        });
        Schema::create('stock_items_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('stock_item_id')->nullable();;
            $table->unsignedInteger('sales_item_id')->nullable();
            $table->unsignedInteger('item_id');
            $table->unsignedSmallInteger('client_id')->nullable();

            $table->tinyText('barcode_details')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreign('stock_item_id')->references('id')->on('stock_items')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('sales_item_id')->references('id')->on('sales_items');
            $table->foreign('client_id')->references('id')->on('app_clients')->cascadeOnDelete()->cascadeOnUpdate();

        });
        Schema::table('users', function (Blueprint $table) {
            $table->integer('phone')->nullable();
            $table->boolean('is_discount_approver')->default(false);
            $table->boolean('is_due_approver')->default(false);
            $table->boolean('is_stock_approver')->default(false);
            $table->boolean('is_po_approver')->default(false);
            $table->boolean('is_active')->default(TRUE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mst_item_mst_supplier');
    }
}
