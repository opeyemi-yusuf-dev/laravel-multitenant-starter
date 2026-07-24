<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TenantReportController extends Controller
{
    /**
     * Retrieve isolated tenant analytics and reporting overview.
     */
    public function index(Request $request): JsonResponse
    {
        // Executes queries within the active, dynamically switched tenant connection
        $summary = [
            'tenant_id' => $request->header('X-Tenant-ID'),
            'active_users' => DB::table('users')->count(),
            'system_status' => 'Healthy',
            'timestamp' => now()->toIso8601String(),
        ];

        return response()->json([
            'success' => true,
            'data' => $summary,
        ]);
    }
}
