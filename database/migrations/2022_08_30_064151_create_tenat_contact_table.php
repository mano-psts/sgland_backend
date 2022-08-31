<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenatContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenat_contacts', function (Blueprint $table) {
            $table->id();
            $table->integer('tenat_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('work_email_address');
            $table->string('job_postion');
            $table->string('mobile_number');
            $table->string('office_phone_number');
            $table->timestamp('assess_start_date');
            $table->integer('office_unit_number');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenat_contact');
    }
}
