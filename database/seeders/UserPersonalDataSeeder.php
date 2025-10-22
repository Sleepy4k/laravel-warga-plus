<?php

namespace Database\Seeders;

use App\Enums\Gender;
use App\Models\User;
use App\Models\UserPersonalData;
use Illuminate\Database\Seeder;

class UserPersonalDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (UserPersonalData::query()->withoutCache()->count() > 0) return;

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
                'user_id' => $users[0]['id'],
                'first_name' => 'Admin',
                'last_name' => 'Warga+',
                'gender' => Gender::MALE->value,
                'birth_date' => date('Y-m-d', '1985-01-01'),
                'job' => 'Administrator',
                'address' => 'Jl. Di Panjaitan No. 1'
            ],
            [
                'user_id' => $users[1]['id'],
                'first_name' => 'Pengurus',
                'last_name' => 'Warga+',
                'gender' => Gender::FEMALE->value,
                'birth_date' => date('Y-m-d', '1990-01-01'),
                'job' => 'Pengurus',
                'address' => 'Jl. Di Panjaitan No. 1'
            ]
        ];

        $payload = collect($payload)->map(function ($item) {
            $item['id'] = \Illuminate\Support\Str::uuid();
            $item['birth_date'] = encrypt($item['birth_date']);
            $item['job'] = encrypt($item['job']);
            $item['address'] = encrypt($item['address']);
            return $item;
        })->toArray();

        UserPersonalData::insert($payload);
    }

    /**
     * Create users for development environment.
     */
    private function createDevelopmentUsers(): void
    {
        $users = User::get('id');
        $personals = UserPersonalData::factory()->count($users->count())->make();
        $personals->each(function ($personal, $index) use ($users) {
            $personal->user_id = $users[$index]['id'];
            $personal->birth_date = encrypt($personal->birth_date);
            $personal->job = encrypt($personal->job);
            $personal->address = encrypt($personal->address);
        });

        UserPersonalData::insert($personals->toArray());
    }
}
