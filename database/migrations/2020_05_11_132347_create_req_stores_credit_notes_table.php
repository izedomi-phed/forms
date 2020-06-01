<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReqStoresCreditNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('req_stores_credit_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('account_id');
            $table->integer('form_id');
            $table->string('staff_id');
            $table->string('name');
            $table->string('account_code');
            $table->string('capital_exp_app_no');
            $table->string('rechargeable_works_order_no');
            $table->string('job_returned');
            $table->string('status');
            $table->string('total');
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
        Schema::dropIfExists('req_stores_credit_notes');
    }
}
