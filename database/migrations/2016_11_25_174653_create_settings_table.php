<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',200);
            $table->string('description',500);
            
        });

        DB::table('settings')->insert(
        array(
            'name' => 'disclaimer_text',
            'description' => 'I accept to pay additional 6% for all Sportsjun efforts.Sportsjun Partners and affiliate Marketing efforts.'
        )
    );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('settings');
    }
}
