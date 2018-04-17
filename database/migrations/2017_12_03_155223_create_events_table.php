<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('order_id')->nullable();  // 見積もりid
            $table->unsignedInteger('shopping_id')->nullable();  // 购物单id
            $table->unsignedInteger('agent_id')->nullable();  // 推广人id
            $table->unsignedInteger('amount')->nullable();  // 报价
            $table->dateTime('event_date');
            $table->unsignedTinyInteger('apm');
            $table->string('types');
            $table->string('truck_ids');
            $table->text('images')->nullable();
            // from / fromfloor / frombuildingtype / to / tofloor / tobuildingtype / stime(开始时间)
            $table->text('details')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
