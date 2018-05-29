<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('event_id')->nullable();
            $table->unsignedTinyInteger('yytype')->default(1);
            $table->string('nickname')->nullable();
            $table->string('tel');
            $table->date('hopedate')->nullable();
            $table->unsignedTinyInteger('apm')->nullable();
            $table->text('from_info');
            $table->text('to_info');
            $table->text('items')->nullable();
            $table->text('images')->nullable();
            $table->string('other_service')->nullable();
            $table->string('manager_note')->nullable();
            $table->string('partner')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('offers');
    }
}
