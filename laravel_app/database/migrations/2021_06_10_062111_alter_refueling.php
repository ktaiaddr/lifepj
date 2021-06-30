<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRefueling extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('refuelings', function (Blueprint $table) {
            $table->renameColumn('refueling_id','refueling_id');
            //
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
            $table->renameColumn('refueling_id','refuelin_id');
        });
    }
}
