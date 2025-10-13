<?php

namespace Database\Seeders;

use App\Models\NavbarMenu;
use Illuminate\Database\Seeder;

class NavbarMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (NavbarMenu::query()->withoutCache()->count() > 0) return;

        $metas = NavbarMenu::factory()->make();

        NavbarMenu::insert($metas->toArray());
    }
}
