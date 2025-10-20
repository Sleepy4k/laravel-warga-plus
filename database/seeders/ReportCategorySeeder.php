<?php

namespace Database\Seeders;

use App\Models\ReportCategory;
use Illuminate\Database\Seeder;

class ReportCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (ReportCategory::query()->withoutCache()->count() > 0) return;

        $categories = ReportCategory::factory()->make();

        ReportCategory::insert($categories->toArray());
    }
}
