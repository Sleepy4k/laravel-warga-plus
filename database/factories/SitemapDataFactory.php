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
                'url' => '/',
                'change_frequency' => 'daily',
                'priority' => 1.0,
            ],
            [
                'url' => '/about-us',
                'change_frequency' => 'yearly',
                'priority' => 1.0,
            ],
            [
                'url' => '/report',
                'change_frequency' => 'daily',
                'priority' => 1.0,
            ],
            [
                'url' => '/information',
                'change_frequency' => 'daily',
                'priority' => 1.0,
            ],
            [
                'url' => '/login',
                'last_modified' => now()->subDays(2)->toDateTimeString(),
                'change_frequency' => 'monthly',
                'priority' => 0.9,
            ],
            [
                'url' => '/register',
                'last_modified' => now()->subDays(3)->toDateTimeString(),
                'change_frequency' => 'monthly',
                'priority' => 0.9,
            ],
            [
                'url' => '/dashboard',
                'last_modified' => now()->subDays(1)->toDateTimeString(),
                'change_frequency' => 'daily',
                'priority' => 0.8,
            ],
        ];

        $currentTime = now();

        foreach ($data as &$item) {
            $item = array_merge([
                'status' => 'active',
                'last_modified' => $currentTime->toDateTimeString(),
            ], $item);

            $item['url'] = $baseUrl . $item['url'];
            $item['created_at'] = $currentTime;
            $item['updated_at'] = $currentTime;
        }

        unset($item);

        return $data;
    }
}
