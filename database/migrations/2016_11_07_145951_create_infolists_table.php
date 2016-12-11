<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfolistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infolists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('created_by');
            $table->boolean('active');
            $table->string('type',32);
            $table->string('image');
            $table->string('name');
            $table->text('description');
            $table->text('data');
            $table->integer('weight');
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
        Schema::drop('infolists');
    }
}
