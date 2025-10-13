<?php

namespace Database\Seeders;

use App\Models\SitemapData;
use Illuminate\Database\Seeder;

class SitemapDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (SitemapData::query()->withoutCache()->count() > 0) return;

        $settings = SitemapData::factory()->make();

        SitemapData::insert($settings->toArray());
    }
}
