<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_setups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gateway_id');
            $table->string('setup_name',50);
            $table->decimal('setup_value', 5, 2);
            $table->enum('status', ['active', 'inactive']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('payment_setups');
    }
}
