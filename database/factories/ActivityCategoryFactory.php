<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActivityCategory>
 */
class ActivityCategoryFactory extends Factory
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
                'name' => 'hiling',
                'description' => 'HIPMI Keliling'
            ],
            [
                'name' => 'pembinaan',
                'description' => 'HIPMI Pembinaan'
            ],
            [
                'name' => 'pengembangan',
                'description' => 'HIPMI Pengembangan'
            ],
            [
                'name' => 'pengabdian',
                'description' => 'HIPMI Pengabdian'
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
            $item['created_at'] = $currentTime;
            $item['updated_at'] = $currentTime;
        }

        unset($item);

        return $data;
    }
}
