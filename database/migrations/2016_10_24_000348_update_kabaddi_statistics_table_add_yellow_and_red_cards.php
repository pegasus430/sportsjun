<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateKabaddiStatisticsTableAddYellowAndRedCards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kabaddi_statistic', function (Blueprint $table) {
            //
            $table->integer('yellow_card');
            $table->integer('red_card');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kabaddi_statistic', function (Blueprint $table) {
            //
        });
    }
}
