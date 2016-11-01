<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentGateWays extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_gate_ways', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->integer('country_id')->nullable();
            $table->enum('status', ['on', 'off'])->default('on');
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
        Schema::drop('payment_gate_ways');
    }
}
