<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReqStoreRecievedAdvicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('req_store_recieved_advices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('account_id');
            $table->integer('form_id');
            $table->string('staff_id');
            $table->string('name');

            $table->string('cost_center');
            $table->string('ref_to_others');
            $table->string('local_purchase_no');
            $table->string('indent_no');
            $table->string('consignment_note_no');
            $table->string('item_no');
            $table->string('supplier_name');
            $table->string('supplier_invoice_no');
            $table->string('case_markings');
            $table->string('date_recieved');
            $table->string('invoice_value');
            $table->string('inward_infreight');
            $table->string('custom_duty');
            $table->string('vat');
            $table->string('status');
            $table->string('total');
            $table->string('remarks');
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
        Schema::dropIfExists('req_store_recieved_advices');
    }
}
