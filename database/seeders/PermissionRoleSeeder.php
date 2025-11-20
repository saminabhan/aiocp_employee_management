<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class PermissionRoleSeeder extends Seeder
{
    public function run(): void
    {
        // -------- Roles --------
        $adminRole = Role::firstOrCreate([
            'name' => 'admin'
        ],[
            'display_name' => 'مدير النظام',
            'description' => 'System Super Administrator'
        ]);


        // -------- Permissions --------
        $permissions = [
            // Dashboard
            ['name' => 'dashboard.view', 'display_name' => 'عرض لوحة التحكم', 'category' => 'Dashboard'],

            // Users
            ['name' => 'users.view', 'display_name' => 'عرض مستخدمين النظام', 'category' => 'Users'],
            ['name' => 'users.create', 'display_name' => 'إنشاء مستخدمين جدد للنظام', 'category' => 'Users'],
            ['name' => 'users.edit', 'display_name' => 'تعديل مستخدم للنظام', 'category' => 'Users'],
            ['name' => 'users.delete', 'display_name' => 'حذف مستخدم من النظام', 'category' => 'Users'],

            // Engineers
            ['name' => 'engineers.view', 'display_name' => 'عرض المهندسين', 'category' => 'engineers'],
            ['name' => 'engineers.create', 'display_name' => 'إنشاء مهندس جديد', 'category' => 'engineers'],
            ['name' => 'engineers.edit', 'display_name' => 'تعديل مهندس', 'category' => 'engineers'],
            ['name' => 'engineers.delete', 'display_name' => 'حذف مهندس', 'category' => 'engineers'],

            //Constants
            ['name' => 'constants.create', 'display_name' => 'إنشاء الثوابت', 'category' => 'constants'],
            ['name' => 'constants.view', 'display_name' => 'عرض الثوابت', 'category' => 'constants'],
            ['name' => 'constants.edit', 'display_name' => 'تعديل الثوابت', 'category' => 'constants'],
            ['name' => 'constants.delete', 'display_name' => 'حذف الثوابت', 'category' => 'constants'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm['name']],
                $perm
            );
        }


        // -------- Assign Permissions to Admin --------
        $adminRole->permissions()->sync(Permission::pluck('id')->toArray());

        // -------- Create Super Admin User (ID = 1) --------
        $adminUser = User::firstOrCreate([
            'username' => 'admin'
        ],[
            'name' => 'administrator',
            'password' => bcrypt('12345678'),
            'role_id' => $adminRole->id,
        ]);

        // Make sure user role_id = admin
        $adminUser->role_id = $adminRole->id;
        $adminUser->save();

        // Also assign ALL permissions directly (optional)
        $adminUser->permissions()->sync(Permission::pluck('id'));

        $governorateManager = Role::firstOrCreate([
    'name' => 'governorate_manager'
],[
    'display_name' => 'مدير المحافظة',
    'description' => 'المسؤول عن إدارة محافظة'
]);

// -------- Assign permissions to governorate manager --------
$permissionsForGovernorate = Permission::whereIn('name', [
    'engineers.view',
    'engineers.create',
    'engineers.edit',
    'dashboard.view',
])->pluck('id')->toArray();

$governorateManager->permissions()->sync($permissionsForGovernorate);


    }
}
