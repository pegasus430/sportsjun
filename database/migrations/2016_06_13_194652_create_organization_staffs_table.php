<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrganizationStaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('organization_staffs', function (Blueprint $table) {
        //     // $table->increments('id');

        //     // $table->integer('user_id');
        //     // $table->foreign('user_id')
        //     //       ->references('id')
        //     //       ->on('users')
        //     //       ->onUpdate('CASCADE')
        //     //       ->onDelete('CASCADE');

        //     // $table->integer('organization_id');
        //     // $table->foreign('organization_id')
        //     //       ->references('id')
        //     //       ->on('organization')
        //     //       ->onUpdate('CASCADE')
        //     //       ->onDelete('CASCADE');

        //     // $table->unsignedInteger('organization_role_id');
        //     // $table->foreign('organization_role_id')
        //     //       ->references('id')
        //     //       ->on('organization_roles')
        //     //       ->onUpdate('CASCADE')
        //     //       ->onDelete('CASCADE');

        //     // $table->boolean('status')->default(false);

        //     // $table->timestamps();

        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('organization_staffs');
    }
}
