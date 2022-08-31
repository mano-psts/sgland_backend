<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenats', function (Blueprint $table) {
            $table->id();
            $table->string('company_full_name');
            $table->string('company_email_address');
            $table->string('office_phone_number');
            $table->integer('reception_unit_number');
            $table->text('levels');
            $table->text('unit');
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
        Schema::dropIfExists('tenats');
    }
}
