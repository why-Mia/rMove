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
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('primary_game')->nullable();
            $table->bigInteger('roblox_id')->nullable();
            $table->string('roblox_username')->nullable();
            $table->dateTime('roblox_account_verified_at')->nullable();
            $table->string('times')->nullable();
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['primary_game', 'roblox_id', 'roblox_username', 'roblox_account_verified_at', 'times']);
            //
        });
    }
};
