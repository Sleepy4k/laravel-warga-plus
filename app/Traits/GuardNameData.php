<?php

namespace App\Traits;

trait GuardNameData
{
    /**
     * Get list of guard name to set permission guard name
     *
     * @return array
     */
    protected function getGuardNameList(): array
    {
        $blacklistedGuards = ['sanctum'];

        $guardList = collect(config('auth.guards'))
            ->reject(function ($guard, $key) use ($blacklistedGuards) {
                foreach ($blacklistedGuards as $blacklisted) {
                    if (strpos($key, $blacklisted) !== false) {
                        return true;
                    }
                }
                return false;
            })
            ->map(function ($guard, $key) {
                return [
                    'value' => $key,
                    'label' => ucfirst($key),
                ];
            })
            ->values()
            ->toArray();

        return $guardList;
    }

    /**
     * Get default guard name for permission guard name
     *
     * @return array
     */
    private function getDefaultGuardName(): array
    {
        $default = config('auth.defaults.guard', 'web');

        return [
            'value' => $default,
            'label' => ucfirst($default),
        ];
    }
}
