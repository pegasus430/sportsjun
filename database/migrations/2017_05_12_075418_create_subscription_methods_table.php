<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type',['installment','monthly','other'])->default('monthly')->nullable();
            $table->string('title')->nullable();
            $table->integer('duration')->nullable();
            $table->text('details')->nullable();
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
        Schema::drop('subscription_methods');
    }
}
