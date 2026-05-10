<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    if (!Schema::hasTable('predictive_analytics')) {
        Schema::create('predictive_analytics', function (Blueprint $table) {
            $table->id();
            $table->text('prediction_type');
            $table->text('title');
            $table->text('result');
            $table->decimal('confidence_score', 8, 2)->nullable();
            $table->text('recommendation')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }
}

    public function down(): void
    {
        Schema::dropIfExists('predictive_analytics');
    }
};