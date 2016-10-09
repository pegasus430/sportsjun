<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SelectedHalfOrQuarter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('match_schedules', function (Blueprint $table) {
            //
            $table->tinyInteger('selected_half_or_quarter')->default(1)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('match_schedules', function (Blueprint $table) {
            //
            $table->dropColumn('selected_half_or_quarter');
        });
    }
}
