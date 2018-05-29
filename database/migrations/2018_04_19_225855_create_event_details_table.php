<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('event_id');
            $table->text('trucks')->nullable();
            $table->text('carefully')->nullable();
            $table->text('aboutgoods')->nullable();
            $table->text('images')->nullable();
            $table->string('from_address')->nullable();
            $table->unsignedTinyInteger('from_elevator')->nullable();
            $table->unsignedTinyInteger('from_floor')->nullable();
            $table->string('to_address')->nullable();
            $table->unsignedTinyInteger('to_elevator')->nullable();
            $table->unsignedTinyInteger('to_floor')->nullable();
            $table->string('phone')->nullable();
            $table->string('wechat')->nullable();
            $table->foreign('event_id')->references('id')->on('events');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_details');
    }
}
