<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VendorAuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $vendor = Vendor::where('email', $credentials['email'])->first();

        if (! $vendor || ! $vendor->is_active || ! Hash::check($credentials['password'], $vendor->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 422);
        }

        $token = $vendor->createToken('vendor-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'role' => 'vendor',
            'user' => $vendor,
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user()->loadCount(['products', 'orders', 'customers']));
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json(['message' => 'Logged out.']);
    }
}
