<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Vendor\Concerns\InteractsWithVendorScope;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SettingsController extends Controller
{
    use InteractsWithVendorScope;

    public function show(Request $request): JsonResponse
    {
        $settings = $this->vendor($request)->vendorSettings()->pluck('value', 'key');

        return response()->json($settings);
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'settings' => ['required', 'array'],
        ]);

        $vendor = $this->vendor($request);

        foreach ($data['settings'] as $key => $value) {
            $vendor->vendorSettings()->updateOrCreate(
                ['key' => $key],
                ['value' => is_scalar($value) || is_null($value) ? $value : json_encode($value), 'updated_at' => Carbon::now()]
            );
        }

        return response()->json($vendor->vendorSettings()->pluck('value', 'key'));
    }
}
