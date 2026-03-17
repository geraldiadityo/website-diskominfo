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
        Schema::create('organization_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position')->comment('Kepala Dinas, Sekretaris, Kepala Bidang dll');
            $table->string('departement')->nullable()->comment('Unit/Bidang');
            $table->string('bio')->nullable();
            $table->string('photo')->nullable();
            // self-referencing foreign key
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('organization_members')
                ->nullOnDelete();

            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['parent_id', 'sort_order'], 'idx_org_members_parent_sort');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_members');
    }
};
