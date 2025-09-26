<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Rolleri oluştur
        $adminRole = Role::create(['name' => 'admin']);
        $dealerRole = Role::create(['name' => 'dealer']);
        $workerRole = Role::create(['name' => 'worker']);
        $centralWorkerRole = Role::create(['name' => 'central_worker']);

        // Temel izinleri oluştur
        $permissions = [
            'view_dashboard',
            'manage_users',
            'manage_dealers',
            'manage_workers',
            'manage_central_workers',
            'view_reports',
            'manage_settings',
            'view_analytics',
            'manage_products',
            'manage_orders',
            'view_financials',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Admin rolüne tüm izinleri ver
        $adminRole->givePermissionTo(Permission::all());

        // Dealer rolüne sınırlı izinler ver
        $dealerRole->givePermissionTo([
            'view_dashboard',
            'manage_workers',
            'view_reports',
            'manage_products',
            'manage_orders',
        ]);

        // Worker rolüne temel izinler ver
        $workerRole->givePermissionTo([
            'view_dashboard',
            'view_reports',
            'manage_products',
            'manage_orders',
        ]);

        // Central Worker rolüne merkez izinleri ver
        $centralWorkerRole->givePermissionTo([
            'view_dashboard',
            'view_reports',
            'view_analytics',
            'manage_products',
            'view_financials',
        ]);
    }
}
