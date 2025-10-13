<?php

namespace Database\Seeders;

use App\Models\DocumentCategory;
use Illuminate\Database\Seeder;

class DocumentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (DocumentCategory::query()->withoutCache()->count() > 0) return;

        $categories = DocumentCategory::factory()->make();

        DocumentCategory::insert($categories->toArray());
    }
}
