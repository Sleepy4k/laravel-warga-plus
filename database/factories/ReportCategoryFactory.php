<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReportCategory>
 */
class ReportCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $data = [
            [
                'name' => 'kebersihan',
            ],
            [
                'name' => 'keamanan',
            ],
            [
                'name' => 'fasilitas umum',
            ],
            [
                'name' => 'lingkungan',
            ],
            [
                'name' => 'lainnya',
            ],
        ];

        $uuids = collect(range(1, count($data)))
            ->map(fn() => (string) \Illuminate\Support\Str::uuid())
            ->sort()
            ->values()
            ->all();

        $currentTime = now();

        foreach ($data as $index => &$item) {
            $item['id'] = $uuids[$index];
            $item['name'] = ucwords($item['name']);
            $item['created_at'] = $currentTime;
            $item['updated_at'] = $currentTime;
        }

        unset($item);

        return $data;
    }
}
