<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class VendorShowcaseSeeder extends Seeder
{
    public function run(): void
    {
        $vendorRole = Role::firstOrCreate([
            'name' => 'vendor',
            'guard_name' => 'vendor',
        ]);

        DB::transaction(function () use ($vendorRole) {
            $vendors = [
                [
                    'email' => 'fusion@marketos.test',
                    'password' => 'password',
                    'name' => 'Fusion Threads',
                    'slug' => 'fusion-threads',
                    'market_type' => 'fashion boutique',
                    'phone' => '+1 555 0200',
                    'settings' => [
                        'hero_headline' => 'Tailored streetwear with a sharp seasonal edge.',
                        'hero_cta' => 'Shop the latest drop',
                        'brand_color' => '#c65d2e',
                        'footer_tagline' => 'Modern silhouettes, clean edits, quick checkout.',
                    ],
                    'categories' => [
                        ['name' => 'Jackets', 'icon' => 'coat', 'sort_order' => 1],
                        ['name' => 'Shirts', 'icon' => 'shirt', 'sort_order' => 2],
                        ['name' => 'Accessories', 'icon' => 'sparkles', 'sort_order' => 3],
                    ],
                    'products' => [
                        [
                            'category' => 'Jackets',
                            'name' => 'Urban Bomber',
                            'price' => 89.99,
                            'cost_price' => 42.00,
                            'stock_qty' => 16,
                            'low_stock_threshold' => 4,
                            'ai_description' => 'A lightweight bomber cut for daily wear with crisp structure and all-day comfort. Built to elevate casual outfits without trying too hard.',
                        ],
                        [
                            'category' => 'Shirts',
                            'name' => 'Minimal Oxford',
                            'price' => 54.50,
                            'cost_price' => 21.00,
                            'stock_qty' => 28,
                            'low_stock_threshold' => 5,
                            'ai_description' => 'A clean oxford shirt that moves easily from office hours to evening plans. Soft fabric and a refined fit keep the look polished.',
                        ],
                        [
                            'category' => 'Accessories',
                            'name' => 'Canvas Crossbody',
                            'price' => 39.00,
                            'cost_price' => 14.50,
                            'stock_qty' => 9,
                            'low_stock_threshold' => 3,
                            'ai_description' => 'Compact, durable, and styled for city movement. This crossbody keeps essentials close without weighing down the look.',
                        ],
                    ],
                    'customer' => [
                        'name' => 'Lina Ward',
                        'email' => 'lina.fusion@example.com',
                        'phone' => '+1 555 1200',
                    ],
                ],
                [
                    'email' => 'restaurant@marketos.test',
                    'password' => 'password',
                    'name' => 'Cedar Flame Kitchen',
                    'slug' => 'cedar-flame-kitchen',
                    'market_type' => 'restaurant',
                    'phone' => '+1 555 0300',
                    'settings' => [
                        'hero_headline' => 'Fresh grilled plates, warm bread, and fast family meals.',
                        'hero_cta' => 'Order tonight',
                        'brand_color' => '#8f2f24',
                        'footer_tagline' => 'Neighborhood comfort food with bold fire and quick delivery.',
                    ],
                    'categories' => [
                        ['name' => 'Grill', 'icon' => 'flame', 'sort_order' => 1],
                        ['name' => 'Wraps', 'icon' => 'wrap', 'sort_order' => 2],
                        ['name' => 'Sides', 'icon' => 'plate', 'sort_order' => 3],
                    ],
                    'products' => [
                        [
                            'category' => 'Grill',
                            'name' => 'Mixed Grill Platter',
                            'price' => 24.99,
                            'cost_price' => 11.00,
                            'stock_qty' => 22,
                            'low_stock_threshold' => 6,
                            'ai_description' => 'A generous mixed grill platter with smoky cuts, charred vegetables, and bright sauces. Designed for hungry tables and repeat orders.',
                        ],
                        [
                            'category' => 'Wraps',
                            'name' => 'Chicken Tawook Wrap',
                            'price' => 8.75,
                            'cost_price' => 3.10,
                            'stock_qty' => 40,
                            'low_stock_threshold' => 10,
                            'ai_description' => 'Tender marinated chicken wrapped with crisp pickles and garlic sauce for a fast, satisfying meal. Big flavor in a compact format customers reorder often.',
                        ],
                        [
                            'category' => 'Sides',
                            'name' => 'Seasoned Fries',
                            'price' => 4.50,
                            'cost_price' => 1.20,
                            'stock_qty' => 55,
                            'low_stock_threshold' => 12,
                            'ai_description' => 'Golden fries tossed in a savory seasoning blend that pairs with nearly every plate. A dependable add-on that lifts average order value.',
                        ],
                    ],
                    'customer' => [
                        'name' => 'Omar Saleh',
                        'email' => 'omar.cedar@example.com',
                        'phone' => '+1 555 1300',
                    ],
                ],
            ];

            foreach ($vendors as $seed) {
                $vendor = Vendor::updateOrCreate(
                    ['email' => $seed['email']],
                    [
                        'name' => $seed['name'],
                        'slug' => $seed['slug'],
                        'market_type' => $seed['market_type'],
                        'phone' => $seed['phone'],
                        'is_active' => true,
                        'password' => $seed['password'],
                    ]
                );

                if (! $vendor->hasRole('vendor')) {
                    $vendor->assignRole($vendorRole);
                }

                foreach ($seed['settings'] as $key => $value) {
                    $vendor->vendorSettings()->updateOrCreate(
                        ['key' => $key],
                        ['value' => $value, 'updated_at' => now()]
                    );
                }

                $categoryMap = [];
                foreach ($seed['categories'] as $categoryData) {
                    $category = $vendor->categories()->updateOrCreate(
                        ['name' => $categoryData['name']],
                        $categoryData
                    );

                    $categoryMap[$category->name] = $category;
                }

                foreach ($seed['products'] as $productData) {
                    $category = $categoryMap[$productData['category']];
                    unset($productData['category']);

                    $vendor->products()->updateOrCreate(
                        ['name' => $productData['name']],
                        array_merge($productData, [
                            'category_id' => $category->id,
                            'is_active' => true,
                        ])
                    );
                }

                $customer = $vendor->customers()->updateOrCreate(
                    ['email' => $seed['customer']['email']],
                    $seed['customer']
                );

                $order = $vendor->orders()->firstOrCreate(
                    ['notes' => 'Seed sample order for '.$vendor->slug],
                    [
                        'customer_id' => $customer->id,
                        'total_amount' => 0,
                        'profit_amount' => 0,
                        'status' => 'delivered',
                        'payment_method' => 'cash',
                        'payment_status' => 'paid',
                    ]
                );

                if ($order->orderItems()->count() === 0) {
                    $total = 0;
                    $profit = 0;

                    foreach ($vendor->products()->take(2)->get() as $product) {
                        $quantity = 2;

                        $order->orderItems()->create([
                            'product_id' => $product->id,
                            'quantity' => $quantity,
                            'unit_price' => $product->price,
                            'cost_price' => $product->cost_price,
                        ]);

                        $total += $product->price * $quantity;
                        $profit += ($product->price - $product->cost_price) * $quantity;
                    }

                    $order->update([
                        'total_amount' => $total,
                        'profit_amount' => $profit,
                    ]);
                }

                Payment::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'method' => 'cash',
                        'status' => 'paid',
                        'amount' => $order->total_amount,
                        'paid_at' => now(),
                    ]
                );
            }
        });
    }
}
