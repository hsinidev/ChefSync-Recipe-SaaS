<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->decimal('amount', 8, 2)->unsigned();
            $table->string('unit', 50);
            $table->string('name');
            $table->string('state', 100)->nullable();

            $table->index(['recipe_id', 'sort_order'], 'idx_recipe_ingredients');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
