<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Setting::query()->withoutCache()->count() > 0) return;

        $settings = Setting::factory()->make();

        Setting::insert($settings->toArray());
    }
}
