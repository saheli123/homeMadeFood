<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
   Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('customer_id');
            $table->string('billing_address')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_pincode')->nullable();
            $table->string('billing_phone')->nullable();
            $table->float('billing_total');
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
        Schema::create('orderProduct', function (Blueprint $table) {

            $table->bigInteger('order_id');
            $table->bigInteger('product_id')->nullable();
            $table->float('price')->nullable();
            $table->bigInteger('quantity')->nullable();

            $table->timestamps();

            $table->primary('order_id','product_id');
	    $table->foreign('order_id')->references('id')->on('orders');
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('orderProduct');
    }
}
