<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_blocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->indexed();
            $table->bigInteger('parent_id')->unsigned()->indexed();
            $table->bigInteger('container_id')->unsigned()->nullable()->indexed();
            $table->bigInteger('page_id')->unsigned()->indexed();
            $table->text('title');
            $table->longText('data');
            $table->string('type');
            $table->string('publish_state',20)->default('draft')->indexed();
            $table->dateTime('publish_date');
            $table->string('access_state',20)->default('all')->indexed();

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
        Schema::drop('page_blocks');
    }
}
