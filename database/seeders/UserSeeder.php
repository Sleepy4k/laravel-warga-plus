<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::query()->withoutCache()->count() > 0) return;

        if (app()->isProduction()) {
            $this->createProductionUsers();
        } else {
            $this->createDevelopmentUsers();
        }
    }

    /**
     * Create users for production environment.
     */
    private function createProductionUsers(): void
    {
        $users = [
            [
                'phone' => '81234567890',
                'identity_number' => '3301234567890001',
                'password' => 'AdminWargaPlus2025*!',
                'role' => 'admin'
            ],
            [
                'phone' => '81234567891',
                'identity_number' => '3301234567890002',
                'password' => 'PengurusWargaPlus^&',
                'role' => 'pengurus'
            ]
        ];

        $uuids = collect(range(1, count($users)))
            ->map(fn() => (string) \Illuminate\Support\Str::uuid())
            ->sort()
            ->values()
            ->all();

        $currentTime = now();

        foreach ($users as $index => &$item) {
            $item['id'] = $uuids[$index];
            $item['verified_at'] = $currentTime;
            $item['created_at'] = $currentTime;
            $item['updated_at'] = $currentTime;
        }

        unset($item);

        array_map(function ($user) {
            $role = $user['role'];
            unset($user['role']);

            User::create($user)->assignRole($role);
        }, $users);
    }

    /**
     * Create users for development environment.
     */
    private function createDevelopmentUsers(): void
    {
        $users = [
            [
                'phone' => '81234567890',
                'identity_number' => '3301234567890001',
                'role' => 'admin'
            ],
            [
                'phone' => '81234567891',
                'identity_number' => '3301234567890002',
                'role' => 'pengurus'
            ],
            [
                'phone' => '81234567892',
                'identity_number' => '3301234567890003',
                'role' => 'user'
            ]
        ];

        $uuids = collect(range(1, count($users)))
            ->map(fn() => (string) \Illuminate\Support\Str::uuid())
            ->sort()
            ->values()
            ->all();

        $currentTime = now();

        foreach ($users as $index => &$item) {
            $item['id'] = $uuids[$index];
            $item['password'] = 'password';
            $item['verified_at'] = $currentTime;
            $item['created_at'] = $currentTime;
            $item['updated_at'] = $currentTime;
        }

        unset($item);

        array_map(function ($user) {
            $role = $user['role'];
            unset($user['role']);

            User::create($user)->assignRole($role);
        }, $users);
    }
}
