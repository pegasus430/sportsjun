<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArcheryStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archery_statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('events');
            $table->integer('user_id');
            $table->integer('team_id');
            $table->integer('third');
            $table->integer('second');
            $table->integer('first');
            $table->string('percent');
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
        Schema::drop('archery_statistics');
    }
}
