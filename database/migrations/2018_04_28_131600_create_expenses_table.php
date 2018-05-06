<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('event_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('finalprice')->default(0); //收款
            $table->unsignedInteger('expenditure')->default(0); //支出
            $table->unsignedInteger('zctingche')->default(0);
            $table->unsignedInteger('zccanyin')->default(0);
            $table->unsignedInteger('zcgaosu')->default(0);
            $table->unsignedInteger('zcjiayou')->default(0);
            $table->unsignedInteger('zcmaihuo')->default(0);
            $table->unsignedInteger('zcother')->default(0);
            $table->unsignedInteger('fxrmb')->default(0);
            $table->unsignedInteger('fxjpy')->default(0);
            $table->unsignedTinyInteger('status')->default(1); //1 入金していない 2 入金済み
            $table->timestamps();
            $table->foreign('event_id')->references('id')->on('events');
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
        Schema::dropIfExists('expenses');
    }
}
