<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cart_id',50);
            $table->string('payment_firstname',50);
            $table->string('payment_address',100);
            $table->string('payment_country',50);
            $table->string('payment_state',50);
            $table->string('payment_city',50);
            $table->string('payment_zipcode',50);
            $table->string('payment_phone',50);
            $table->string('mihpayid',100);
            $table->string('status',50);
            $table->double('amount',10,2);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('payment_details');
    }
}
