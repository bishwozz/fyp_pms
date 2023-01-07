<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
        /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('item_qty_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('client_id')->nullable();
            $table->unsignedSmallInteger('store_id')->nullable();
            $table->unsignedSmallInteger('item_id')->nullable();
            $table->integer('item_qty')->nullable();

            $table->foreign('item_id')->references('id')->on('mst_items')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('client_id')->references('id')->on('app_clients')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedSmallInteger('created_by');
            $table->unsignedSmallInteger('updated_by')->nullable();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->unsignedInteger('deleted_uq_code')->default(1);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
        Schema::create('batch_qty_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('client_id')->nullable();
            $table->unsignedSmallInteger('item_id')->nullable();
            $table->tinyText('batch_no')->nullable();
            $table->string('batch_from')->nullable();
            $table->integer('batch_qty')->nullable();
            $table->float('batch_price')->nullable();

            $table->foreign('item_id')->references('id')->on('mst_items')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('client_id')->references('id')->on('app_clients')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedSmallInteger('created_by');
            $table->unsignedSmallInteger('updated_by')->nullable();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->unsignedInteger('deleted_uq_code')->default(1);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });


        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('client_id');
            $table->string('bill_no')->nullable();
            $table->string('return_bill_no')->nullable();
            $table->integer('bill_type')->nullable();
            $table->string('full_name')->nullable();
            $table->smallInteger('gender_id')->nullable();
            $table->integer('age')->nullable();
            $table->string('address', 200)->nullable();
            $table->string('contact_number')->nullable();
            $table->string('pan_vat')->nullable();
            $table->string('company_name')->nullable();
            $table->string('bill_date_bs', 10)->nullable();
            $table->date('bill_date_ad')->nullable();
            $table->smallInteger('discount_type')->nullable();
            $table->float('discount')->nullable();
            $table->string('remarks')->nullable();
            $table->string('payment_type')->nullable();
            $table->float('receipt_amt')->nullable();
            $table->float('gross_amt')->nullable();
            $table->float('discount_amt')->nullable();
            $table->float('taxable_amt')->nullable();
            $table->float('total_tax_vat')->nullable();
            $table->float('net_amt')->nullable();
            $table->float('paid_amt')->nullable();
            $table->float('refund')->nullable();
            $table->unsignedSmallInteger('bank_id')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('cheque_number')->nullable();
            $table->string('cheque_date')->nullable();
            $table->string('ac_holder_name')->nullable();
            $table->string('cheque_upload')->nullable();
            $table->float('due_amt')->nullable();
            $table->boolean('is_return')->default(false);
            $table->date('transaction_date_ad')->nullable();
            $table->unsignedSmallInteger('return_reason_id')->nullable();
            $table->unsignedSmallInteger('due_approver_id')->nullable();
            $table->unsignedSmallInteger('store_id')->nullable();
            $table->unsignedSmallInteger('status_id')->nullable();
            $table->unsignedSmallInteger('discount_approver_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->unsignedInteger('deleted_uq_code')->default(1);
            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('gender_id')
            ->references('id')
            ->on('mst_genders')
            ->onUpdate('cascade')
            ->onDelete('restrict');
            
            $table->foreign('due_approver_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('client_id')->references('id')->on('app_clients')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('bank_id')->references('id')->on('mst_banks')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('status_id')->references('id')->on('sup_status')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('discount_approver_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });


        // Schema::create('sales',fuction(Blue))

        Schema::create('sales_items', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedSmallInteger('sales_id')->nullable();
            $table->unsignedSmallInteger('item_id')->nullable();
            $table->unsignedSmallInteger('unit_id')->nullable();
            $table->unsignedSmallInteger('store_id')->nullable();

            $table->float('item_price')->nullable();
            $table->float('tax_vat')->nullable();
            $table->float('item_discount')->nullable();
            $table->float('item_total')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->unsignedInteger('deleted_uq_code')->default(1);
            $table->softDeletes();
            $table->timestamps();

            
            $table->foreign('item_id')->references('id')->on('mst_items')->cascadeOnDelete()->cascadeOnDelete();
            $table->foreign('sales_id')->references('id')->on('sales')->cascadeOnDelete()->cascadeOnDelete();
            $table->integer('total_qty')->nullable();
            $table->integer('return_qty')->nullable();
            $table->string('batch_no')->nullable();
            $table->integer('batch_qty')->nullable();

            $table->unsignedSmallInteger('item_qty_detail_id')->nullable();
            $table->unsignedSmallInteger('batch_qty_detail_id')->nullable();

            $table->foreign('item_qty_detail_id')->references('id')->on('item_qty_detail')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('batch_qty_detail_id')->references('id')->on('batch_qty_detail')->cascadeOnDelete()->cascadeOnUpdate();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_qty_detail');
        Schema::dropIfExists('batch_qty_detail');

        Schema::dropIfExists('sales');
        Schema::dropIfExists('sales_items');
        // Schema::dropIfExists('sales_items_details');
    }
}
