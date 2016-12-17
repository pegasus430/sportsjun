<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->indexed();
            $table->bigInteger('parent_id')->unsigned()->indexed();
            $table->text('title');
            $table->integer('template_id');
            $table->string('publish_state',20)->default('draft')->indexed();
            $table->dateTime('publish_date');
            $table->string('access_state',20)->default('all')->indexed();
            $table->string('linkname',200)->charset('utf8')->unique()->nullable()->indexed();
            $table->text('data')->nullable();

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
        Schema::drop('pages');
    }
}
