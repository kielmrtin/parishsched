<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    if (!Schema::hasTable('customers')) {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('email');
            $table->text('phone')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->text('role')->nullable();
            $table->uuid('auth_id')->nullable();
        });
    }
}

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};