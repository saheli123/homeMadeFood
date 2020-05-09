<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDishGallery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('dish_gallery', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image');
            $table->bigInteger('dish_id');
            $table->timestamps();
            $table->foreign('dish_id')->references('id')->on('food_items');
});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dish_gallery');
    }
}
