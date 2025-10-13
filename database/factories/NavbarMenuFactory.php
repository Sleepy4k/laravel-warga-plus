<?php

namespace Database\Factories;

use App\Models\NavbarMenuMeta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class NavbarMenuFactory extends Factory
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
                'name' => 'My Profile',
            ],
            [
                'name' => 'Settings',
            ],
            [
                'name' => 'Misc Spacer',
                'is_spacer' => true,
            ],
            [
                'name' => 'Security',
            ],
            [
                'name' => 'Shortcuts',
            ],
        ];

        $currentTime = now();
        $menuMetaIds = NavbarMenuMeta::pluck('id')->toArray();
        $uuids = collect(range(1, count($data)))
            ->map(fn() => (string) \Illuminate\Support\Str::uuid())
            ->sort()
            ->values()
            ->all();

        foreach ($data as $index => &$item) {
            $item = array_merge([
                'is_spacer' => false,
            ], $item);

            $item['id'] = $uuids[$index];
            $item['order'] = $index + 1;
            $item['meta_id'] = $menuMetaIds[$index] ?? null;
            $item['created_at'] = $currentTime;
            $item['updated_at'] = $currentTime;
        }

        unset($item);

        return $data;
    }
}
