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
            ];
        });

        return response()->json(['alerts' => $alerts]);
    }

    public function salesForecast(Request $request): JsonResponse
    {
        $vendor = $request->user();
        $daily = $vendor->orders()
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get();

        $average = (float) $daily->avg('total');
        $forecast = collect(range(1, 7))->map(fn ($day) => [
            'date' => now()->addDays($day)->toDateString(),
            'projected_revenue' => round($average, 2),
        ]);

        return response()->json([
            'history' => $daily,
            'forecast' => $forecast,
            'insight' => 'Revenue is projected from the last 7 days average. Connect Claude for narrative forecasting.',
        ]);
    }

    public function earningsSummary(Request $request): JsonResponse
    {
        $vendor = $request->user();
        $orders = $vendor->orders();
        $grossRevenue = (float) $orders->sum('total_amount');
        $grossProfit = (float) $orders->sum('profit_amount');
        $totalCost = $grossRevenue - $grossProfit;
        $margin = $grossRevenue > 0 ? round(($grossProfit / $grossRevenue) * 100, 2) : 0;

        return response()->json([
            'gross_revenue' => $grossRevenue,
            'total_cost' => $totalCost,
            'gross_profit' => $grossProfit,
            'margin' => $margin,
            'summary' => "Gross revenue is {$grossRevenue}, gross profit is {$grossProfit}, and current margin is {$margin}%.",
            'tip' => 'Review your lowest-margin products and adjust pricing or sourcing costs first.',
        ]);
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
            $response = Http::withHeaders([
                'x-api-key' => env('CLAUDE_API_KEY'),
                'anthropic-version' => '2023-06-01',
            ])->post('https://api.anthropic.com/v1/messages', [
                'model' => env('CLAUDE_MODEL', 'claude-sonnet-4-20250514'),
                'max_tokens' => 200,
                'messages' => [[
                    'role' => 'user',
                    'content' => sprintf(
                        'Write a compelling two-sentence product description for a %s product named %s in the %s market at price %s.',
                        $data['category'] ?? 'general',
                        $data['name'],
                        $data['market_type'] ?? 'general',
                        $data['price'] ?? 'N/A'
                    ),
                ]],
            ]);

            if ($response->successful()) {
                return response()->json([
                    'description' => data_get($response->json(), 'content.0.text'),
                ]);
            }
        }

        return response()->json([
            'description' => "{$data['name']} is designed for {$data['market_type']} shoppers who want reliable value and a polished presentation. Its practical features and clear positioning make it an easy fit for everyday purchase decisions.",
        ]);
    }
}
