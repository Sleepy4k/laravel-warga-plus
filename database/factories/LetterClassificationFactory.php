<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LetterClassification>
 */
class LetterClassificationFactory extends Factory
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
                'name' => 'Administrasi',
                'description' => 'Surat-surat yang berkaitan dengan administrasi'
            ],
            [
                'name' => 'Pengembangan Sumber Daya Manusia',
                'description' => 'Surat-surat yang berkaitan dengan pengembangan sumber daya manusia'
            ],
            [
                'name' => 'Keuangan',
                'description' => 'Surat-surat yang berkaitan dengan keuangan'
            ],
            [
                'name' => 'Hubungan Masyarakat',
                'description' => 'Surat-surat yang berkaitan dengan hubungan masyarakat'
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
