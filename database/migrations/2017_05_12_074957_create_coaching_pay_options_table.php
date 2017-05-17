<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoachingPayOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coaching_pay_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('coaching_id')->nullable();
            $table->integer('organization_id')->nullable();
            $table->float('price',2)->nullable();
            $table->float('discount',2)->nullable();
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
        Schema::drop('coaching_pay_options');
    }
}
