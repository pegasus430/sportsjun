<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCoachingsTableAddPaymentOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coachings', function (Blueprint $table) {
            //
            $table->string('payment_method')->nullable();
            $table->integer('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coachings', function (Blueprint $table) {
            //
            $table->dropColumn('payment_method');
            $table->integer('user_id')->nullable();
        });
    }
}
