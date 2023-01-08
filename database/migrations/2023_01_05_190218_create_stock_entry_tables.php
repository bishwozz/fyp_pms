<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockEntryTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('entry_date_ad')->nullable();
            $table->timestamp('entry_date_bs')->nullable();
            $table->text('comments')->nullable();
            $table->unsignedFloat('gross_total')->nullable();
            $table->unsignedFloat('total_discount')->nullable();
            $table->unsignedFloat('flat_discount')->nullable();
            $table->unsignedFloat('taxable_amount')->nullable();
            $table->unsignedFloat('tax_total')->nullable();
            $table->unsignedFloat('net_amount')->nullable();
            $table->foreignId('client_id')->nullable()->constrained('app_clients')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('sup_status_id')->nullable()->constrained('sup_status')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('stock_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('available_total_qty')->nullable();
            $table->unsignedBigInteger('add_qty')->nullable();
            $table->unsignedBigInteger('total_qty')->nullable();
            $table->tinyText('batch_no')->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->unsignedInteger('free_item')->nullable();
            $table->unsignedFloat('discount')->nullable();
            $table->unsignedFloat('unit_cost_price')->nullable();
            $table->unsignedFloat('unit_sales_price')->nullable();
            $table->unsignedInteger('tax_vat')->nullable();
            $table->unsignedFloat('item_total')->nullable();
            $table->foreignId('client_id')->nullable()->constrained('app_clients')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('stock_id')->nullable()->constrained('stock_entries')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('item_id')->nullable()->constrained('mst_items')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_entries');
        Schema::dropIfExists('stock_items');
    }
}
