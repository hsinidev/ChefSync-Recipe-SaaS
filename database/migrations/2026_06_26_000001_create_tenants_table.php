<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->binary('uuid', 16)->unique();
            $table->string('name');
            $table->string('domain')->unique();
            $table->boolean('uses_isolated_db')->default(false);
            $table->text('db_config')->nullable(); // Encrypted JSON
            $table->string('billing_plan', 50)->default('free');
            $table->timestamps();

            $table->index('domain');
            $table->index('uuid');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
