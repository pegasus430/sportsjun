<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArcheryArrowStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archery_arrow_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('match_id');         
            $table->integer('user_id');
            $table->integer('round_number');
            $table->integer('round_id');
            $table->integer('arrow_1')->nullable();
            $table->integer('arrow_2')->nullable();
            $table->integer('arrow_3')->nullable();
            $table->integer('arrow_4')->nullable();
            $table->integer('arrow_5')->nullable();
            $table->integer('arrow_6')->nullable();
            $table->integer('arrow_7')->nullable();
            $table->integer('arrow_8')->nullable();
            $table->integer('arrow_9')->nullable();
            $table->integer('arrow_10')->nullable();
            $table->integer('total');
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
        Schema::drop('archery_arrow_stats');
    }
}
