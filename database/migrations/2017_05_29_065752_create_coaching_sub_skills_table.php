<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoachingSubSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coaching_sub_skills', function (Blueprint $table) {
          // Schema::create('coaching_skills', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('coaching_id')->nullable();
            $table->integer('sports_id')->nullable();
            $table->string('title')->nullable();
            $table->integer('coaching_skill_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
       // });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('coaching_sub_skills');
    }
}
