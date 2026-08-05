<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant;
use App\Models\Scopes\TenantScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

final readonly class TenantDatabaseResolver
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();

        // Retrieve tenant metadata from persistent cache layers
        $tenant = Cache::remember("tenant:host:{$host}", 86400, function () use ($host) {
            return Tenant::where('domain', $host)->first();
        });

        if (!$tenant) {
            // In local development, try resolving common aliases (127.0.0.1 <-> localhost)
            $aliasMap = ['127.0.0.1' => 'localhost', 'localhost' => '127.0.0.1', 'recipes.hsini.dev' => 'localhost'];
            if (isset($aliasMap[$host])) {
                $aliasHost = $aliasMap[$host];
                $tenant = Cache::remember("tenant:host:{$aliasHost}", 86400, function () use ($aliasHost) {
                    return Tenant::where('domain', $aliasHost)->first();
                });
            }
        }

        if (!$tenant) {
            // In local/debug mode, allow the request through without a tenant scope
            if (app()->isLocal()) {
                return $next($request);
            }
            abort(Response::HTTP_NOT_FOUND, 'The requested culinary blog does not exist on our servers.');
        }

        if ($tenant->uses_isolated_db) {
            $config = json_decode(Crypt::decryptString($tenant->db_config), true, 512, JSON_THROW_ON_ERROR);
            
            Config::set('database.connections.tenant.host', $config['host']);
            Config::set('database.connections.tenant.database', $config['database']);
            Config::set('database.connections.tenant.username', $config['username']);
            Config::set('database.connections.tenant.password', $config['password']);

            DB::purge('tenant');
            DB::reconnect('tenant');
            
            // Enforce schema checks
            Schema::connection('tenant')->getConnection()->reconnect();
        } else {
            // Bind tenant context reference globally
            TenantScope::setTenantId($tenant->uuid);
        }

        return $next($request);
    }
}
