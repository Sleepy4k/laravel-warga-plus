<?php

namespace Database\Seeders;

use App\Models\LetterClassification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LetterClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (LetterClassification::query()->withoutCache()->count() > 0) return;

        $classifications = LetterClassification::factory()->make();

        LetterClassification::insert($classifications->toArray());
    }
}
