<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('vendor_id')->constrained('vendors')->cascadeOnDelete();
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->unique(['vendor_id', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_settings');
    }
};
