<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_list_id');
            $table->unsignedInteger('sku')->default(0);
            $table->string('title');
            $table->unsignedInteger('quantity'); // 数量
            $table->unsignedInteger('price'); // 单价
            $table->string('image')->nullable();
            $table->timestamps();
            $table->foreign('product_list_id')->references('id')->on('product_lists');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_items');
    }
}
