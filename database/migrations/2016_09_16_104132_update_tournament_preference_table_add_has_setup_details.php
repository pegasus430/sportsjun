<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTournamentPreferenceTableAddHasSetupDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tournament_match_preferences', function (Blueprint $table) {
            //
            $table->enum('has_setup_details',[0,1])->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tournament_match_preferences', function (Blueprint $table) {
            //
        });
    }
}
