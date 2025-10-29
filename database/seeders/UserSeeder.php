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

        array_map(function ($user) {
            $role = $user['role'];
            unset($user['role']);
            $user['verified_at'] = now();

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

        array_map(function ($user) {
            $role = $user['role'];
            unset($user['role']);
            $user['password'] = 'password';
            $user['verified_at'] = now();

            User::create($user)->assignRole($role);
        }, $users);
    }
}
