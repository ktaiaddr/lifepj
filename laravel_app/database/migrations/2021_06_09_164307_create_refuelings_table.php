<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class   CreateRefuelingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refuelings', function (Blueprint $table) {
            $table->increments('refueling_id');
            $table->float('refueling_amount');
            $table->float('refueling_distance');
            $table->string('gas_station');
            $table->string('memo');
//            $table->dateTime('created_at');
//            $table->dateTime('updated_at');
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
        Schema::dropIfExists('refuelings');
    }
}
