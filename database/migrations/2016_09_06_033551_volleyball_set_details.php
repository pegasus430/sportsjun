<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VolleyballSetDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('volleyball_set_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tournament_id')->nullable();
            $table->integer('match_id');
            $table->integer('team_id');
            $table->integer('set_number');
            $table->integer('server_id');
            $table->integer('serve_round');
            $table->integer('serve_got_at');
            $table->boolean('point_won');
            $table->integer('total_serve_points');
            $table->integer('serve_lost_at');
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
        Schema::drop('volleyball_set_details');
    }
}
