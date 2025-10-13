<?php

if (!function_exists('isUserHasRole')) {
    /**
     * Check if the authenticated user has a specific role.
     *
     * @param string $role
     * @return bool
     */
    function isUserHasRole(string $role): bool
    {
        $user = auth('web')->user();
        return $user ? $user->hasRole($role) : false;
    }
}

if (!function_exists('getUserRole')) {
    /**
     * Get the role of the authenticated user.
     *
     * @return string|null
     */
    function getUserRole(): ?string
    {
        $user = auth('web')->user();
        return $user ? $user->getRoleNames()->first() : null;
    }
}

if (!function_exists('getUserPermissions')) {
    /**
     * Get the permissions of the authenticated user.
     *
     * @return array
     */
    function getUserPermissions(): array
    {
        $user = auth('web')->user();
        return $user ? $user->getAllPermissions()->pluck('name')->toArray() : [];
    }
}

if (!function_exists('canUserPerformAction')) {
    /**
     * Check if the authenticated user can perform a specific action.
     *
     * @param string $permission
     * @return bool
     */
    function canUserPerformAction(string $permission): bool
    {
        $user = auth('web')->user();
        return $user ? $user->can($permission) : false;
    }
}
