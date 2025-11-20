<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('user_can')) {
    function user_can($permissionName)
    {
        $user = Auth::user();
        if (!$user) return false;

        if ($user->role && $user->role->name === 'admin') {
            return true;
        }

        if ($user->permissions->contains('name', $permissionName)) {
            return true;
        }

        return $user->role?->permissions->contains('name', $permissionName);
    }
}
