<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMstHouseholdAccountTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DAT_HOUSEHOLD_ACCOUNT_TRANSACTION', function (Blueprint $table) {
            $table->string('transaction_id')->primary();
            $table->date('date');
            $table->integer('amount');
            $table->string('contents');
            $table->tinyInteger('type');
            $table->dateTime('created_at');
//            $table->dateTime('updated_at');
//            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('DAT_HOUSEHOLD_ACCOUNT_TRANSACTION');

    }
}
