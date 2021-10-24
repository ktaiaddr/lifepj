<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenatableDatHouseHoldAccountBalanceToDatHouseHoldAccountAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('DAT_HOUSEHOLD_ACCOUNT_BALANCE', 'DAT_HOUSEHOLD_ACCOUNT_ACCOUNT');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('DAT_HOUSEHOLD_ACCOUNT_ACCOUNT', 'DAT_HOUSEHOLD_ACCOUNT_BALANCE');
    }
}
