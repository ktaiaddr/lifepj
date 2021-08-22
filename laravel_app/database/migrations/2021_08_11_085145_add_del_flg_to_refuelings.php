<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDelFlgToRefuelings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('refuelings', function (Blueprint $table) {
            $table->tinyInteger('del_flg')->after('memo')->default(0);
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
            Schema::table('refuelings', function (Blueprint $table) {
                $table->dropColumn('del_flg');
            });
        });
    }
}
