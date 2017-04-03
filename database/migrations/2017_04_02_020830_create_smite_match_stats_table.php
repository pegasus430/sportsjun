<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmiteMatchStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smite_match_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('match_id');
            $table->integer('smite_match_id');
            $table->integer('final_level');
            $table->integer('kills');
            $table->integer('deaths');
            $table->integer('assists');
            $table->integer('gold_earned');
            $table->integer('gpm');
            $table->integer('magical_damage_done');
            $table->integer('physical_damage_done');
            $table->integer('healing');
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
        Schema::drop('smite_match_stats');
    }
}
