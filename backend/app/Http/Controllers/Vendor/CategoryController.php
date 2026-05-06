<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Vendor\Concerns\InteractsWithVendorScope;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use InteractsWithVendorScope;

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->vendor($request)->categories()->orderBy('sort_order')->get()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['sometimes', 'integer'],
        ]);

        $category = $this->vendor($request)->categories()->create($data);

        return response()->json($category, 201);
    }

    public function show(Request $request, Category $category): JsonResponse
    {
        abort_if($category->vendor_id !== $this->vendor($request)->id, 404);

        return response()->json($category);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        abort_if($category->vendor_id !== $this->vendor($request)->id, 404);

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['sometimes', 'integer'],
        ]);

        $category->update($data);

        return response()->json($category->fresh());
    }

    public function destroy(Request $request, Category $category): JsonResponse
    {
        abort_if($category->vendor_id !== $this->vendor($request)->id, 404);

        $category->delete();

        return response()->json(status: 204);
    }
}
