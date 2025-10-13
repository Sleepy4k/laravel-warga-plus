<?php

namespace App\Traits;

use App\Enums\ReportLogType;
use App\Exceptions\FailedToFinalizeInstallationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

trait FinishesInstallation
{
    use SystemLog;

    /**
     * Mark the application as installed.
     *
     * @return bool
     */
    private function markAsInstalled(): bool
    {
        $path = storage_path('.installed');
        $timestamp = date('Y-m-d H:i:s');
        $version = config('app.version', '1.0.0');
        $isNew = !file_exists($path);

        $content = ($isNew ? 'Installed' : 'Reinstalled') . " at: {$timestamp}\nVersion: {$version}\n";
        $flags = $isNew ? 0 : FILE_APPEND;

        if ($isNew || is_writable($path)) {
            return file_put_contents($path, $content, $flags) !== false;
        }

        return false;
    }

    /**
     * Finish the installation process.
     *
     * @throws FailedToFinalizeInstallationException
     *
     * @return void
     */
    protected function finishInstallation(): void
    {
        $errors = '';

        // Ensure database already migrated
        try {
            // Query to table migrations to check if already migrated
            DB::table('migrations')->first();
        } catch (\Exception) {
            $errors .= 'Database is not yet migrated, please run `php artisan migrate` first.\n';
        }

        if (!$this->markAsInstalled()) {
            $errors .= 'Unable to create installed file. Please try installation again.\n';
        }

        if ($errors !== '') {
            $this->sendReportLog(ReportLogType::ERROR, $errors);
            throw new FailedToFinalizeInstallationException($errors);
        }
    }

    /**
     * Migrate the database
     *
     * @return void
     */
    protected function migrate(): void
    {
        Artisan::call('migrate --force');
    }

    /**
     * Check if the database is already migrated
     */
    protected function isAlreadyMigrated(): bool
    {
        return Artisan::call('migrate:status') === 0;
    }

}
