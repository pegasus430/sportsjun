<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateArcheryRoundsAddTotalAndRoundNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('archery_rounds', function (Blueprint $table) {
            //
            $table->integer('round_number');
            $table->integer('total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('archery_rounds', function (Blueprint $table) {
            //
            $table->dropColumn('round_number');
            $table->dropColumn('total');
        });
    }
}
