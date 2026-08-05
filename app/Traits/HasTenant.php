<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;

trait HasTenant
{
    /**
     * Boot the trait to apply the global tenant scope and listen for creating events.
     */
    protected static function bootHasTenant(): void
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function (Model $model) {
            if (empty($model->tenant_uuid)) {
                $tenantId = TenantScope::getTenantId();
                if ($tenantId !== null) {
                    $model->tenant_uuid = $tenantId;
                }
            }
        });
    }
}
