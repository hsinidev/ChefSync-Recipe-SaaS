<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create categories table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->binary('tenant_uuid', 16);
            $table->string('name');
            $table->string('slug');
            $table->timestamps();

            $table->unique(['tenant_uuid', 'slug'], 'idx_tenant_category_slug');
        });

        // 2. Create settings table
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->binary('tenant_uuid', 16)->unique();
            $table->text('gemini_api_key')->nullable();
            $table->text('openai_api_key')->nullable();
            $table->string('preferred_ai_provider')->default('gemini');
            $table->string('openai_model')->default('gpt-4o-mini');
            $table->timestamps();
        });

        // 3. Update recipes table
        Schema::table('recipes', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->longText('description_html')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'description_html']);
        });

        Schema::dropIfExists('settings');
        Schema::dropIfExists('categories');
    }
};
