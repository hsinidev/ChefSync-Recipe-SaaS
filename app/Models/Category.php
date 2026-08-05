<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Category extends Model
{
    use HasTenant;

    protected $fillable = [
        'tenant_uuid',
        'name',
        'slug',
    ];

    /**
     * Get all recipes belonging to this category.
     */
    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }
}
