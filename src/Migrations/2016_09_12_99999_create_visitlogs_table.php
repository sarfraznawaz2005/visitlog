<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateVisitLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitlogs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip')->default('0.0.0.0');
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->string('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('country_code')->nullable();
            $table->string('country_name')->nullable();
            $table->string('region_name')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('time_zone')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
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
        Schema::drop('visitlogs');
    }
}
