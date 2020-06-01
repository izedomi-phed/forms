<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReqStockRequisitionConsignmentDetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('req_stock_requisition_consignment_dets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('request_id');
            $table->string('stock_code')->nullable();
            $table->string('desc')->nullable();
            $table->string('unit_of_issue')->nullable();
            $table->string('qty_req_figure')->nullable();
            $table->string('qty_req_words')->nulluble();
            $table->string('qty_issued_figure')->nullable();
            $table->string('qty_issued_words')->nullable();
            $table->string('unit_price')->nullable();
            $table->string('value')->nullable();
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
        Schema::dropIfExists('req_stock_requisition_consignment_dets');
    }
}
