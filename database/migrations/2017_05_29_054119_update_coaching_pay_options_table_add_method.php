<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCoachingPayOptionsTableAddMethod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coaching_pay_options', function (Blueprint $table) {
            //
            $table->string('payment_method')->nullable();
            $table->integer('subscription_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coaching_pay_options', function (Blueprint $table) {
            //
            $table->dropColumn('payment_method');
            $table->dropColumn('subscription_id');
        });
    }
}
