<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiToolsController extends Controller
{
    public function lowStockAlerts(Request $request): JsonResponse
    {
        $vendor = $request->user();
        $products = $vendor->products()->lowStock()->get();

        $alerts = $products->map(function ($product) {
            $gap = max($product->low_stock_threshold - $product->stock_qty, 0);

            return [
                'product_id' => $product->id,
                'name' => $product->name,
                'stock_qty' => $product->stock_qty,
                'low_stock_threshold' => $product->low_stock_threshold,
                'suggested_reorder_qty' => max($gap * 3, 5),
                'urgency' => $product->stock_qty === 0 ? 'high' : ($product->stock_qty <= 2 ? 'medium' : 'low'),
                'image_url' => $product->image_url,
            ];
        });

        return response()->json(['alerts' => $alerts]);
    }

    public function salesForecast(Request $request): JsonResponse
    {
        $vendor = $request->user();
        $daily = $vendor->orders()
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get();

        $last7Days = $vendor->orders()
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->sum('total_amount');
        
        $last30Days = $vendor->orders()
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->sum('total_amount');

        $average7 = (float) ($last7Days / 7);
        $average30 = (float) ($last30Days / 30);
        
        // Generate forecast with slight variation
        $forecast = collect(range(1, 14))->map(fn ($day) => [
            'date' => now()->addDays($day)->format('M d'),
            'projected_revenue' => round($average7 * (0.9 + (rand(0, 20) / 100)), 2),
        ]);

        return response()->json([
            'history' => $daily->map(fn ($d) => [
                'date' => \Carbon\Carbon::parse($d->date)->format('M d'),
                'revenue' => (float) $d->total,
            ]),
            'forecast' => $forecast,
            'trend' => $average7 > $average30 ? 'up' : 'down',
            'last7_avg' => round($average7, 2),
            'last30_avg' => round($average30, 2),
            'insight' => $average7 > $average30 
                ? '📈 Your sales are trending upward! Keep momentum going.' 
                : '📊 Sales are stable. Consider promotions to boost growth.',
        ]);
    }

    public function earningsSummary(Request $request): JsonResponse
    {
        $vendor = $request->user();
        $orders = $vendor->orders();
        
        $last30Days = $orders->whereDate('created_at', '>=', now()->subDays(30));
        $last7Days = $orders->whereDate('created_at', '>=', now()->subDays(7));
        
        $grossRevenue = (float) $orders->sum('total_amount');
        $grossProfit = (float) $orders->sum('profit_amount');
        $totalCost = $grossRevenue - $grossProfit;
        $margin = $grossRevenue > 0 ? round(($grossProfit / $grossRevenue) * 100, 2) : 0;
        
        $revenue30 = (float) $last30Days->sum('total_amount');
        $profit30 = (float) $last30Days->sum('profit_amount');
        
        $totalOrders = $orders->count();
        $avgOrderValue = $totalOrders > 0 ? round($grossRevenue / $totalOrders, 2) : 0;

        return response()->json([
            'gross_revenue' => $grossRevenue,
            'total_cost' => $totalCost,
            'gross_profit' => $grossProfit,
            'margin' => $margin,
            'last30_revenue' => $revenue30,
            'last30_profit' => $profit30,
            'total_orders' => $totalOrders,
            'avg_order_value' => $avgOrderValue,
            'summary' => "Total revenue: \${$grossRevenue}. Gross profit: \${$grossProfit}. Margin: {$margin}%.",
            'tip' => 'Review your lowest-margin products and adjust pricing or sourcing costs first.',
        ]);
    }

    public function customerInsights(Request $request): JsonResponse
    {
        $vendor = $request->user();
        
        $totalCustomers = $vendor->orders()->distinct('customer_id')->count();
        $repeatCustomers = $vendor->orders()->groupBy('customer_id')
            ->havingRaw('COUNT(*) > 1')
            ->count();
        
        $avgOrdersPerCustomer = $totalCustomers > 0 
            ? round($vendor->orders()->count() / $totalCustomers, 2) 
            : 0;

        return response()->json([
            'total_customers' => $totalCustomers,
            'repeat_customers' => $repeatCustomers,
            'new_customers' => $totalCustomers - $repeatCustomers,
            'repeat_rate' => $totalCustomers > 0 ? round(($repeatCustomers / $totalCustomers) * 100, 1) : 0,
            'avg_orders_per_customer' => $avgOrdersPerCustomer,
            'insight' => $repeatCustomers > $totalCustomers * 0.3
                ? '⭐ Strong repeat customer base! Focus on loyalty.'
                : '🎯 Build repeat customer base through follow-ups.',
        ]);
    }

    public function pricingRecommendation(Request $request): JsonResponse
    {
        $vendor = $request->user();
        $products = $vendor->products()->get();

        $recommendations = $products->map(function ($product) {
            $orders = $product->orderItems()->count();
            $margin = $product->price > 0 && $product->cost_price
                ? round((($product->price - $product->cost_price) / $product->price) * 100, 2)
                : 0;

            $recommended = $product->price;
            if ($orders > 10 && $margin < 20) {
                $recommended = round($product->cost_price * 1.35, 2);
            } elseif ($orders < 3 && $margin > 40) {
                $recommended = round($product->cost_price * 1.25, 2);
            }

            return [
                'product_id' => $product->id,
                'name' => $product->name,
                'current_price' => (float) $product->price,
                'recommended_price' => (float) $recommended,
                'current_margin' => $margin,
                'orders_sold' => $orders,
                'recommendation' => $recommended > $product->price
                    ? "⬆️ Increase to \${$recommended}"
                    : ($recommended < $product->price ? "⬇️ Decrease to \${$recommended}" : "✓ Price is good"),
            ];
        });

        return response()->json(['recommendations' => $recommendations]);
    }

    public function productDescription(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'numeric'],
            'market_type' => ['nullable', 'string', 'max:255'],
        ]);

        if (filled(env('CLAUDE_API_KEY'))) {
            try {
                $response = Http::withHeaders([
                    'x-api-key' => env('CLAUDE_API_KEY'),
                    'anthropic-version' => '2023-06-01',
                ])->timeout(30)->post('https://api.anthropic.com/v1/messages', [
                    'model' => env('CLAUDE_MODEL', 'claude-sonnet-4-20250514'),
                    'max_tokens' => 300,
                    'messages' => [[
                        'role' => 'user',
                        'content' => sprintf(
                            'Write a compelling 3-sentence product description for a %s product named "%s" in the %s market at price $%s. Make it engaging and benefit-focused.',
                            $data['category'] ?? 'general',
                            $data['name'],
                            $data['market_type'] ?? 'general',
                            $data['price'] ?? 'N/A'
                        ),
                    ]],
                ]);

                if ($response->successful()) {
                    $description = data_get($response->json(), 'content.0.text');
                    return response()->json([
                        'description' => $description,
                        'ai_generated' => true,
                    ]);
                }
            } catch (\Exception $e) {
                // Fallback if API fails
            }
        }

        return response()->json([
            'description' => "{$data['name']} is designed for {$data['market_type']} shoppers who want reliable value and quality. Its practical features and clear positioning make it an easy fit for everyday purchase decisions. Get yours today and experience the difference!",
            'ai_generated' => false,
        ]);
    }
}
