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
        Schema::create('MST_HOUSEHOLD_ACCOUNT_TRANSACTION', function (Blueprint $table) {
            $table->increments('transaction_id');
            $table->dateTime('date');
            $table->integer('amount');
            $table->string('contents');
            $table->tinyInteger('class');
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
        Schema::dropIfExists('MST_HOUSEHOLD_ACCOUNT_TRANSACTION');

    }
}
