<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//bash tool/run.sh artisan "make:migration create_table_dat_household_account_balance --create=DAT_HOUSEHOLD_ACCOUNT_BALANCE"
class CreateTableDatHouseholdAccountBalance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DAT_HOUSEHOLD_ACCOUNT_BALANCE', function (Blueprint $table) {
            $table->string('transaction_id');
            $table->tinyInteger('account_id');
            $table->integer('balance');
            $table->dateTime('created_at');

            $table->primary(['transaction_id','account_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('DAT_HOUSEHOLD_ACCOUNT_BALANCE');
    }
}
