<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookedRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booked_rooms', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('no_of_attendees');
            $table->date('date');
            $table->time('time');
            $table->string('location');
            $table->integer('room_id')->length(11);
            $table->string('payment_method');
            $table->string('compay_name');
            $table->integer('Tenat_id')->length(11);
            $table->string('fees');
            $table->string('amount');
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
        Schema::dropIfExists('booked_rooms');
    }
}
