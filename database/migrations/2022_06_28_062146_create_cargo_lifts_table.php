<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCargoLiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cargo_lifts', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('reasons');
            $table->string('in_charger_name');
            $table->string('office_number');
            $table->string('mobile_number');
            $table->string('contractor_company');
            $table->integer('tenar_id')->length(11);
            $table->tinyInteger('status')->default(0);
            $table->integer('ticket_id')->length(11);
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
        Schema::dropIfExists('cargo_lifts');
    }
}
