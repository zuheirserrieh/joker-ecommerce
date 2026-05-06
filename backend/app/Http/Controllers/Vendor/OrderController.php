<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Vendor\Concerns\InteractsWithVendorScope;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use InteractsWithVendorScope;

    public function index(Request $request): JsonResponse
    {
        $query = $this->vendor($request)
            ->orders()
            ->with(['customer', 'orderItems.product', 'payment'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        return response()->json($query->paginate(15));
    }

    public function show(Request $request, Order $order): JsonResponse
    {
        abort_if($order->vendor_id !== $this->vendor($request)->id, 404);

        return response()->json($order->load(['customer', 'orderItems.product', 'payment']));
    }

    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        abort_if($order->vendor_id !== $this->vendor($request)->id, 404);

        $data = $request->validate([
            'status' => ['required', 'in:pending,confirmed,processing,delivered,cancelled'],
            'payment_status' => ['nullable', 'in:pending,paid,failed'],
        ]);

        $order->update($data);

        return response()->json($order->fresh()->load(['customer', 'orderItems.product', 'payment']));
    }
}
