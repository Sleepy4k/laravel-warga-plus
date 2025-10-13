<?php

namespace Database\Seeders;

use App\Models\ActivityCategory;
use Illuminate\Database\Seeder;

class ActivityCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (ActivityCategory::query()->withoutCache()->count() > 0) return;

        $categories = ActivityCategory::factory()->make();

        ActivityCategory::insert($categories->toArray());
    }
}
