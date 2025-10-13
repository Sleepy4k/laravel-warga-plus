<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Menu::query()->withoutCache()->count() > 0) return;

        $metas = Menu::factory()->make();

        Menu::insert($metas->toArray());
    }
}
