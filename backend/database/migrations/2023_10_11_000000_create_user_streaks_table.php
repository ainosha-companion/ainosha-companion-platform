<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_streaks', function (Blueprint $table) {
            $table->foreignId('user_id')->primary();
            $table->unsignedInteger('current_streak')->default(0);
            $table->unsignedInteger('highest_streak')->default(0);
            $table->datetime('last_login')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_streaks');
    }
};
