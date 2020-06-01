<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReqStockRequisitionIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('req_stock_requisition_issues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('account_id');
            $table->integer('form_id');
            $table->string('staff_id');
            $table->string('name');
            $table->string('requested_by');
            $table->string('cost_center');
            $table->string('department');
            $table->string('budget_no');
            $table->string('destination');
            $table->string('requisition_purpose');
            $table->string('project_type');
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
        Schema::dropIfExists('req_stock_requisition_issues');
    }
}
