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
            $table->integer('user_id');
            $table->integer('order_id')->nullable();  // 見積もりid
            $table->integer('shopping_id')->nullable();  // 购物单id
            $table->integer('agent_id')->nullable();  // 推广人id
            $table->dateTime('event_date');
            $table->tinyInteger('apm');
            $table->string('types');
            $table->string('truck_ids');
            $table->string('quotation')->nullable();  // 报价
            $table->text('images')->nullable();
            // from / fromfloor / frombuildingtype / to / tofloor / tobuildingtype / stime(开始时间)
            $table->text('details')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->softDeletes();
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
        Schema::dropIfExists('events');
    }
}
