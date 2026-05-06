<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureVendor
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user() instanceof \App\Models\Vendor) {
            abort(403, 'Vendor access only.');
        }

        return $next($request);
    }
}
