<?php

namespace App\Services\Install;

use App\Foundations\Service;
use App\Support\PermissionsChecker;

class PermissionService extends Service
{
    /**
     * Create a new service instance.
     *
     * @param PermissionsChecker $checker
     */
    public function __construct(private PermissionsChecker $checker) {}

    /**
     * Handle the incoming request.
     *
     * @return array
     */
    public function invoke(): array
    {
        try {
            $permissions = $this->checker->check();
            $process_user = !function_exists('posix_getpwuid') ? get_current_user() : posix_getpwuid(posix_geteuid())['name'];

            return compact('permissions', 'process_user');
        } catch (\Exception $e) {
            throw new \Exception('Could not check permissions: '.$e->getMessage());
        }
    }
}
