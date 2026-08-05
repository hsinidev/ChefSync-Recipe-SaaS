<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('header_logo_text')->default('ChefSync');
            $table->string('header_subtitle')->default('Culinary Portal');
            $table->text('header_nav_links')->nullable(); // JSON
            $table->string('footer_newsletter_title')->default('Our best tips for eating thoughtfully and living joyfully, right in your inbox.');
            $table->string('footer_newsletter_placeholder')->default('ex: myname@email.com');
            $table->string('footer_newsletter_button')->default('SUBSCRIBE');
            $table->string('footer_copyright')->default('© 2026 ChefSync, Inc. All Rights Reserved');
            $table->text('footer_columns_json')->nullable(); // JSON
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'header_logo_text',
                'header_subtitle',
                'header_nav_links',
                'footer_newsletter_title',
                'footer_newsletter_placeholder',
                'footer_newsletter_button',
                'footer_copyright',
                'footer_columns_json',
            ]);
        });
    }
};
