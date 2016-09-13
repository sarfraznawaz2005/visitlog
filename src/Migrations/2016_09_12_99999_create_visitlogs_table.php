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
            $table->string('ip');
            $table->string('browser');
            $table->string('os');
            $table->string('user_id');
            $table->string('user_name');
            $table->string('country_code');
            $table->string('country_name');
            $table->string('region_name');
            $table->string('city');
            $table->string('zip_code');
            $table->string('time_zone');
            $table->string('latitude');
            $table->string('longitude');
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
