<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->dateTime('starts_at');
            $table->dateTime('expires_at')->nullable();
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active'); // active, expired, cancelled
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for common queries
            $table->index(['expires_at']);
            $table->index(['user_id']);
            $table->index(['plan_id']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
