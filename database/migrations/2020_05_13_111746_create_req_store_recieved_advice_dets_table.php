<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReqStoreRecievedAdviceDetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('req_store_recieved_advice_dets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('request_id')->nullable();
            $table->string('vocab')->nullable();
            $table->string('desc')->nullable();
            $table->string('unit')->nullable();
            $table->string('qty')->nullable();
            $table->string('price')->nullable();
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
        Schema::dropIfExists('req_store_recieved_advice_dets');
    }
}
