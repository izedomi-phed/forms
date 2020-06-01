<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReqStockRequisitionConsignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('req_stock_requisition_consignments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('account_id');
            $table->integer('form_id');
            $table->string('staff_id');
            $table->string('name');
            $table->string('req_store');
            $table->string('issue_store');
            $table->string('lorry_no');
            $table->string('location');
            $table->string('driver_name');
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
        Schema::dropIfExists('req_stock_requisition_consignments');
    }
}
