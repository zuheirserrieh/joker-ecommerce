<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Vendor\Concerns\InteractsWithVendorScope;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use InteractsWithVendorScope;

    public function index(Request $request): JsonResponse
    {
        $vendor = $this->vendor($request);

        $query = $vendor->products()->with('category')->latest();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->string('category_id'));
        }

        if ($request->boolean('low_stock')) {
            $query->lowStock();
        }

        return response()->json($query->paginate(15));
    }

    public function store(Request $request): JsonResponse
    {
        $vendor = $this->vendor($request);

        $data = $request->validate([
            'category_id' => ['nullable', 'uuid', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'ai_description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'cost_price' => ['sometimes', 'numeric', 'min:0'],
            'stock_qty' => ['sometimes', 'integer', 'min:0'],
            'low_stock_threshold' => ['sometimes', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'image_url' => ['nullable', 'string', 'max:2048'],
        ]);

        $product = $vendor->products()->create($data);

        return response()->json($product->load('category'), 201);
    }

    public function show(Request $request, Product $product): JsonResponse
    {
        abort_if($product->vendor_id !== $this->vendor($request)->id, 404);

        return response()->json($product->load('category'));
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        abort_if($product->vendor_id !== $this->vendor($request)->id, 404);

        $data = $request->validate([
            'category_id' => ['nullable', 'uuid', 'exists:categories,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'ai_description' => ['nullable', 'string'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'cost_price' => ['sometimes', 'numeric', 'min:0'],
            'stock_qty' => ['sometimes', 'integer', 'min:0'],
            'low_stock_threshold' => ['sometimes', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'image_url' => ['nullable', 'string', 'max:2048'],
        ]);

        $product->update($data);

        return response()->json($product->fresh()->load('category'));
    }

    public function destroy(Request $request, Product $product): JsonResponse
    {
        abort_if($product->vendor_id !== $this->vendor($request)->id, 404);

        $product->delete();

        return response()->json(status: 204);
    }
}
