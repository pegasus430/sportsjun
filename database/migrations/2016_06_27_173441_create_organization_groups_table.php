<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

            $table->integer('manager_id');
            $table->foreign('manager_id')
                  ->references('id')
                  ->on('users')
                  ->onUpdate('CASCADE')
                  ->onDelete('CASCADE');

            $table->integer('organization_id');
            $table->foreign('organization_id')
                  ->references('id')
                  ->on('organization')
                  ->onUpdate('CASCADE')
                  ->onDelete('CASCADE');

            $table->string('logo');

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
        Schema::drop('organization_groups');
    }
}
