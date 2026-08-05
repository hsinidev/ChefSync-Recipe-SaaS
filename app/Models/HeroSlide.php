<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Model;

final class HeroSlide extends Model
{
    use HasTenant;

    protected $fillable = [
        'tenant_uuid',
        'title',
        'subtitle',
        'category_tag',
        'image_url',
        'link_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];
}
