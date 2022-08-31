<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParkingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parkings', function (Blueprint $table) {
            $table->id();
            $table->string('billing_period');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('vehicle_type');
            $table->string('vehicle_number');
            $table->string('Iu_number');
            $table->string('supprting_documents');
            $table->string('images');
            $table->integer('building_id')->length(11);
            $table->integer('Tenat_id')->length(11);
            $table->string('company_name');
            $table->string('parking_rate');
            $table->string('amount');
            $table->tinyInteger('status')->default(0);
            $table->integer('ticket_id')->length(11);
            $table->string('payment_method');
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
        Schema::dropIfExists('parkings');
    }
}
