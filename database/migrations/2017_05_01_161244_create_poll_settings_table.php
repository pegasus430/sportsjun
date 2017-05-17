<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poll_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('organization_id')->unique()->nullable();
            $table->enum('poll_result', ['result','percentage','hide']);
            $table->enum('block_votes',['disabled','cookie','all']);
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
        Schema::drop('poll_settings');
    }
}
