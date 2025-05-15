<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('token_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('token_id');
            $table->string('type');
            $table->json('context');
            $table->dateTime('expired_at');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('token_id')->references('id')->on('tokens')->onDelete('cascade');
            $table->index('token_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_reports');
    }
};
