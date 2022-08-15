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
        Schema::table('roblox_accounts', function (Blueprint $table) {
            $table->boolean('is_primary_account')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roblox_accounts', function (Blueprint $table) {
            $table->dropColumn(['is_primary_account']);
        });
    }
};
