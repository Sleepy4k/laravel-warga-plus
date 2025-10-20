<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Setting>
 */
class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $defaultLogo = asset('warga-plus.png', (bool) !config('app.debug', false));

        $data = [
            [
                'group' => 'app',
                'key' => 'name',
                'value' => config('app.name', 'WARGA PLUS'),
            ],
            [
                'group' => 'app',
                'key' => 'description',
                'value' => 'Sistem Informasi Manajemen Warga dan RT berbasis web.',
            ],
            [
                'group' => 'app',
                'key' => 'logo',
                'value' => $defaultLogo,
                'is_required' => false,
                'is_file' => true,
            ],
            [
                'group' => 'app',
                'key' => 'favicon',
                'value' => $defaultLogo,
                'is_required' => false,
                'is_file' => true,
            ],
            [
                'group' => 'app',
                'key' => 'timezone',
                'value' => config('app.timezone', 'UTC'),
            ],
            [
                'group' => 'seo',
                'key' => 'title',
                'value' => config('app.name', 'WARGA PLUS'),
            ],
            [
                'group' => 'seo',
                'key' => 'description',
                'value' => 'Sistem Informasi Manajemen Warga dan RT berbasis web.',
            ],
            [
                'group' => 'seo',
                'key' => 'keywords',
                'value' => 'warga, rt, sistem informasi, manajemen warga, web',
            ],
            [
                'group' => 'seo',
                'key' => 'author',
                'value' => 'WARGA PLUS',
            ],
            [
                'group' => 'seo',
                'key' => 'image_width',
                'value' => 1200,
                'type' => 'integer',
            ],
            [
                'group' => 'seo',
                'key' => 'image_height',
                'value' => 630,
                'type' => 'integer',
            ],
            [
                'group' => 'sidebar',
                'key' => 'name',
                'value' => 'WARGA PLUS',
            ],
            [
                'group' => 'sidebar',
                'key' => 'name_size',
                'value' => 1.5,
                'type' => 'float',
            ],
            [
                'group' => 'sidebar',
                'key' => 'logo',
                'value' => $defaultLogo,
                'is_required' => false,
                'is_file' => true,
            ],
            [
                'group' => 'footer',
                'key' => 'copyright',
                'value' => 'Telkom University Purwokerto',
            ],
            [
                'group' => 'footer',
                'key' => 'copyright_url',
                'value' => 'https://purwokerto.telkomuniversity.ac.id',
            ],
            [
                'group' => 'footer',
                'key' => 'support_title',
                'value' => 'About Us',
                'is_required' => false,
            ],
            [
                'group' => 'footer',
                'key' => 'support_url',
                'value' => 'https://purwokerto.telkomuniversity.ac.id',
                'is_required' => false,
            ],
        ];

        $currentTime = now();

        foreach ($data as &$item) {
            $item = array_merge([
                'type' => 'string',
                'is_required' => true,
                'is_file' => false,
            ], $item);

            $item['created_at'] = $currentTime;
            $item['updated_at'] = $currentTime;
        }

        unset($item);

        return $data;
    }
}
