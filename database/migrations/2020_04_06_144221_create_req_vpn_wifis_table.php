<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReqVpnWifisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('req_vpn_wifis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('account_id');
            $table->integer('form_id');
            $table->string('form_type');
            $table->integer('staff_id');
            $table->string('staff_name');
            $table->string('staff_designation');
            $table->string('requester_fullname');
            $table->string('requester_company');
            $table->string('date_of_request');
            $table->string('date_of_access');
            $table->string('duration_of_access');
            $table->string('status');           
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
        Schema::dropIfExists('req_vpn_wifis');
    }
}
