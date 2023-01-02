<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_referrals', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('code',20);
            $table->unsignedSmallInteger('client_id');
            $table->string('name',200);
            $table->string('referral_type');
            $table->string('contact_person');
            $table->string('phone',10)->nullable();
            $table->string('email',200)->nullable();
            $table->string('address',200)->nullable();
            $table->boolean('is_active')->default(false);
            $table->string('discount_percentage');

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedSmallInteger('deleted_by')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->unsignedInteger('deleted_uq_code')->nullable()->default(1);

            $table->unique(['code','deleted_uq_code'],'uq_mst_referrals_code');
            $table->unique(['name','deleted_uq_code'],'uq_mst_referrals_name');

            $table->foreign('client_id','fk_mst_referrals_client_id')->references('id')->on('app_clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mst_referrals');
    }
}
