<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMstAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('MST_ACCOUNT', function (Blueprint $table) {
            $table->id('account_id')->comment("アカウントID");
            $table->integer('user_id')->comment('ユーザID');
            $table->tinyInteger('type')->comment('アカウント種別 1/手持ち現金、2/銀行口座');
            $table->string('name',255)->comment('アカウント名（手持ち現金、銀行口座名）');
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
        Schema::dropIfExists('MST_ACCOUNT');
    }
}
