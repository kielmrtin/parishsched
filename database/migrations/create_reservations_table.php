<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    if (!Schema::hasTable('reservations')) {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id')->nullable();
            $table->text('event_type');
            $table->date('reservation_date')->nullable();
            $table->text('reservation_time');
            $table->text('status')->default('pending');
            $table->timestamp('created_at')->useCurrent();
            $table->text('admin_note')->nullable();
            $table->bigInteger('approved_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->json('details')->nullable();
        });
    }
}

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};