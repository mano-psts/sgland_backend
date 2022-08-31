<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fault_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('level');
            $table->string('location');
            $table->string('title');
            $table->text('description');
            $table->text('Images');
            $table->integer('fault_category_id')->length(11);
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
        Schema::dropIfExists('fault_reports');
    }
};
