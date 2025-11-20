<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'phone',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /** ----------------------------------------------------
     * RELATIONS
     * ----------------------------------------------------*/

    // User has one role (via role_id)
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Direct user permissions (optional)
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permission');
    }

    /** ----------------------------------------------------
     * CHECKS
     * ----------------------------------------------------*/

    // super admin id = 1
    public function isSuperAdmin()
    {
        return $this->id === 1;
    }

    // Check if user has a specific role
    public function hasRole($role): bool
    {
        if ($this->isSuperAdmin()) return true;

        if (is_string($role)) {
            return $this->role?->name === $role;
        }

        return $this->role_id === $role->id;
    }

    // Check permission (direct + role)
    public function hasPermission($permission): bool
    {
        if ($this->isSuperAdmin()) return true;

        // direct
        if (is_string($permission)) {
            if ($this->permissions()->where('name', $permission)->exists()) {
                return true;
            }
        } else {
            if ($this->permissions()->where('id', $permission->id)->exists()) {
                return true;
            }
        }

        // from role
        if ($this->role && $this->role->hasPermission($permission)) {
            return true;
        }

        return false;
    }

    // Assign direct permission
    public function givePermissionTo($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        $this->permissions()->syncWithoutDetaching([$permission->id]);
    }

    // Combine role + direct permissions
    public function getAllPermissions()
    {
        if ($this->isSuperAdmin()) {
            return Permission::all();
        }

        $rolePerms = $this->role ? $this->role->permissions : collect();
        $direct = $this->permissions;

        return $rolePerms->merge($direct)->unique('id');
    }
}
