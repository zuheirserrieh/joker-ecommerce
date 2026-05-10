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
                            'stock_qty' => 12,
                            'low_stock_threshold' => 3,
                            'image_url' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=500&q=80',
                            'ai_description' => 'A slim 14-inch performance laptop for creators, students, and mobile teams. Equipped with Intel Core i7, 16GB RAM, and 512GB SSD for seamless multitasking.',
                        ],
                        [
                            'category' => 'Laptops',
                            'name' => 'Atlas Gaming 16',
                            'price' => 1699.00,
                            'cost_price' => 1240.00,
                            'stock_qty' => 8,
                            'low_stock_threshold' => 2,
                            'image_url' => 'https://images.unsplash.com/photo-1588872657840-218e86e742ba?w=500&q=80',
                            'ai_description' => 'A 16-inch gaming powerhouse with RTX 4060, 165Hz display, and advanced cooling. Perfect for competitive gaming and content creation.',
                        ],
                        [
                            'category' => 'Laptops',
                            'name' => 'UltraBook Air 13',
                            'price' => 899.00,
                            'cost_price' => 640.00,
                            'stock_qty' => 15,
                            'low_stock_threshold' => 4,
                            'image_url' => 'https://images.unsplash.com/photo-1516062423079-7ca13cdc7f5a?w=500&q=80',
                            'ai_description' => 'Lightweight 13-inch ultrabook weighing only 2.8 lbs. 24-hour battery life, perfect for professionals on the move.',
                        ],
                        [
                            'category' => 'PC Tools',
                            'name' => 'Precision Repair Toolkit',
                            'price' => 49.00,
                            'cost_price' => 18.00,
                            'stock_qty' => 45,
                            'low_stock_threshold' => 8,
                            'image_url' => 'https://images.unsplash.com/photo-1633802241481-7f8eea34d400?w=500&q=80',
                            'ai_description' => 'Complete 82-piece toolkit with precision screwdrivers, pry tools, and cleaning supplies. Professional grade for laptop and PC repairs.',
                        ],
                        [
                            'category' => 'PC Tools',
                            'name' => 'Anti-Static Service Mat',
                            'price' => 34.00,
                            'cost_price' => 12.50,
                            'stock_qty' => 20,
                            'low_stock_threshold' => 6,
                            'image_url' => 'https://images.unsplash.com/photo-1586253408898-fbb40a52f2d2?w=500&q=80',
                            'ai_description' => 'Grounded work mat (24" x 36") with wrist strap. Protects sensitive components during assembly and repair work.',
                        ],
                        [
                            'category' => 'PC Tools',
                            'name' => 'Thermal Paste & Cleaner Kit',
                            'price' => 22.00,
                            'cost_price' => 7.50,
                            'stock_qty' => 38,
                            'low_stock_threshold' => 10,
                            'image_url' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=500&q=80',
                            'ai_description' => 'Professional thermal paste with cleaning solution. Improves CPU/GPU cooling performance and extends hardware lifespan.',
                        ],
                        [
                            'category' => 'Components',
                            'name' => '1TB NVMe Performance SSD',
                            'price' => 89.00,
                            'cost_price' => 51.00,
                            'stock_qty' => 28,
                            'low_stock_threshold' => 5,
                            'image_url' => 'https://images.unsplash.com/photo-1630998318168-39953effb2b0?w=500&q=80',
                            'ai_description' => 'Samsung 990 Pro NVMe SSD. PCIe 4.0 speed, read speeds up to 7,100 MB/s. Ideal for gaming and professional workloads.',
                        ],
                        [
                            'category' => 'Components',
                            'name' => '32GB DDR5 RAM',
                            'price' => 139.00,
                            'cost_price' => 78.00,
                            'stock_qty' => 16,
                            'low_stock_threshold' => 4,
                            'image_url' => 'https://images.unsplash.com/photo-1620411441646-1abdd6b05fe2?w=500&q=80',
                            'ai_description' => 'Corsair DDR5 RAM (2x16GB). 5600MHz speed, CAS latency 36. Future-proof your system with next-gen memory.',
                        ],
                        [
                            'category' => 'Components',
                            'name' => 'RTX 4070 Graphics Card',
                            'price' => 549.00,
                            'cost_price' => 390.00,
                            'stock_qty' => 6,
                            'low_stock_threshold' => 2,
                            'image_url' => 'https://images.unsplash.com/photo-1596524430218-95e5e92d7000?w=500&q=80',
                            'ai_description' => 'NVIDIA RTX 4070 with 12GB GDDR6. Exceptional 1440p gaming and 4K content creation performance.',
                        ],
                        [
                            'category' => 'Accessories',
                            'name' => 'USB-C Docking Hub 8-in-1',
                            'price' => 79.00,
                            'cost_price' => 32.00,
                            'stock_qty' => 18,
                            'low_stock_threshold' => 5,
                            'image_url' => 'https://images.unsplash.com/photo-1579033127669-776a0245dd38?w=500&q=80',
                            'ai_description' => '8-in-1 USB-C hub with 4K HDMI, USB 3.0, SD card reader, and 100W power delivery. One cable to replace them all.',
                        ],
                        [
                            'category' => 'Accessories',
                            'name' => 'Premium Laptop Cooling Pad',
                            'price' => 59.00,
                            'cost_price' => 24.00,
                            'stock_qty' => 22,
                            'low_stock_threshold' => 6,
                            'image_url' => 'https://images.unsplash.com/photo-1518611505868-d2b4fd36b785?w=500&q=80',
                            'ai_description' => 'Dual fan cooling pad with 6-level height adjustment. Reduces laptop temperature by up to 15°C during gaming.',
                        ],
                        [
                            'category' => 'Accessories',
                            'name' => 'Mechanical Gaming Keyboard',
                            'price' => 119.00,
                            'cost_price' => 52.00,
                            'stock_qty' => 12,
                            'low_stock_threshold' => 4,
                            'image_url' => 'https://images.unsplash.com/photo-1587829191301-4e4f3e21c25c?w=500&q=80',
                            'ai_description' => 'RGB mechanical keyboard with Cherry MX switches. 144+ polling rate, programmable keys, and aluminum case.',
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
                            'days_ago' => 20,
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
                            'days_ago' => 18,
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
                            'days_ago' => 14,
                            'status' => 'delivered',
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
                            'days_ago' => 12,
                            'status' => 'delivered',
                            'payment_method' => 'cash',
                            'payment_status' => 'paid',
                            'notes' => 'Demo tech order 004 - upgrade parts',
                            'items' => [
                                ['name' => '1TB NVMe Performance SSD', 'quantity' => 4],
                                ['name' => 'USB-C Docking Hub 8-in-1', 'quantity' => 1],
                            ],
                        ],
                        [
                            'customer' => ['name' => 'Sarah Wilson', 'email' => 'sarah.tech@example.com', 'phone' => '+1 555 1104'],
                            'days_ago' => 10,
                            'status' => 'delivered',
                            'payment_method' => 'online',
                            'payment_status' => 'paid',
                            'notes' => 'Demo tech order 005 - cooling setup',
                            'items' => [
                                ['name' => 'Premium Laptop Cooling Pad', 'quantity' => 2],
                                ['name' => 'Thermal Paste & Cleaner Kit', 'quantity' => 1],
                            ],
                        ],
                        [
                            'customer' => ['name' => 'James Mitchell', 'email' => 'james.tech@example.com', 'phone' => '+1 555 1105'],
                            'days_ago' => 8,
                            'status' => 'delivered',
                            'payment_method' => 'online',
                            'payment_status' => 'paid',
                            'notes' => 'Demo tech order 006 - gaming peripherals',
                            'items' => [
                                ['name' => 'Mechanical Gaming Keyboard', 'quantity' => 1],
                                ['name' => 'Premium Laptop Cooling Pad', 'quantity' => 1],
                            ],
                        ],
                        [
                            'customer' => ['name' => 'Emma Thompson', 'email' => 'emma.tech@example.com', 'phone' => '+1 555 1106'],
                            'days_ago' => 5,
                            'status' => 'delivered',
                            'payment_method' => 'cash',
                            'payment_status' => 'paid',
                            'notes' => 'Demo tech order 007 - ultrabook',
                            'items' => [
                                ['name' => 'UltraBook Air 13', 'quantity' => 1],
                                ['name' => 'Mechanical Gaming Keyboard', 'quantity' => 1],
                            ],
                        ],
                        [
                            'customer' => ['name' => 'David Kumar', 'email' => 'david.tech@example.com', 'phone' => '+1 555 1107'],
                            'days_ago' => 3,
                            'status' => 'processing',
                            'payment_method' => 'online',
                            'payment_status' => 'paid',
                            'notes' => 'Demo tech order 008 - component upgrade',
                            'items' => [
                                ['name' => '32GB DDR5 RAM', 'quantity' => 1],
                                ['name' => 'RTX 4070 Graphics Card', 'quantity' => 1],
                            ],
                        ],
                        [
                            'customer' => ['name' => 'Jessica Brown', 'email' => 'jessica.tech@example.com', 'phone' => '+1 555 1108'],
                            'days_ago' => 1,
                            'status' => 'confirmed',
                            'payment_method' => 'cash',
                            'payment_status' => 'pending',
                            'notes' => 'Demo tech order 009 - toolkit bundle',
                            'items' => [
                                ['name' => 'Precision Repair Toolkit', 'quantity' => 1],
                                ['name' => 'Anti-Static Service Mat', 'quantity' => 1],
                                ['name' => 'Thermal Paste & Cleaner Kit', 'quantity' => 2],
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
                            'stock_qty' => 24,
                            'low_stock_threshold' => 4,
                            'image_url' => 'https://images.unsplash.com/photo-1551028719-00167b16ebc5?w=500&q=80',
                            'ai_description' => 'Lightweight bomber with crisp structure and modern cuts. Perfect for layering and elevated casual wear.',
                        ],
                        [
                            'category' => 'Jackets',
                            'name' => 'Classic Denim Jacket',
                            'price' => 79.99,
                            'cost_price' => 35.00,
                            'stock_qty' => 18,
                            'low_stock_threshold' => 5,
                            'image_url' => 'https://images.unsplash.com/photo-1551569867-47a0bde51b1a?w=500&q=80',
                            'ai_description' => 'Timeless dark denim jacket with reinforced seams. A versatile wardrobe essential that pairs with everything.',
                        ],
                        [
                            'category' => 'Jackets',
                            'name' => 'Leather Moto Jacket',
                            'price' => 189.00,
                            'cost_price' => 85.00,
                            'stock_qty' => 8,
                            'low_stock_threshold' => 2,
                            'image_url' => 'https://images.unsplash.com/photo-1551529834-42bebf3e6e06?w=500&q=80',
                            'ai_description' => 'Premium genuine leather moto jacket with asymmetrical zippered pockets. Bold statement piece with premium finishing.',
                        ],
                        [
                            'category' => 'Shirts',
                            'name' => 'Minimal Oxford',
                            'price' => 54.50,
                            'cost_price' => 21.00,
                            'stock_qty' => 35,
                            'low_stock_threshold' => 5,
                            'image_url' => 'https://images.unsplash.com/photo-1596215565018-e8b0a6b3a2d8?w=500&q=80',
                            'ai_description' => 'Clean oxford cloth shirt in classic white. Refined fit moves from office to evening wear seamlessly.',
                        ],
                        [
                            'category' => 'Shirts',
                            'name' => 'Striped Linen Shirt',
                            'price' => 65.00,
                            'cost_price' => 26.00,
                            'stock_qty' => 20,
                            'low_stock_threshold' => 4,
                            'image_url' => 'https://images.unsplash.com/photo-1596215565018-e8b0a6b3a2d8?w=500&q=80',
                            'ai_description' => 'Breathable navy and white striped linen. Perfect for warm weather while maintaining a polished look.',
                        ],
                        [
                            'category' => 'Shirts',
                            'name' => 'Oversized Tee',
                            'price' => 39.99,
                            'cost_price' => 15.00,
                            'stock_qty' => 42,
                            'low_stock_threshold' => 8,
                            'image_url' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=500&q=80',
                            'ai_description' => 'Relaxed fit oversized tee in premium cotton. Modern silhouette perfect for casual styling and comfort.',
                        ],
                        [
                            'category' => 'Accessories',
                            'name' => 'Canvas Crossbody',
                            'price' => 39.00,
                            'cost_price' => 14.50,
                            'stock_qty' => 22,
                            'low_stock_threshold' => 3,
                            'image_url' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=500&q=80',
                            'ai_description' => 'Durable canvas crossbody bag with adjustable strap. Compact yet spacious for daily essentials.',
                        ],
                        [
                            'category' => 'Accessories',
                            'name' => 'Leather Belt',
                            'price' => 49.99,
                            'cost_price' => 18.00,
                            'stock_qty' => 30,
                            'low_stock_threshold' => 6,
                            'image_url' => 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=500&q=80',
                            'ai_description' => 'Premium full-grain leather belt with minimalist buckle. Durable investment piece that elevates any outfit.',
                        ],
                        [
                            'category' => 'Accessories',
                            'name' => 'Wool Watch Cap',
                            'price' => 35.00,
                            'cost_price' => 12.50,
                            'stock_qty' => 26,
                            'low_stock_threshold' => 5,
                            'image_url' => 'https://images.unsplash.com/photo-1550258987-920a2eae3d3f?w=500&q=80',
                            'ai_description' => 'Classic charcoal wool beanie with ribbed knit. Warm, timeless, and pairs with any winter look.',
                        ],
                    ],
                    'orders' => [
                        [
                            'customer' => ['name' => 'Lina Ward', 'email' => 'lina.fusion@example.com', 'phone' => '+1 555 1200'],
                            'days_ago' => 16,
                            'status' => 'delivered',
                            'payment_method' => 'online',
                            'payment_status' => 'paid',
                            'notes' => 'Fusion order 001 - jacket combo',
                            'items' => [
                                ['name' => 'Urban Bomber', 'quantity' => 1],
                                ['name' => 'Minimal Oxford', 'quantity' => 2],
                            ],
                        ],
                        [
                            'customer' => ['name' => 'Alex Rivera', 'email' => 'alex.fashion@example.com', 'phone' => '+1 555 1201'],
                            'days_ago' => 13,
                            'status' => 'delivered',
                            'payment_method' => 'online',
                            'payment_status' => 'paid',
                            'notes' => 'Fusion order 002 - premium jacket',
                            'items' => [
                                ['name' => 'Leather Moto Jacket', 'quantity' => 1],
                                ['name' => 'Leather Belt', 'quantity' => 1],
                            ],
                        ],
                        [
                            'customer' => ['name' => 'Michelle Torres', 'email' => 'michelle.fashion@example.com', 'phone' => '+1 555 1202'],
                            'days_ago' => 11,
                            'status' => 'delivered',
                            'payment_method' => 'cash',
                            'payment_status' => 'paid',
                            'notes' => 'Fusion order 003 - casual shirts',
                            'items' => [
                                ['name' => 'Striped Linen Shirt', 'quantity' => 2],
                                ['name' => 'Oversized Tee', 'quantity' => 3],
                            ],
                        ],
                        [
                            'customer' => ['name' => 'Ryan Chen', 'email' => 'ryan.fashion@example.com', 'phone' => '+1 555 1203'],
                            'days_ago' => 9,
                            'status' => 'delivered',
                            'payment_method' => 'online',
                            'payment_status' => 'paid',
                            'notes' => 'Fusion order 004 - denim set',
                            'items' => [
                                ['name' => 'Classic Denim Jacket', 'quantity' => 1],
                                ['name' => 'Canvas Crossbody', 'quantity' => 1],
                            ],
                        ],
                        [
                            'customer' => ['name' => 'Sophie Martin', 'email' => 'sophie.fashion@example.com', 'phone' => '+1 555 1204'],
                            'days_ago' => 7,
                            'status' => 'delivered',
                            'payment_method' => 'cash',
                            'payment_status' => 'paid',
                            'notes' => 'Fusion order 005 - accessories',
                            'items' => [
                                ['name' => 'Leather Belt', 'quantity' => 1],
                                ['name' => 'Wool Watch Cap', 'quantity' => 2],
                                ['name' => 'Canvas Crossbody', 'quantity' => 1],
                            ],
                        ],
                        [
                            'customer' => ['name' => 'Marcus Johnson', 'email' => 'marcus.fashion@example.com', 'phone' => '+1 555 1205'],
                            'days_ago' => 4,
                            'status' => 'delivered',
                            'payment_method' => 'online',
                            'payment_status' => 'paid',
                            'notes' => 'Fusion order 006 - complete outfit',
                            'items' => [
                                ['name' => 'Urban Bomber', 'quantity' => 1],
                                ['name' => 'Oversized Tee', 'quantity' => 1],
                                ['name' => 'Wool Watch Cap', 'quantity' => 1],
                            ],
                        ],
                        [
                            'customer' => ['name' => 'Rachel Adams', 'email' => 'rachel.fashion@example.com', 'phone' => '+1 555 1206'],
                            'days_ago' => 2,
                            'status' => 'processing',
                            'payment_method' => 'online',
                            'payment_status' => 'paid',
                            'notes' => 'Fusion order 007 - jacket upgrade',
                            'items' => [
                                ['name' => 'Leather Moto Jacket', 'quantity' => 1],
                                ['name' => 'Minimal Oxford', 'quantity' => 1],
                            ],
                        ],
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
                            'stock_qty' => 32,
                            'low_stock_threshold' => 6,
                            'image_url' => 'https://images.unsplash.com/photo-1432139555190-58524dae6a55?w=500&q=80',
                            'ai_description' => 'Generous mixed grill with smoky lamb, chicken, and beef. Charred vegetables and house sauces. Perfect for hungry customers.',
                        ],
                        [
                            'category' => 'Grill',
                            'name' => 'Cedar Grilled Lamb Skewers',
                            'price' => 22.50,
                            'cost_price' => 10.00,
                            'stock_qty' => 18,
                            'low_stock_threshold' => 4,
                            'image_url' => 'https://images.unsplash.com/photo-1598103442097-8b74394b95c6?w=500&q=80',
                            'ai_description' => 'Tender marinated lamb skewers grilled over cedar. Authentic Middle Eastern flavors with aromatic smoke finish.',
                        ],
                        [
                            'category' => 'Grill',
                            'name' => 'Flame-Grilled Chicken Thighs',
                            'price' => 19.99,
                            'cost_price' => 8.50,
                            'stock_qty' => 28,
                            'low_stock_threshold' => 7,
                            'image_url' => 'https://images.unsplash.com/photo-1598928891346-96a05fddf8a5?w=500&q=80',
                            'ai_description' => 'Juicy bone-in chicken thighs char-grilled to perfection. Served with tahini sauce and grilled lemon.',
                        ],
                        [
                            'category' => 'Wraps',
                            'name' => 'Chicken Tawook Wrap',
                            'price' => 8.75,
                            'cost_price' => 3.10,
                            'stock_qty' => 60,
                            'low_stock_threshold' => 15,
                            'image_url' => 'https://images.unsplash.com/photo-1599974579688-403dbee803b0?w=500&q=80',
                            'ai_description' => 'Tender marinated chicken with crisp pickles and garlic sauce. Customer favorite for quick satisfying meal.',
                        ],
                        [
                            'category' => 'Wraps',
                            'name' => 'Lamb Shawarma Wrap',
                            'price' => 9.99,
                            'cost_price' => 3.50,
                            'stock_qty' => 42,
                            'low_stock_threshold' => 10,
                            'image_url' => 'https://images.unsplash.com/photo-1604482797202-7ad47f0e7b01?w=500&q=80',
                            'ai_description' => 'Slow-cooked lamb shawarma with tomato, onion, and tahini. Premium spiced meat wrap with authentic flavors.',
                        ],
                        [
                            'category' => 'Wraps',
                            'name' => 'Falafel & Hummus Wrap',
                            'price' => 7.50,
                            'cost_price' => 2.20,
                            'stock_qty' => 35,
                            'low_stock_threshold' => 8,
                            'image_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&q=80',
                            'ai_description' => 'Crispy falafel, creamy hummus, fresh salad, and tahini sauce. Popular vegetarian choice.',
                        ],
                        [
                            'category' => 'Sides',
                            'name' => 'Seasoned Fries',
                            'price' => 4.50,
                            'cost_price' => 1.20,
                            'stock_qty' => 75,
                            'low_stock_threshold' => 15,
                            'image_url' => 'https://images.unsplash.com/photo-1584621341511-c7b59b7eb0e4?w=500&q=80',
                            'ai_description' => 'Golden fries with signature house seasoning blend. Dependable add-on that boosts order value.',
                        ],
                        [
                            'category' => 'Sides',
                            'name' => 'Grilled Vegetables',
                            'price' => 5.99,
                            'cost_price' => 1.80,
                            'stock_qty' => 28,
                            'low_stock_threshold' => 6,
                            'image_url' => 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=500&q=80',
                            'ai_description' => 'Flame-charred bell peppers, zucchini, eggplant, and tomatoes. Perfect complement to any grill order.',
                        ],
                        [
                            'category' => 'Sides',
                            'name' => 'Fresh Hummus & Bread',
                            'price' => 6.50,
                            'cost_price' => 1.90,
                            'stock_qty' => 32,
                            'low_stock_threshold' => 8,
                            'image_url' => 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?w=500&q=80',
                            'ai_description' => 'House-made hummus with warm pita bread. Creamy texture and roasted chickpea flavor. Popular starter.',
                        ],
                    ],
                    'orders' => [
                        [
                            'customer' => ['name' => 'Omar Saleh', 'email' => 'omar.cedar@example.com', 'phone' => '+1 555 1300'],
                            'days_ago' => 15,
                            'status' => 'delivered',
                            'payment_method' => 'cash',
                            'payment_status' => 'paid',
                            'notes' => 'Cedar order 001 - family grill',
                            'items' => [
                                ['name' => 'Mixed Grill Platter', 'quantity' => 2],
                                ['name' => 'Seasoned Fries', 'quantity' => 2],
                            ],
                        ],
                        [
                            'customer' => ['name' => 'Fatima Hassan', 'email' => 'fatima.cedar@example.com', 'phone' => '+1 555 1301'],
                            'days_ago' => 12,
                            'status' => 'delivered',
                            'payment_method' => 'online',
                            'payment_status' => 'paid',
                            'notes' => 'Cedar order 002 - lunch wraps',
                            'items' => [
                                ['name' => 'Chicken Tawook Wrap', 'quantity' => 4],
                                ['name' => 'Fresh Hummus & Bread', 'quantity' => 1],
                            ],
                        ],
                        [
                            'customer' => ['name' => 'Ahmed Khalil', 'email' => 'ahmed.cedar@example.com', 'phone' => '+1 555 1302'],
                            'days_ago' => 10,
                            'status' => 'delivered',
                            'payment_method' => 'cash',
                            'payment_status' => 'paid',
                            'notes' => 'Cedar order 003 - lamb specialty',
                            'items' => [
                                ['name' => 'Cedar Grilled Lamb Skewers', 'quantity' => 3],
                                ['name' => 'Grilled Vegetables', 'quantity' => 1],
                                ['name' => 'Seasoned Fries', 'quantity' => 1],
                            ],
                        ],
                        [
                            'customer' => ['name' => 'Leila Mahmoud', 'email' => 'leila.cedar@example.com', 'phone' => '+1 555 1303'],
                            'days_ago' => 8,
                            'status' => 'delivered',
                            'payment_method' => 'online',
                            'payment_status' => 'paid',
                            'notes' => 'Cedar order 004 - vegetarian combo',
                            'items' => [
                                ['name' => 'Falafel & Hummus Wrap', 'quantity' => 2],
                                ['name' => 'Grilled Vegetables', 'quantity' => 2],
                            ],
                        ],
                        [
                            'customer' => ['name' => 'Hassan Ibrahim', 'email' => 'hassan.cedar@example.com', 'phone' => '+1 555 1304'],
                            'days_ago' => 6,
                            'status' => 'delivered',
                            'payment_method' => 'cash',
                            'payment_status' => 'paid',
                            'notes' => 'Cedar order 005 - chicken feast',
                            'items' => [
                                ['name' => 'Flame-Grilled Chicken Thighs', 'quantity' => 2],
                                ['name' => 'Lamb Shawarma Wrap', 'quantity' => 1],
                                ['name' => 'Seasoned Fries', 'quantity' => 2],
                            ],
                        ],
                        [
                            'customer' => ['name' => 'Mariam Yousef', 'email' => 'mariam.cedar@example.com', 'phone' => '+1 555 1305'],
                            'days_ago' => 4,
                            'status' => 'delivered',
                            'payment_method' => 'online',
                            'payment_status' => 'paid',
                            'notes' => 'Cedar order 006 - quick lunch',
                            'items' => [
                                ['name' => 'Chicken Tawook Wrap', 'quantity' => 2],
                                ['name' => 'Seasoned Fries', 'quantity' => 1],
                                ['name' => 'Fresh Hummus & Bread', 'quantity' => 1],
                            ],
                        ],
                        [
                            'customer' => ['name' => 'Karim Rashid', 'email' => 'karim.cedar@example.com', 'phone' => '+1 555 1306'],
                            'days_ago' => 2,
                            'status' => 'processing',
                            'payment_method' => 'cash',
                            'payment_status' => 'paid',
                            'notes' => 'Cedar order 007 - premium grill',
                            'items' => [
                                ['name' => 'Mixed Grill Platter', 'quantity' => 1],
                                ['name' => 'Cedar Grilled Lamb Skewers', 'quantity' => 2],
                            ],
                        ],
                        [
                            'customer' => ['name' => 'Zainab Ali', 'email' => 'zainab.cedar@example.com', 'phone' => '+1 555 1307'],
                            'days_ago' => 1,
                            'status' => 'confirmed',
                            'payment_method' => 'online',
                            'payment_status' => 'pending',
                            'notes' => 'Cedar order 008 - dinner order',
                            'items' => [
                                ['name' => 'Flame-Grilled Chicken Thighs', 'quantity' => 1],
                                ['name' => 'Lamb Shawarma Wrap', 'quantity' => 2],
                                ['name' => 'Grilled Vegetables', 'quantity' => 1],
                            ],
                        ],
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
