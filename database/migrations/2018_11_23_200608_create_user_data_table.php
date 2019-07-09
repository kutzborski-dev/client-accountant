<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_data', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned()->nullable(false);
            $table->string('company', 100)->nullable(false);
            $table->string('title', 50)->nullable(false);
            $table->string('contact', 50)->nullable(false);
            $table->string('street', 50)->nullable(false);
            $table->string('housenumber', 25)->nullable(false);
            $table->string('city', 50)->nullable(false);
            $table->string('postcode', 50)->nullable(false);
            $table->string('logo', 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_data');
    }
}
