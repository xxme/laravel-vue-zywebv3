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
            $table->unsignedInteger('product_list_id')->nullable();  // 购物单id
            $table->string('partner')->nullable();  // 推广人id
            $table->unsignedInteger('amount')->nullable();  // 报价
            $table->unsignedTinyInteger('total')->nullable();  // 总量
            $table->date('event_date');
            $table->unsignedTinyInteger('apm');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->text('types');
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
