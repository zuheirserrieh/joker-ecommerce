<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Vendor\Concerns\InteractsWithVendorScope;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    use InteractsWithVendorScope;

    public function index(Request $request): JsonResponse
    {
        $customers = $this->vendor($request)
            ->customers()
            ->withCount('orders')
            ->withSum('orders', 'total_amount')
            ->latest()
            ->paginate(15);

        return response()->json($customers);
    }

    public function show(Request $request, Customer $customer): JsonResponse
    {
        abort_if($customer->vendor_id !== $this->vendor($request)->id, 404);

        return response()->json($customer->load(['orders.orderItems.product', 'orders.payment']));
    }
}
