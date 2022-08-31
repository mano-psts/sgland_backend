<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSLTServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slt_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->time('from_time');
            $table->time('to_time');
            $table->integer('duration');
            $table->string('rate');
            $table->string('amount');
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
        Schema::dropIfExists('s_l_t_services');
    }
}
