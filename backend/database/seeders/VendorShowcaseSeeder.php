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
                    'email' => 'vendor@marketos.test',
                    'password' => 'password',
                    'name' => 'Demo Vendor',
                    'slug' => 'demo-vendor',
                    'market_type' => 'computer store',
                    'phone' => '+1 555 0100',
                    'settings' => [
                        'header_brand' => 'Demo Tech',
                        'header_tagline' => 'Laptops and PC tools',
                        'logo_text' => 'DT',
                        'nav_shop_label' => 'Shop',
                        'nav_catalog_label' => 'Catalog',
                        'cart_label' => 'Cart',
                        'page_background' => '#f4f1e8',
                        'hero_headline' => 'Laptops, PC tools, and repair-ready gear for serious work.',
                        'hero_eyebrow' => 'Computer store',
                        'hero_cta' => 'Shop tech gear',
                        'secondary_cta' => 'Browse categories',
                        'brand_color' => '#2f5f8f',
                        'footer_tagline' => 'Performance laptops, build essentials, and trusted accessories with fast local checkout.',
                        'stat_products_label' => 'products',
                        'stat_categories_label' => 'categories',
                        'stat_stock_label' => 'in stock',
                        'featured_label' => 'Featured gear',
                        'featured_button_label' => 'View specs',
                        'categories_eyebrow' => 'Departments',
                        'categories_title' => 'Shop by category',
                        'all_products_label' => 'All gear',
                        'catalog_eyebrow' => 'Catalog',
                        'catalog_title' => 'Ready to ship',
                        'catalog_description' => 'Choose a department or compare all laptops, repair tools, components, and accessories.',
                        'product_details_label' => 'View Specs',
                        'add_to_cart_label' => 'Add to Cart',
                    ],
                    'categories' => [
                        ['name' => 'Laptops', 'icon' => 'laptop', 'sort_order' => 1],
                        ['name' => 'PC Tools', 'icon' => 'tool', 'sort_order' => 2],
                        ['name' => 'Components', 'icon' => 'cpu', 'sort_order' => 3],
                        ['name' => 'Accessories', 'icon' => 'keyboard', 'sort_order' => 4],
                    ],
                    'products' => [
                        [
                            'category' => 'Laptops',
                            'name' => 'NovaBook Pro 14',
                            'price' => 1299.00,
                            'cost_price' => 945.00,
                            'stock_qty' => 8,
                            'low_stock_threshold' => 3,
                            'image_url' => '/demo-products/novabook-pro-14.svg',
                            'ai_description' => 'A slim 14-inch performance laptop for creators, students, and mobile teams who need speed without extra bulk. AI note: high-ticket item with strong margin; bundle it with a USB-C dock to lift order value.',
                        ],
                        [
                            'category' => 'Laptops',
                            'name' => 'Atlas Gaming 16',
                            'price' => 1699.00,
                            'cost_price' => 1240.00,
                            'stock_qty' => 4,
                            'low_stock_threshold' => 2,
                            'image_url' => '/demo-products/atlas-gaming-16.svg',
                            'ai_description' => 'A 16-inch gaming laptop tuned for high-refresh gameplay, streaming, and heavier creative workloads. AI note: stock is tight; prioritize ads only while availability stays above the reorder threshold.',
                        ],
                        [
                            'category' => 'PC Tools',
                            'name' => 'Precision Repair Toolkit',
                            'price' => 49.00,
                            'cost_price' => 18.00,
                            'stock_qty' => 31,
                            'low_stock_threshold' => 8,
                            'image_url' => '/demo-products/precision-repair-toolkit.svg',
                            'ai_description' => 'A compact driver and pry-tool kit for laptop repairs, SSD upgrades, and desktop maintenance. AI note: best used as an add-on near checkout because it converts well with laptops and components.',
                        ],
                        [
                            'category' => 'PC Tools',
                            'name' => 'Anti-Static Service Mat',
                            'price' => 34.00,
                            'cost_price' => 12.50,
                            'stock_qty' => 6,
                            'low_stock_threshold' => 6,
                            'image_url' => '/demo-products/anti-static-service-mat.svg',
                            'ai_description' => 'A grounded work mat that protects sensitive PC parts during upgrades and repair jobs. AI note: reorder soon; current stock is at the low-stock threshold and tool demand follows component sales.',
                        ],
                        [
                            'category' => 'Components',
                            'name' => '1TB NVMe Performance SSD',
                            'price' => 89.00,
                            'cost_price' => 51.00,
                            'stock_qty' => 18,
                            'low_stock_threshold' => 5,
                            'image_url' => '/demo-products/nvme-performance-ssd.svg',
                            'ai_description' => 'A fast 1TB NVMe SSD for laptop upgrades, gaming PCs, and workstation boot drives. AI note: strong cross-sell with repair toolkit; promote as the fastest upgrade under $100.',
                        ],
                        [
                            'category' => 'Accessories',
                            'name' => 'USB-C Docking Hub 8-in-1',
                            'price' => 79.00,
                            'cost_price' => 32.00,
                            'stock_qty' => 2,
                            'low_stock_threshold' => 5,
                            'image_url' => '/demo-products/usb-c-docking-hub.svg',
                            'ai_description' => 'An 8-in-1 USB-C hub for monitors, storage, Ethernet, and daily desk setups. AI note: urgent reorder recommended; low inventory may block laptop bundle sales.',
                        ],
                    ],
                    'customer' => [
                        'name' => 'Maya Reed',
                        'email' => 'maya.demo@example.com',
                        'phone' => '+1 555 1100',
                    ],
                    'orders' => [
                        [
                            'customer' => ['name' => 'Maya Reed', 'email' => 'maya.demo@example.com', 'phone' => '+1 555 1100'],
                            'days_ago' => 6,
                            'status' => 'delivered',
                            'payment_method' => 'online',
                            'payment_status' => 'paid',
                            'notes' => 'Demo tech order 001 - laptop bundle',
                            'items' => [
                                ['name' => 'NovaBook Pro 14', 'quantity' => 1],
                                ['name' => 'USB-C Docking Hub 8-in-1', 'quantity' => 1],
                            ],
                        ],
                        [
                            'customer' => ['name' => 'Karim Stone', 'email' => 'karim.demo@example.com', 'phone' => '+1 555 1101'],
                            'days_ago' => 4,
                            'status' => 'delivered',
                            'payment_method' => 'cash',
                            'payment_status' => 'paid',
                            'notes' => 'Demo tech order 002 - repair kit',
                            'items' => [
                                ['name' => 'Precision Repair Toolkit', 'quantity' => 3],
                                ['name' => 'Anti-Static Service Mat', 'quantity' => 2],
                            ],
                        ],
                        [
                            'customer' => ['name' => 'Nora Chen', 'email' => 'nora.demo@example.com', 'phone' => '+1 555 1102'],
                            'days_ago' => 2,
                            'status' => 'processing',
                            'payment_method' => 'online',
                            'payment_status' => 'paid',
                            'notes' => 'Demo tech order 003 - gaming setup',
                            'items' => [
                                ['name' => 'Atlas Gaming 16', 'quantity' => 1],
                                ['name' => '1TB NVMe Performance SSD', 'quantity' => 2],
                                ['name' => 'Precision Repair Toolkit', 'quantity' => 1],
                            ],
                        ],
                        [
                            'customer' => ['name' => 'Leo Park', 'email' => 'leo.demo@example.com', 'phone' => '+1 555 1103'],
                            'days_ago' => 0,
                            'status' => 'confirmed',
                            'payment_method' => 'cash',
                            'payment_status' => 'pending',
                            'notes' => 'Demo tech order 004 - upgrade parts',
                            'items' => [
                                ['name' => '1TB NVMe Performance SSD', 'quantity' => 4],
                                ['name' => 'USB-C Docking Hub 8-in-1', 'quantity' => 1],
                            ],
                        ],
                    ],
                ],
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

                $orders = $seed['orders'] ?? [[
                    'customer' => $seed['customer'],
                    'days_ago' => 1,
                    'status' => 'delivered',
                    'payment_method' => 'cash',
                    'payment_status' => 'paid',
                    'notes' => 'Seed sample order for '.$vendor->slug,
                    'items' => $vendor->products()->take(2)->get()->map(fn ($product) => [
                        'name' => $product->name,
                        'quantity' => 2,
                    ])->all(),
                ]];

                foreach ($orders as $orderData) {
                    $customer = $vendor->customers()->updateOrCreate(
                        ['email' => $orderData['customer']['email']],
                        $orderData['customer']
                    );

                    $orderedAt = now()->subDays($orderData['days_ago']);
                    $order = $vendor->orders()->updateOrCreate(
                        ['notes' => $orderData['notes']],
                        [
                            'customer_id' => $customer->id,
                            'total_amount' => 0,
                            'profit_amount' => 0,
                            'status' => $orderData['status'],
                            'payment_method' => $orderData['payment_method'],
                            'payment_status' => $orderData['payment_status'],
                        ]
                    );

                    $order->forceFill([
                        'created_at' => $orderedAt,
                        'updated_at' => $orderedAt,
                    ])->save();

                    $order->orderItems()->delete();

                    $total = 0;
                    $profit = 0;

                    foreach ($orderData['items'] as $itemData) {
                        $product = $vendor->products()->where('name', $itemData['name'])->firstOrFail();
                        $quantity = $itemData['quantity'];

                        $order->orderItems()->create([
                            'product_id' => $product->id,
                            'quantity' => $quantity,
                            'unit_price' => $product->price,
                            'cost_price' => $product->cost_price,
                            'created_at' => $orderedAt,
                        ]);

                        $total += $product->price * $quantity;
                        $profit += ($product->price - $product->cost_price) * $quantity;
                    }

                    $order->update([
                        'total_amount' => $total,
                        'profit_amount' => $profit,
                    ]);

                    Payment::updateOrCreate(
                        ['order_id' => $order->id],
                        [
                            'method' => $orderData['payment_method'],
                            'status' => $orderData['payment_status'],
                            'amount' => $order->total_amount,
                            'paid_at' => $orderData['payment_status'] === 'paid' ? $orderedAt : null,
                            'created_at' => $orderedAt,
                        ]
                    );
                }
            }
        });
    }
}
