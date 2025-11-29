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
        'governorate_id',
        'engineer_id',
        'city_id',
        'main_work_area_code',
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

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permission');
    }

    public function isSuperAdmin()
    {
        return $this->id === 1;
    }

  public function hasRole($role): bool
{
    if (!$this->role) {
        return false;
    }

    if (is_string($role)) {
        return $this->role->name === $role;
    }

    if (is_array($role)) {
        return in_array($this->role->name, $role);
    }

    if ($role instanceof \App\Models\Role) {
        return $this->role_id === $role->id;
    }

    return false;
}


    public function hasPermission($permission): bool
    {
        if ($this->isSuperAdmin()) return true;

        if (is_string($permission)) {
            if ($this->permissions()->where('name', $permission)->exists()) {
                return true;
            }
        } else {
            if ($this->permissions()->where('id', $permission->id)->exists()) {
                return true;
            }
        }

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

        public function governorate()
    {
        return $this->belongsTo(Constant::class, 'governorate_id');
    }

    public function city()
{
    return $this->belongsTo(Constant::class, 'city_id');
}

public function mainWorkArea()
{
    return $this->belongsTo(Constant::class, 'main_work_area_code');
}

public function engineer()
{
    return $this->belongsTo(Engineer::class, 'engineer_id');
}

public function notifications()
{
    return $this->hasMany(Notification::class)->latest();
}

public function unreadNotifications()
{
    return $this->hasMany(Notification::class)->unread();
}

public function unreadNotificationsCount()
{
    return $this->unreadNotifications()->count();
}

}
