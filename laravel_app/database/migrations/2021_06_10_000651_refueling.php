<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Refueling extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('refuelings', function (Blueprint $table) {
            // change() tells the Schema builder that we are altering a table
            $table->string('gas_station',30)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('refuelings', function (Blueprint $table) {
            // change() tells the Schema builder that we are altering a table
            $table->string('gas_station')->change();
        });
    }
}
