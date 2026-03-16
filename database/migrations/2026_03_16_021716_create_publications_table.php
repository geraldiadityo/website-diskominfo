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
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('tipe_id')->constrained('tipes')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('description');
            $table->string('file_path');
            $table->string('file_type');
            $table->bigInteger('file_size');
            $table->string('status')->default('draf');
            $table->timestamp('published_at')->nullable();
            $table->integer('download_count')->default(0);
            $table->timestamps();

            $table->index(['status', 'published_at'], 'idx_publications_status_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
