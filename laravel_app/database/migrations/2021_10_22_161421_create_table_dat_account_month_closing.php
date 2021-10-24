<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDatAccountMonthClosing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DAT_ACCOUNT_MONTH_CLOSING', function (Blueprint $table) {
            $table->integer('account_id')->comment("アカウントID");
            $table->integer('month')->comment('対象締月');
            $table->integer('balance')->comment('残高');
            $table->dateTime('created_at');
            $table->primary(['account_id','month']);

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
        Schema::dropIfExists('DAT_ACCOUNT_MONTH_CLOSING');
    }
}
