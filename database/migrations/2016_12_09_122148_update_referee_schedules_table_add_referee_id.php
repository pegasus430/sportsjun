<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRefereeSchedulesTableAddRefereeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('match_schedules_referees', function (Blueprint $table) {
            //
            $table->integer('referee_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('match_schedules_referees', function (Blueprint $table) {
            //
            $table->dropColumn('referee_id');
        });
    }
}
