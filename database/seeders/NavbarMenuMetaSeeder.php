<?php

namespace Database\Seeders;

use App\Models\NavbarMenuMeta;
use Illuminate\Database\Seeder;

class NavbarMenuMetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (NavbarMenuMeta::query()->withoutCache()->count() > 0) return;

        $metas = NavbarMenuMeta::factory()->make();

        NavbarMenuMeta::insert($metas->toArray());
    }
}
