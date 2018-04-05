<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            // 1 manager 2 staff 3 agent 4 agent applying 5 user 
            $table->unsignedInteger('group_id')->default(5);
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('profileimg')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('group_id')->references('id')->on('user_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
