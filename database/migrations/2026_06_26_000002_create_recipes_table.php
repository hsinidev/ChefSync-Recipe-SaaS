<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->binary('tenant_uuid', 16);
            $table->foreignId('author_id')->constrained('users')->onDelete('restrict');
            $table->string('title');
            $table->string('slug');
            $table->text('excerpt')->nullable();
            $table->unsignedSmallInteger('prep_time_minutes')->default(0);
            $table->unsignedSmallInteger('cook_time_minutes')->default(0);
            $table->unsignedSmallInteger('servings')->default(4);
            $table->enum('status', ['draft', 'review', 'published'])->default('draft');
            $table->timestamps();

            $table->unique(['tenant_uuid', 'slug'], 'idx_tenant_slug');
            $table->index(['tenant_uuid', 'status', 'created_at'], 'idx_tenant_status_created');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
