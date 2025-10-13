<?php

namespace Database\Seeders;

use App\Models\LetterStatus;
use Illuminate\Database\Seeder;

class LetterStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (LetterStatus::query()->withoutCache()->count() > 0) return;

        $status = LetterStatus::factory()->make();

        LetterStatus::insert($status->toArray());
    }
}
