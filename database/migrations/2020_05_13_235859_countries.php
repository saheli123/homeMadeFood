<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Countries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('countries', function (Blueprint $table) {
            // `id` int(11) NOT NULL AUTO_INCREMENT,
            // `sortname` varchar(3) NOT NULL,
            // `name` varchar(150) NOT NULL,
            // `phonecode` int(11) NOT NULL,
            // PRIMARY KEY (`id`)
            $table->increments('id');
            $table->string("sortname");
            $table->string("name");
            $table->integer("phonecode");
           
        });
        Schema::create('cities', function (Blueprint $table) {
            // CREATE TABLE IF NOT EXISTS `cities` (
            //     `id` int(11) NOT NULL AUTO_INCREMENT,
            //     `name` varchar(30) NOT NULL,
            //     `state_id` int(11) NOT NULL,
            $table->increments('id');
            $table->string("name");
            $table->integer("state_id");
           
        });
        Schema::create('states', function (Blueprint $table) {
         
            $table->increments('id');
            $table->string("name");
            $table->integer("country_id")->default(1);
           
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('countries');
        Schema::dropIfExists('states');
        Schema::dropIfExists('cities');
    }
}
