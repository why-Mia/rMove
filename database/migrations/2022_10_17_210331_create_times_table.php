<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {//TODO: adding times fails if no map found.
        Schema::create('times', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('roblox_account_id');
            $table->foreign('roblox_account_id')->references('id')->on('roblox_accounts')
            ->onDelete('cascade');
            $table->integer('time');
            $table->unsignedBigInteger('map_id');
            $table->foreign('map_id')->references('id')->on('maps')
            ->onDelete('cascade');
            $table->integer('style');
            $table->integer('mode');
            $table->integer('game');
            $table->timestamp('date');
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
        Schema::dropIfExists('times');
    }
};
