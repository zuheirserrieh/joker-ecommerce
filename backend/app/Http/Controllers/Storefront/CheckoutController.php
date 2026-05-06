<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    public function store(Request $request, Vendor $vendor): JsonResponse
    {
        $data = $request->validate([
            'customer.name' => ['required', 'string', 'max:255'],
            'customer.email' => ['required', 'email'],
            'customer.phone' => ['nullable', 'string', 'max:255'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'uuid', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:online,cash'],
            'notes' => ['nullable', 'string'],
        ]);

        $order = DB::transaction(function () use ($data, $vendor) {
            $customer = Customer::firstOrCreate(
                ['vendor_id' => $vendor->id, 'email' => $data['customer']['email']],
                [
                    'name' => $data['customer']['name'],
                    'phone' => $data['customer']['phone'] ?? null,
                ]
            );

            $products = Product::query()
                ->where('vendor_id', $vendor->id)
                ->whereIn('id', collect($data['items'])->pluck('product_id'))
                ->get()
                ->keyBy('id');

            $total = 0;
            $profit = 0;

            $order = Order::create([
                'vendor_id' => $vendor->id,
                'customer_id' => $customer->id,
                'total_amount' => 0,
                'profit_amount' => 0,
                'status' => 'pending',
                'payment_method' => $data['payment_method'],
                'payment_status' => 'pending',
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                $product = $products[$item['product_id']] ?? null;

                abort_unless($product, 422, 'Invalid product selection.');
                abort_if($product->stock_qty < $item['quantity'], 422, "Insufficient stock for {$product->name}.");

                $lineTotal = $product->price * $item['quantity'];
                $lineProfit = ($product->price - $product->cost_price) * $item['quantity'];

                $order->orderItems()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'cost_price' => $product->cost_price,
                ]);

                $product->decrement('stock_qty', $item['quantity']);

                $total += $lineTotal;
                $profit += $lineProfit;
            }

            $order->update([
                'total_amount' => $total,
                'profit_amount' => $profit,
                'payment_status' => $data['payment_method'] === 'cash' ? 'pending' : 'pending',
            ]);

            Payment::create([
                'order_id' => $order->id,
                'method' => $data['payment_method'],
                'status' => $data['payment_method'] === 'cash' ? 'pending' : 'pending',
                'amount' => $total,
            ]);

            return $order->load(['customer', 'orderItems.product', 'payment']);
        });

        return response()->json($order, 201);
    }

    public function paymentIntent(Request $request, Vendor $vendor): JsonResponse
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'currency' => ['nullable', 'string', 'size:3'],
        ]);

        abort_if(blank(env('STRIPE_SECRET')), 422, 'Stripe is not configured.');

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $intent = PaymentIntent::create([
            'amount' => (int) round($data['amount'] * 100),
            'currency' => strtolower($data['currency'] ?? 'usd'),
            'metadata' => ['vendor_id' => $vendor->id],
            'automatic_payment_methods' => ['enabled' => true],
        ]);

        return response()->json([
            'client_secret' => $intent->client_secret,
            'payment_intent_id' => $intent->id,
        ]);
    }
}
