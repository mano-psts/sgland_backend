<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(true);
            $table->string('last_name')->nullable(true);
            $table->string('email')->unique();
            $table->tinyInteger('verified')->default(0);
            $table->integer('role_id')->length(11);
            $table->string('password')->nullable(true);
            $table->time('validate_time')->nullable(true);
            $table->integer('otp')->length(6)->nullable(true);         
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
        Schema::dropIfExists('customers');
    }
}
