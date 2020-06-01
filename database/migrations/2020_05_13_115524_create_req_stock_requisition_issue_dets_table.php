<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReqStockRequisitionIssueDetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('req_stock_requisition_issue_dets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('request_id')->nullable();
            $table->string('code_no')->nullable();
            $table->string('desc')->nullable();
            $table->string('qty_req')->nullable();
            $table->string('qty_issued')->nullable();
            $table->string('unit_price')->nullable();
            $table->string('total_value')->nullable();
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
        Schema::dropIfExists('req_stock_requisition_issue_dets');
    }
}
