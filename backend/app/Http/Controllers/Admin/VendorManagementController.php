<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VendorManagementController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Vendor::latest()->paginate(15));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:vendors,slug'],
            'market_type' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:vendors,email'],
            'phone' => ['nullable', 'string', 'max:255'],
            'logo_url' => ['nullable', 'string', 'max:2048'],
            'password' => ['required', 'string', 'min:8'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $vendor = Vendor::create($data);

        $vendor->assignRole('vendor');

        return response()->json($vendor, 201);
    }

    public function show(Vendor $vendor): JsonResponse
    {
        return response()->json($vendor->loadCount(['products', 'orders', 'customers']));
    }

    public function update(Request $request, Vendor $vendor): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => ['sometimes', 'string', 'max:255', 'unique:vendors,slug,'.$vendor->id.',id'],
            'market_type' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'unique:vendors,email,'.$vendor->id.',id'],
            'phone' => ['nullable', 'string', 'max:255'],
            'logo_url' => ['nullable', 'string', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $vendor->update($data);

        return response()->json($vendor->fresh());
    }

    public function destroy(Vendor $vendor): JsonResponse
    {
        $vendor->delete();

        return response()->json(status: 204);
    }
}
