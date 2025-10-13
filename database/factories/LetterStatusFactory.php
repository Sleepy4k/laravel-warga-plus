<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LetterStatus>
 */
class LetterStatusFactory extends Factory
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
                'status' => 'Segera',
            ],
            [
                'status' => 'Tindak Lanjut',
            ],
            [
                'status' => 'Diterima',
            ],
            [
                'status' => 'Ditolak',
            ],
            [
                'status' => 'Selesai',
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
