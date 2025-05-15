<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feature_plan_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feature_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->unique(['feature_id', 'plan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_plan_rules');
    }
};
