<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentOrgGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('tournament_org_groups', function (Blueprint $table) {
        //     // $table->integer('tournament_parent_id');
        //     // //$table->foreign('tournament_parent_id')
        //     // //      ->references('id')
        //     // //      ->on('trounament_parent')
        //     // //      ->onUpdate('CASCADE')
        //     // //      ->onDelete('CASCADE');

        //     // $table->unsignedInteger('organization_group_id');
        //     // $table->foreign('organization_group_id')
        //     //       ->references('id')
        //     //       ->on('organization_groups')
        //     //       ->onUpdate('CASCADE')
        //     //       ->onDelete('CASCADE');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tournament_org_groups');
    }
}
