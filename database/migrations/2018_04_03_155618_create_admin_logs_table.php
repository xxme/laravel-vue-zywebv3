<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('obj_id');
            // 1 event 2 type 3 comment 4 user 5 Event commission 6 Event complete
            // 7 shopping list 8 Estimates 9 Repetition
            $table->unsignedTinyInteger('type');
            // 1 add 2 update 3 delete(soft)
            $table->unsignedTinyInteger('log_type');
            $table->string('content')->nullable();
            $table->text('details')->nullable();
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
        Schema::dropIfExists('admin_logs');
    }
}
