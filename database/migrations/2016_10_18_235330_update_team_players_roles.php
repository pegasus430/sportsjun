<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTeamPlayersRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `team_players` CHANGE COLUMN `role` `role` ENUM('owner','player','manager','coach','captain','keeper','vice-captain','physio') NULL DEFAULT 'player' AFTER `user_id`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
