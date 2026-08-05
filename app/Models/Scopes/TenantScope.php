<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

final class TenantScope implements Scope
{
    private static ?string $tenantId = null;

    public static function setTenantId(?string $tenantId): void
    {
        self::$tenantId = $tenantId;
    }

    public static function getTenantId(): ?string
    {
        return self::$tenantId;
    }

    public function apply(Builder $builder, Model $model): void
    {
        if (self::$tenantId !== null) {
            $builder->where('tenant_uuid', self::$tenantId);
        }
    }
}
