<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SitemapData>
 */
class SitemapDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $baseUrl = config('app.url');
        $data = [
            [
                'url' => $baseUrl.'/',
                'last_modified' => now()->toDateTimeString(),
                'change_frequency' => 'daily',
                'priority' => 0.8,
            ],
            [
                'url' => $baseUrl.'/login',
                'last_modified' => now()->subDays(2)->toDateTimeString(),
                'change_frequency' => 'monthly',
                'priority' => 1.0,
            ],
            [
                'url' => $baseUrl.'/dashboard',
                'last_modified' => now()->subDays(1)->toDateTimeString(),
                'change_frequency' => 'daily',
                'priority' => 0.9,
            ],
            [
                'url' => $baseUrl.'/register',
                'last_modified' => now()->subDays(3)->toDateTimeString(),
                'change_frequency' => 'monthly',
                'priority' => 0.95,
            ],
        ];

        $currentTime = now();

        foreach ($data as &$item) {
            $item = array_merge([
                'status' => 'active',
            ], $item);

            $item['created_at'] = $currentTime;
            $item['updated_at'] = $currentTime;
        }

        unset($item);

        return $data;
    }
}
