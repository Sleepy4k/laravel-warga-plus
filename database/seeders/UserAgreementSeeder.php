<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserAgreement;
use Illuminate\Database\Seeder;

class UserAgreementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (UserAgreement::query()->withoutCache()->count() > 0) return;

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
        $users = User::get('id');
        $payload = [
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'user_id' => $users[0]['id'],
                'agreement' => true,
                'privacy_policy' => true,
                'newsletter' => false,
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'user_id' => $users[1]['id'],
                'agreement' => true,
                'privacy_policy' => true,
                'newsletter' => false,
            ]
        ];

        UserAgreement::insert($payload);
    }

    /**
     * Create users for development environment.
     */
    private function createDevelopmentUsers(): void
    {
        $users = User::get('id');
        $agreements = UserAgreement::factory()->count($users->count())->make();
        $agreements->each(function ($agreement, $index) use ($users) {
            $agreement->user_id = $users[$index]['id'];
        });

        UserAgreement::insert($agreements->toArray());
    }
}
