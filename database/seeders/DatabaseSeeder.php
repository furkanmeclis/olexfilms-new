<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Önce rolleri oluştur
        $this->call([
            RoleSeeder::class,
        ]);

        // Test kullanıcıları oluştur
        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
        $adminUser->assignRole('admin');

        $dealerUser = User::factory()->create([
            'name' => 'Dealer User',
            'email' => 'dealer@example.com',
        ]);
        $dealerUser->assignRole('dealer');

        $workerUser = User::factory()->create([
            'name' => 'Worker User',
            'email' => 'worker@example.com',
            'dealer_id' => $dealerUser->id,
        ]);
        $workerUser->assignRole('worker');

        $centralWorkerUser = User::factory()->create([
            'name' => 'Central Worker User',
            'email' => 'central@example.com',
        ]);
        $centralWorkerUser->assignRole('central_worker');
    }
}
