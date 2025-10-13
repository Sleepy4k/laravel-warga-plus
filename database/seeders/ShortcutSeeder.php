<?php

namespace Database\Seeders;

use App\Models\Shortcut;
use Illuminate\Database\Seeder;

class ShortcutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Shortcut::query()->withoutCache()->count() > 0) return;

        $metas = Shortcut::factory()->make();

        Shortcut::insert($metas->toArray());
    }
}
