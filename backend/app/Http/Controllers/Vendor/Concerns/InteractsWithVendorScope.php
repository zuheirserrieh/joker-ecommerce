<?php

namespace App\Http\Controllers\Vendor\Concerns;

use App\Models\Vendor;
use Illuminate\Http\Request;

trait InteractsWithVendorScope
{
    protected function vendor(Request $request): Vendor
    {
        return $request->user();
    }
}
