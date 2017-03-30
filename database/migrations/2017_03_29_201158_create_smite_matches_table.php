<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmiteMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smite_matches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('match_id');
            $table->foreign('match_id')->references('id')->on('match_schedules');
            $table->enum('match_status', ['started', 'finished', 'canceled']);
            $table->text('lobby_name');
            $table->text('lobby_password');
            $table->text('match_statistic')->nullable();
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
        Schema::drop('smite_matches');
    }
}
