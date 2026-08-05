<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Model;

final class Setting extends Model
{
    use HasTenant;

    protected $fillable = [
        'tenant_uuid',
        'gemini_api_key',
        'openai_api_key',
        'preferred_ai_provider',
        'openai_model',
        'header_logo_text',
        'header_subtitle',
        'header_nav_links',
        'footer_newsletter_title',
        'footer_newsletter_placeholder',
        'footer_newsletter_button',
        'footer_copyright',
        'footer_columns_json',
    ];

    protected $casts = [
        'header_nav_links' => 'array',
        'footer_columns_json' => 'array',
    ];
}
