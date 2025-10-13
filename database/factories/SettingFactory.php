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
        $defaultLogo = asset('hipmi.png', (bool) !config('app.debug', false));

        $data = [
            [
                'group' => 'app',
                'key' => 'name',
                'value' => config('app.name', 'HIPMI TUP CMS'),
            ],
            [
                'group' => 'app',
                'key' => 'description',
                'value' => 'Website resmi Himpunan Pengusaha Muda Indonesia (HIPMI) PT Telkom University Purwokerto.',
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
                'value' => config('app.name', 'HIPMI TUP CMS'),
            ],
            [
                'group' => 'seo',
                'key' => 'description',
                'value' => 'Website resmi Himpunan Pengusaha Muda Indonesia (HIPMI) PT Telkom University Purwokerto.',
            ],
            [
                'group' => 'seo',
                'key' => 'keywords',
                'value' => 'cms, hipmi, hipmi pt, pt, tup, telkom, university, universitas, telkom university, universitas telkom, purwokerto, banyumas, bpc, hipmi bpc, jawa tengah, hipmi jawa tengah, hipmi jateng',
            ],
            [
                'group' => 'seo',
                'key' => 'author',
                'value' => 'HIPMI TUP',
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
                'value' => 'HIPMI CMS',
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
                'value' => 'Research and Technology Division',
            ],
            [
                'group' => 'footer',
                'key' => 'copyright_url',
                'value' => 'https://github.com/hipmi-pt-tup',
            ],
            [
                'group' => 'footer',
                'key' => 'support_title',
                'value' => 'Support',
                'is_required' => false,
            ],
            [
                'group' => 'footer',
                'key' => 'support_url',
                'value' => 'https://github.com/hipmi-pt-tup/bantuan-pengguna',
                'is_required' => false,
            ],
            [
                'group' => 'footer',
                'key' => 'landing_page_title',
                'value' => 'Landing Page',
                'is_required' => false,
            ],
            [
                'group' => 'footer',
                'key' => 'landing_page_url',
                'value' => 'https://hipmi-tup.com',
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
