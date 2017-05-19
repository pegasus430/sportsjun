<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoachingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coachings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('organization_id')->nullable();
            $table->string('title')->nullable();
            $table->text('details')->nullable();
            $table->integer('staff_id')->nullable();
            $table->integer('sports_id')->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->integer('number_of_players')->nullable();
            $table->enum('parental',[0,1])->default('0')->nullable();
            $table->enum('status', [0,1])->default('0')->nullable();
            $table->string('image')->nullable();
            $table->string('image_url')->nullable();
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
        Schema::drop('coachings');
    }
}
