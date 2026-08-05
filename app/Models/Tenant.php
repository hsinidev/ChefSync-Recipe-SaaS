<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

final class Tenant extends Model
{
    protected $fillable = [
        'uuid',
        'name',
        'domain',
        'uses_isolated_db',
        'db_config',
        'billing_plan',
    ];

    protected $casts = [
        'uses_isolated_db' => 'boolean',
        'db_config' => 'encrypted:array',
    ];

    /**
     * Boot model to automatically generate binary UUID on creation if not set.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Tenant $tenant) {
            if (empty($tenant->uuid)) {
                $tenant->uuid = Str::uuid()->getBytes();
            }
        });
    }

    /**
     * Helper to get UUID as string representation.
     */
    public function getUuidStringAttribute(): string
    {
        return Str::uuid()->toString();
    }

    /**
     * Get all recipes belonging to this tenant.
     */
    public function recipes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Recipe::class, 'tenant_uuid', 'uuid');
    }
}
