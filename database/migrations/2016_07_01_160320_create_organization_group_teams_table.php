<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationGroupTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_group_teams', function (Blueprint $table) {
            $table->unsignedInteger('organization_group_id');
            $table->foreign('organization_group_id')
                  ->references('id')
                  ->on('organization_groups')
                  ->onUpdate('CASCADE')
                  ->onDelete('CASCADE');

            $table->integer('team_id');
            $table->foreign('team_id')
                  ->references('id')
                  ->on('teams')
                  ->onUpdate('CASCADE')
                  ->onDelete('CASCADE');

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
        Schema::drop('organization_group_teams');
    }
}
