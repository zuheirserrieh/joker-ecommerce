<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Vendor\Concerns\InteractsWithVendorScope;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorDashboardController extends Controller
{
    use InteractsWithVendorScope;

    public function index(Request $request): JsonResponse
    {
        $vendor = $this->vendor($request);

        $dailyRevenue = Order::query()
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->where('vendor_id', $vendor->id)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        $categoryBreakdown = $vendor->categories()
            ->withCount('products')
            ->get()
            ->map(fn ($category) => [
                'name' => $category->name,
                'products_count' => $category->products_count,
            ]);

        return response()->json([
            'kpis' => [
                'monthly_revenue' => $vendor->orders()->whereMonth('created_at', now()->month)->sum('total_amount'),
                'net_profit' => $vendor->orders()->whereMonth('created_at', now()->month)->sum('profit_amount'),
                'orders_count' => $vendor->orders()->whereMonth('created_at', now()->month)->count(),
                'low_stock_count' => $vendor->products()->lowStock()->count(),
            ],
            'daily_revenue' => $dailyRevenue,
            'category_breakdown' => $categoryBreakdown,
            'top_products' => $vendor->products()->orderByDesc('stock_qty')->take(5)->get(),
        ]);
    }
}
