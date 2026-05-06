<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    public function show(Vendor $vendor): JsonResponse
    {
        return response()->json([
            'vendor' => $vendor,
            'settings' => $vendor->vendorSettings()->pluck('value', 'key'),
            'categories' => $vendor->categories()->orderBy('sort_order')->get(),
            'featured_products' => $vendor->products()->where('is_active', true)->take(8)->get(),
        ]);
    }

    public function products(Request $request, Vendor $vendor): JsonResponse
    {
        $query = $vendor->products()->with('category')->where('is_active', true);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->string('search').'%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->string('category_id'));
        }

        if ($request->string('sort') === 'price_asc') {
            $query->orderBy('price');
        } elseif ($request->string('sort') === 'price_desc') {
            $query->orderByDesc('price');
        } else {
            $query->latest();
        }

        return response()->json($query->paginate(16));
    }

    public function product(Vendor $vendor, Product $product): JsonResponse
    {
        abort_if($product->vendor_id !== $vendor->id || ! $product->is_active, 404);

        return response()->json($product->load('category'));
    }
}
