<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReqSagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('req_sages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('account_id')->nullable();
            $table->integer('form_id')->nullable();
            $table->string('staff_id')->nullable();
            $table->string('name')->nullable();
            $table->string('designation')->nullable();
            $table->string('staff_type')->nullable();
            $table->string('job_desc')->nullable();
            $table->string('hr_module')->nullable();
            $table->string('finance_module')->nullable();
            $table->string('access_level')->nullable();
            $table->string('location')->nullable();
            $table->string('department')->nullable();
            $table->string('termination_date')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('work_no')->nullable();
            $table->string('hod_name')->nullable();
            $table->string('hod_email')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('req_sages');
    }
}
