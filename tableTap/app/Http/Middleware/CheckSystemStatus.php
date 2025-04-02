<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Inertia\Inertia;

class CheckSystemStatus
{
    public function handle(Request $request, Closure $next)
    {
        // Let admin routes pass so administrators can change the status.
        if ($request->is('admin/*') || ($request->is('login/admin'))) {
            return $next($request);
        }
        
        $setting = SystemSetting::where('key', 'system_enabled')->first();
        $enabled = $setting ? $setting->value === 'true' : true;

        if (!$enabled) {
            // If using Inertia, render an Inertia page for maintenance.
            return Inertia::render('Maintenance', [
           
            ]);
        }

        return $next($request);
    }
}