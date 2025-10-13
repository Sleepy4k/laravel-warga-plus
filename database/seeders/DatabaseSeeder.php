<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $isMustSilent = !file_exists(storage_path('.installed'));

        Artisan::call('optimize:clear', [
            '--no-interaction' => true,
            '--quiet' => $isMustSilent,
        ]);

        $this->call([
            SettingSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            MenuMetaSeeder::class,
            MenuSeeder::class,
            NavbarMenuMetaSeeder::class,
            NavbarMenuSeeder::class,
            UserSeeder::class,
            UserPersonalDataSeeder::class,
            UserAgreementSeeder::class,
            SitemapDataSeeder::class,
            ShortcutSeeder::class,
            LetterClassificationSeeder::class,
            LetterStatusSeeder::class,
            ActivityCategorySeeder::class,
            DocumentCategorySeeder::class,
        ], $isMustSilent);
    }
}
