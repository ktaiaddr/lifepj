<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTabledatHouseholdAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('DAT_HOUSEHOLD_ACCOUNT_ACCOUNT', function (Blueprint $table) {
            $table->renameColumn('balance','increase_decrease_type');
        });
        Schema::table('DAT_HOUSEHOLD_ACCOUNT_ACCOUNT', function (Blueprint $table) {
            DB::statement('alter table DAT_HOUSEHOLD_ACCOUNT_ACCOUNT change column increase_decrease_type increase_decrease_type tinyint not null comment \'アカウント残高の増減種別 1:減少、2:増加\'');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('DAT_HOUSEHOLD_ACCOUNT_ACCOUNT', function (Blueprint $table) {
            DB::statement('alter table DAT_HOUSEHOLD_ACCOUNT_ACCOUNT change column increase_decrease_type increase_decrease_type integer comment \'\'');
        });

        Schema::table('DAT_HOUSEHOLD_ACCOUNT_ACCOUNT', function (Blueprint $table) {
            $table->renameColumn('increase_decrease_type','balance');
        });
    }
}
