<?php

namespace Database\Seeders;

use App\Models\InformationCategory;
use Illuminate\Database\Seeder;

class InformationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (InformationCategory::query()->withoutCache()->count() > 0) return;

        $categories = InformationCategory::factory()->make();

        InformationCategory::insert($categories->toArray());
    }
}
