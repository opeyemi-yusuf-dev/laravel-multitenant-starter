<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Handle an incoming request for multi-tenant database switching.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Extract Tenant Identifier from Header or Subdomain
        $tenantId = $request->header('X-Tenant-ID');

        if (!$tenantId) {
            $host = $request->getHost();
            $account = explode('.', $host);
            $tenantId = $account[0] ?? null;
        }

        if (!$tenantId || $tenantId === 'www') {
            return response()->json(['error' => 'Tenant identification header or subdomain missing.'], 400);
        }

        // 2. Fetch tenant database details (Simulated or fetched from central DB)
        $databaseName = 'tenant_' . preg_replace('/[^a-zA-Z0-9_]/', '', $tenantId);

        // 3. Dynamically reconfigure tenant database connection
        Config::set('database.connections.tenant.database', $databaseName);
        DB::purge('tenant');
        DB::reconnect('tenant');
        DB::setDefaultConnection('tenant');

        return $next($request);
    }
}
