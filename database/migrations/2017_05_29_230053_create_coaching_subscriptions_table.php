<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoachingSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coaching_subscriptions', function (Blueprint $table) {
           $table->increments('id');
            $table->integer('coaching_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('player_id')->nullable();
            $table->enum('status',['paid','unpaid','active','inactive'])->default('unpaid')->nullable();
            $table->integer('subscription_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('coaching_subscriptions');
    }
}
