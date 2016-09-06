<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VolleyballScore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('volleyball_score', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tournament_id')->nullable();
            $table->integer('match_id');
            $table->integer('team_id');
            $table->string('team_name');
            $table->integer('set1');
            $table->integer('set2');
            $table->integer('set3');
            $table->integer('set4');
            $table->integer('set5');
            $table->boolean('isactive');
            $table->boolean('won_toss')->nullable();
            $table->enum('elected', ['receive', 'serve'])->nullable();

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
        Schema::drop('volleyball_score');
    }
}
