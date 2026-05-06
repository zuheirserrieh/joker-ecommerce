<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;

class AdminDashboardController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'vendors_count' => Vendor::count(),
            'active_vendors_count' => Vendor::where('is_active', true)->count(),
            'platform_revenue' => Order::sum('total_amount'),
            'platform_profit' => Order::sum('profit_amount'),
            'recent_vendors' => Vendor::latest()->take(5)->get(),
        ]);
    }
}
