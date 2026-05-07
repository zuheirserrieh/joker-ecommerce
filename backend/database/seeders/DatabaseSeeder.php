<?php

namespace Database\Seeders;

use App\Models\SuperAdmin;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'admin',
        ]);

        $vendorRole = Role::firstOrCreate([
            'name' => 'vendor',
            'guard_name' => 'vendor',
        ]);

        $admin = SuperAdmin::updateOrCreate(
            ['email' => 'admin@marketos.test'],
            [
                'name' => 'MarketOS Admin',
                'password' => 'password',
            ]
        );

        $vendor = Vendor::updateOrCreate(
            ['email' => 'vendor@marketos.test'],
            [
                'name' => 'Demo Vendor',
                'slug' => 'demo-vendor',
                'market_type' => 'general',
                'phone' => '+1 555 0100',
                'is_active' => true,
                'password' => 'password',
            ]
        );

        $admin->assignRole($adminRole);
        $vendor->assignRole($vendorRole);

        $this->call(VendorShowcaseSeeder::class);
    }
}
