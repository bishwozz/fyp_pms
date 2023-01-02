 <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_mst_categories', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('client_id');
            $table->string('code',20);
            $table->string('title',200);
            $table->string('description',500)->nullable();
            $table->boolean('is_active')->nullable()->default(true);

            $table->timestamps();

            $table->unique('code','uq_lab_mst_categories_code');
            $table->index('title','idx_lab_mst_categories_title');

            $table->foreign('client_id','fk_lab_mst_categories_client_id')->references('id')->on('app_clients');
        });

        Schema::create('lab_mst_items', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('client_id');
            $table->string('code',20);
            $table->unsignedSmallInteger('lab_category_id');
            $table->string('name',200);
            $table->string('reference_from_value')->nullable();
            $table->string('reference_from_to')->nullable();
            $table->string('unit',50)->nullable();
            $table->float('price')->default(0);
            $table->string('description',500)->nullable();
            $table->boolean('is_testable')->nullable()->default(true);
            $table->boolean('is_taxable')->nullable()->default(false);
            $table->unsignedSmallInteger('result_field_type')->nullable();
            $table->json('result_field_options')->nullable();
            $table->float('tax_percentage')->nullable();
            $table->boolean('is_active')->nullable()->default(true);
            $table->unsignedSmallInteger('sample_id')->nullable();
            $table->unsignedSmallInteger('method_id')->nullable();
            $table->boolean('is_special_reference')->default(false);
            $table->string('special_reference')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->unique(['code','deleted_uq_code'],'uq_lab_mst_items_deleted_uq_code');
            $table->unique('code','uq_lab_mst_items_code');
            $table->unique(["name", "lab_category_id"],'uq_lab_mst_items_category_name');
            $table->index('name','idx_lab_mst_items_name');

            $table->foreign('client_id','fk_lab_mst_items_client_id')->references('id')->on('app_clients');
            $table->foreign('lab_category_id','fk_lab_mst_items_lab_category_id')->references('id')->on('lab_mst_categories');
            $table->foreign('sample_id','fk_lab_mst_items_sample_id')->references('id')->on('mst_lab_samples');
            $table->foreign('method_id','fk_lab_mst_items_method_id')->references('id')->on('mst_lab_methods');

        });
        Schema::create('lab_groups', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('client_id');
            $table->unsignedSmallInteger('lab_category_id');
            $table->string('code',20);
            $table->string('name',200);
            $table->double('charge_amount')->nullable();
            $table->boolean('is_active')->nullable()->default(true);

            $table->timestamps();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->index('name','idx_lab_groups_name');

            $table->foreign('lab_category_id','fk_lab_groups_lab_category_id')->references('id')->on('lab_mst_categories');
            $table->foreign('client_id','fk_lab_groups_client_id')->references('id')->on('app_clients');
        });
        Schema::create('lab_group_items', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('lab_item_id');
            $table->unsignedSmallInteger('lab_group_id');
            $table->timestamps();

            $table->foreign('lab_item_id','fk_lab_group_items_lab_item_id')->references('id')->on('lab_mst_items');
            $table->foreign('lab_group_id','fk_lab_group_items_lab_group_id')->references('id')->on('lab_groups');
        });

        Schema::create('lab_panels', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('client_id');

            $table->string('code',20);
            $table->string('name',200);
            $table->double('charge_amount')->nullable();
            $table->boolean('is_active')->nullable()->default(true);
            $table->unsignedSmallInteger('lab_category_id');
            $table->timestamps();

            $table->unsignedSmallInteger('created_by')->nullable();
            $table->unsignedSmallInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->unique(['id','deleted_uq_code'],'uq_lab_panels_id');
            $table->unique('code','uq_lab_panels_code');

            $table->foreign('client_id','fk_lab_panels_client_id')->references('id')->on('app_clients');
            $table->foreign('lab_category_id','fk_lab_panels_lab_category_id')->references('id')->on('lab_mst_categories');

        });

        Schema::create('lab_panel_groups_items', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('lab_panel_id');
            $table->unsignedSmallInteger('lab_group_id')->nullable();
            $table->unsignedSmallInteger('lab_item_id')->nullable();
            $table->timestamps();

            $table->foreign('lab_panel_id','fk_lab_panel_groups_lab_panel_id')->references('id')->on('lab_panels');
            $table->foreign('lab_group_id','fk_lab_panel_groups_lab_group_id')->references('id')->on('lab_groups');
            $table->foreign('lab_item_id','fk_lab_panel_groups_lab_item_id')->references('id')->on('lab_mst_items');
        });
        
        Schema::create('mst_payment_methods', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('code');
            $table->string('title',200);
            $table->boolean('is_active')->nullable()->default(true);
            
            $table->timestamps();
            $table->unsignedSmallInteger('created_by')->nullable();
            $table->unsignedSmallInteger('updated_by')->nullable();
            
            $table->index('title','idx_mst_payment_methods_title');
            
        });

        Schema::create('lab_bills', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('client_id');
            $table->unsignedSmallInteger('patient_id')->nullable();
            $table->string('customer_name',100);
            $table->string('address',150)->nullable();
            $table->string('age',10)->nullable();
            $table->string('gender',20)->nullable();
            $table->string('item_discount_type')->nullable();
            
            $table->string('bill_no')->nullable();
            $table->date('generated_date')->nullable();
            $table->string('generated_date_bs',10)->nullable();

            $table->boolean('is_paid')->default(true);
            $table->date('payment_date')->nullable();
            $table->string('payment_date_bs',10)->nullable();
            
            $table->unsignedSmallInteger('payment_method_id')->nullable();
            $table->string('transaction_number',100)->nullable();

            $table->float('total_gross_amount')->default(0);
            $table->string('total_discount_type')->nullable();
            $table->float('total_discount_value')->default(0);
            $table->float('total_discount_amount')->default(0);

            $table->float('total_tax_amount')->default(0);
            $table->float('total_net_amount')->default(0);
            $table->float('total_paid_amount')->default(0);
            $table->float('total_refund_amount')->default(0);

            $table->boolean('is_cancelled')->default(false);
            $table->datetime('cancelled_datetime')->nullable();
            $table->string('cancelled_reason',200)->nullable();
            $table->unsignedSmallInteger('referred_by');

            $table->unsignedSmallInteger('credit_approved_by')->nullable();
            $table->unsignedSmallInteger('discount_approved_by')->nullable();
            $table->unsignedSmallInteger('due_received_by')->nullable();
            $table->datetime('due_received_datetime')->nullable();

            $table->unsignedSmallInteger('bank_id')->nullable();
            $table->unsignedSmallInteger('card_id')->nullable();
            $table->string('cheque_no')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();
            $table->unsignedSmallInteger('created_by')->nullable();
            $table->unsignedSmallInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->unique(['id','deleted_uq_code'],'uq_lab_bills_id');
            $table->unique('bill_no','uq_lab_bills_bill_no');

            $table->foreign('patient_id','fk_lab_bills_patient_id')->references('id')->on('patients');
            $table->foreign('client_id','fk_lab_bills_client_id')->references('id')->on('app_clients');
            $table->foreign('payment_method_id','fk_lab_bills_payment_method_id')->references('id')->on('mst_payment_methods');
            
            $table->foreign('referred_by','fk_lab_patient_test_data_referred_by')->references('id')->on('mst_referrals');
            $table->foreign('credit_approved_by','fk_lab_patient_test_data_credit_approved_by')->references('id')->on('hr_mst_employees');
            $table->foreign('discount_approved_by','fk_lab_patient_test_data_discount_approved_by')->references('id')->on('hr_mst_employees');
            $table->foreign('due_received_by','fk_lab_patient_test_data_due_received_by')->references('id')->on('hr_mst_employees');
            $table->foreign('bank_id','fk_lab_patient_test_data_bank_id')->references('id')->on('mst_banks');
            
        });

        Schema::create('lab_bill_items', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('client_id');

            $table->unsignedSmallInteger('lab_bill_id');
            $table->unsignedSmallInteger('lab_panel_id')->nullable();
            $table->unsignedSmallInteger('lab_item_id')->nullable();
            $table->float('quantity')->default(0);
            $table->float('rate')->default(0);
            $table->float('discount')->default(0);
            $table->float('amount')->default(0);
            $table->float('tax')->default(0);
            $table->float('net_amount')->default(0);

            $table->timestamps();
            $table->unsignedSmallInteger('created_by')->nullable();
            $table->unsignedSmallInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->unique(['id','deleted_uq_code'],'uq_lab_bill_items_id');
            $table->foreign('lab_bill_id','fk_lab_bill_items_lab_bill_id')->references('id')->on('lab_bills');
            $table->foreign('client_id','fk_lab_bill_items_client_id')->references('id')->on('app_clients');
            $table->foreign('lab_panel_id','fk_lab_bill_items_lab_panel_id')->references('id')->on('lab_panels');
            $table->foreign('lab_item_id','fk_lab_bill_items_lab_item_id')->references('id')->on('lab_mst_items');

        });

        // Schema::table('lab_patient_test_results',function (Blueprint $table){
        //     $table->unsignedSmallInteger('bill_id');
        //     $table->foreign('bill_id','fk_lab_patient_test_results_bill_id')->references('id')->on('lab_bills');

        // });
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
