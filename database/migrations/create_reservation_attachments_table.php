<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    if (!Schema::hasTable('reservation_attachments')) {
        Schema::create('reservation_attachments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('reservation_id');
            $table->text('file_url');
            $table->text('field_key')->nullable();
            $table->text('label')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }
}

    public function down(): void
    {
        Schema::dropIfExists('reservation_attachments');
    }
};