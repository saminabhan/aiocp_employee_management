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

            // Teams
            ['name' => 'teams.view', 'display_name' => 'عرض الفرق', 'category' => 'teams'],
            ['name' => 'teams.create', 'display_name' => 'إنشاء فريق جديد', 'category' => 'teams'],
            ['name' => 'teams.edit', 'display_name' => 'تعديل فريق', 'category' => 'teams'],
            ['name' => 'teams.delete', 'display_name' => 'حذف فريق', 'category' => 'teams'],

            //issues
            ['name' => 'issues.view', 'display_name' => 'عرض التذاكر', 'category' => 'issues'],
            ['name' => 'issues.create', 'display_name' => 'إنشاء تذكرة جديدة', 'category' => 'issues'],
            ['name' => 'issues.edit', 'display_name' => 'تعديل تذكرة', 'category' => 'issues'],
            ['name' => 'issues.delete', 'display_name' => 'حذف تذكرة', 'category' => 'issues'],

            //survey supervisors
            ['name' => 'survey.supervisor.view', 'display_name' => 'عرض مشرفي الحصر', 'category' => 'survey supervisors'],
            // ['name' => 'survey.supervisor.create', 'display_name' => 'إنشاء مشرفي حصر جدد', 'category' => 'survey supervisors'],
            // ['name' => 'survey.supervisor.edit', 'display_name' => 'تعديل مشرفي الحصر', 'category' => 'survey supervisors'],
            // ['name' => 'survey.supervisor.delete', 'display_name' => 'حذف مشرفي الحصر', 'category' => 'survey supervisors'],

            // Profile
            ['name' => 'profile.edit', 'display_name' => 'تعديل الملف الشخصي', 'category' => 'profile'],
            ['name' => 'profile.view', 'display_name' => 'عرض الملف الشخصي', 'category' => 'profile'],

            //Attendance
            ['name' => 'attendance.view', 'display_name' => 'عرض سجل الدوام اليومي', 'category' => 'attendance'],
            ['name' => 'attendance.create', 'display_name' => 'اضافة دوام اليومي', 'category' => 'attendance'],
            ['name' => 'attendance.edit', 'display_name' => 'تعديل دوام اليومي', 'category' => 'attendance'],
            ['name' => 'attendance.delete', 'display_name' => 'حذف دوام اليومي', 'category' => 'attendance'],

            //Sync
            ['name' => 'engineer_sync.view',   'display_name' => 'عرض مزامنة المهندسين',   'category' => 'engineer_sync'],
            ['name' => 'engineer_sync.create', 'display_name' => 'إضافة مزامنة مهندسين',  'category' => 'engineer_sync'],
            ['name' => 'engineer_sync.edit',   'display_name' => 'تعديل مزامنة مهندسين',  'category' => 'engineer_sync'],
            ['name' => 'engineer_sync.delete', 'display_name' => 'حذف مزامنة مهندسين',    'category' => 'engineer_sync'],

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
            'teams.view',
            'teams.create',
            'teams.edit',
            'attendance.view',
            'attendance.create',
            'attendance.edit',
            'attendance.delete'

        ])->pluck('id')->toArray();

        $governorateManager->permissions()->sync($permissionsForGovernorate);

                // -------- New Roles (Hierarchy) --------
        $fieldEngineer = Role::firstOrCreate([
            'name' => 'field_engineer'
        ],[
            'display_name' => 'مهندس حصر (ميداني)',
            'description' => 'مهندس ميداني يقوم بعمليات الحصر'
        ]);

        $surveySupervisor = Role::firstOrCreate([
            'name' => 'survey_supervisor'
        ],[
            'display_name' => 'مشرف مهندسين الحصر',
            'description' => 'مشرف على مهندسي الحصر الميدانيين'
        ]);

        $governorateManager = Role::firstOrCreate([
            'name' => 'governorate_manager'
        ],[
            'display_name' => 'مدير المحافظة',
            'description' => 'المسؤول عن إدارة محافظة'
        ]);

        $northSupport = Role::firstOrCreate([
            'name' => 'north_support'
        ],[
            'display_name' => 'دعم فني شمال غزة',
            'description' => 'الدعم الفني لشمال غزة وغزة'
        ]);

        $southSupport = Role::firstOrCreate([
            'name' => 'south_support'
        ],[
            'display_name' => 'دعم فني جنوب غزة',
            'description' => 'الدعم الفني لجنوب غزة والوسطى و خان يونس و رفح'
        ]);


        // Field Engineer permissions
        $fieldEngineer->permissions()->sync([
            Permission::where('name','issues.view')->first()->id,
            Permission::where('name','issues.create')->first()->id,

            Permission::where('name','dashboard.view')->first()->id,

            Permission::where('name','profile.view')->first()->id,
        ]);

        // Supervisor permissions
        $surveySupervisor->permissions()->sync([
            Permission::where('name','engineers.view')->first()->id,
            Permission::where('name','engineers.edit')->first()->id,

            Permission::where('name','issues.view')->first()->id,
            Permission::where('name','issues.create')->first()->id,

            Permission::where('name','teams.view')->first()->id,
            Permission::where('name','teams.create')->first()->id,
            Permission::where('name','teams.edit')->first()->id,
            
            Permission::where('name','dashboard.view')->first()->id,

            Permission::where('name','engineer_sync.view')->first()->id,
            Permission::where('name','engineer_sync.create')->first()->id,
            Permission::where('name','engineer_sync.edit')->first()->id,
            Permission::where('name','engineer_sync.delete')->first()->id,

        ]);

        // Governorate Manager permissions
        $governorateManager->permissions()->sync([
            Permission::where('name','engineers.view')->first()->id,
            Permission::where('name','engineers.edit')->first()->id,
            Permission::where('name','engineers.create')->first()->id,

            Permission::where('name','teams.view')->first()->id,
            Permission::where('name','teams.edit')->first()->id,
            Permission::where('name','teams.create')->first()->id,

            Permission::where('name','issues.view')->first()->id,
            Permission::where('name','issues.create')->first()->id,

            Permission::where('name','dashboard.view')->first()->id,

            Permission::where('name','survey.supervisor.view')->first()->id,

            Permission::where('name','attendance.view')->first()->id,
            Permission::where('name','attendance.create')->first()->id,
            Permission::where('name','attendance.edit')->first()->id,
            Permission::where('name','attendance.delete')->first()->id,
            
            Permission::where('name','engineer_sync.view')->first()->id,
            Permission::where('name','engineer_sync.create')->first()->id,
            Permission::where('name','engineer_sync.edit')->first()->id,
            Permission::where('name','engineer_sync.delete')->first()->id,

        ]);

        // North Support permissions
        $northSupport->permissions()->sync([
            Permission::where('name','dashboard.view')->first()->id,

            // view permissions
            Permission::where('name','users.view')->first()->id,
            Permission::where('name','engineers.view')->first()->id,
            Permission::where('name','teams.view')->first()->id,
            Permission::where('name','constants.view')->first()->id,
            Permission::where('name','issues.view')->first()->id,

            // edit ticket only
            Permission::where('name','issues.edit')->first()->id,
        ]);

        // South Support permissions
        $southSupport->permissions()->sync([
            Permission::where('name','dashboard.view')->first()->id,

            // view permissions
            Permission::where('name','users.view')->first()->id,
            Permission::where('name','engineers.view')->first()->id,
            Permission::where('name','teams.view')->first()->id,
            Permission::where('name','constants.view')->first()->id,
            Permission::where('name','issues.view')->first()->id,

            // edit ticket only
            Permission::where('name','issues.edit')->first()->id,
        ]);


    }
}
