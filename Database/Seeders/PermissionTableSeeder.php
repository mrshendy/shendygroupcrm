<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function deletePermissionsData()
    {
        // حذف جميع الأدوار والصلاحيات
        Role::truncate();
        Permission::truncate();

        // يمكنك أيضًا استخدام الأمر التالي إذا كنت تريد حذف الأدوار والصلاحيات مع حذف العلاقات الفرعية مثل الأذونات المعينة للأدوار.
        // Role::query()->delete();
        // Permission::query()->delete();

        return 'تم حذف البيانات من جداول الصلاحيات بنجاح.';
    }

    public function run()
    {
        $permissions = [
            // Programs
            [
                'name' => 'Dashboard',
                'title' => ['en' => 'Dashboard', 'ar' => 'الداش بور'],
                'category' => ['en' => 'Programs', 'ar' => 'البرامج'],
            ],
            [
                'name' => 'reports',
                'title' => ['en' => 'Reports', 'ar' => 'التقارير'],
                'category' => ['en' => 'Programs', 'ar' => 'البرامج'],
            ],
            [
                'name' => 'settings',
                'title' => ['en' => 'Settings', 'ar' => 'الاعدادات'],
                'category' => ['en' => 'Programs', 'ar' => 'البرامج'],
            ],
            [
                'name' => 'users',
                'title' => ['en' => 'User system', 'ar' => 'نظام المستخدمين'],
                'category' => ['en' => 'Programs', 'ar' => 'البرامج'],
            ],
            [
                'name' => 'clients',
                'title' => ['en' => 'Client system', 'ar' => 'نظام العملاء'],
                'category' => ['en' => 'Programs', 'ar' => 'البرامج'],
            ],
            [
                'name' => 'employees',
                'title' => ['en' => 'Employee system', 'ar' => 'نظام الموظفين'],
                'category' => ['en' => 'Programs', 'ar' => 'البرامج'],
            ],

            [
                'name' => 'projects',
                'title' => ['en' => 'Project system', 'ar' => 'نظام المشاريع'],
                'category' => ['en' => 'Programs', 'ar' => 'البرامج'],
            ],

            [
                'name' => 'offers',
                'title' => ['en' => 'Offers system', 'ar' => 'نظام العروض'],
                'category' => ['en' => 'Programs', 'ar' => 'البرامج'],
            ],
            [
                'name' => 'contracts',
                'title' => ['en' => 'Contract system', 'ar' => 'نظام العقود'],
                'category' => ['en' => 'Programs', 'ar' => 'البرامج'],
            ],
            [
                'name' => 'finance',
                'title' => ['en' => 'Finance system', 'ar' => 'نظام المالية'],
                'category' => ['en' => 'Programs', 'ar' => 'البرامج'],
            ],

            [
                'name' => 'attendance',
                'title' => ['en' => 'Attendance system', 'ar' => 'تسجيل حضور وانصراف'],
                'category' => ['en' => 'Programs', 'ar' => 'البرامج'],
            ],
            [
                'name' => 'leaves',
                'title' => ['en' => 'Leaves system', 'ar' => ' التقديم على اجازة'],
                'category' => ['en' => 'Programs', 'ar' => 'البرامج'],
            ],
            [
                'name' => 'notifications',
                'title' => ['en' => 'Notifications  system', 'ar' => ' الإشعارات'],
                'category' => ['en' => 'Programs', 'ar' => 'البرامج'],
            ],
            [
                'name' => 'help',
                'title' => ['en' => 'Help system', 'ar' => ' المساعدة'],
                'category' => ['en' => 'Programs', 'ar' => 'البرامج'],
            ],

            // permissions

            [
                'name' => 'role-list',
                'title' => ['en' => 'role list', 'ar' => 'قائمة الصلاحيات'],
                'category' => ['en' => 'permissions', 'ar' => 'الصلاحيات'],
            ],
            [
                'name' => 'role-create',
                'title' => ['en' => 'role create', 'ar' => 'انشاء صلاحيات'],
                'category' => ['en' => 'permissions', 'ar' => 'الصلاحيات'],
            ],
            [
                'name' => 'role-edit',
                'title' => ['en' => 'role edit', 'ar' => 'تعديل صلاحيات'],
                'category' => ['en' => 'permissions', 'ar' => 'الصلاحيات'],
            ],
            [
                'name' => 'role-delete',
                'title' => ['en' => 'role delete', 'ar' => 'حذف صلاحيات'],
                'category' => ['en' => 'permissions', 'ar' => 'الصلاحيات'],
            ],
            [
                'name' => 'role-show',
                'title' => ['en' => 'role show', 'ar' => 'عرض صلاحيات'],
                'category' => ['en' => 'permissions', 'ar' => 'الصلاحيات'],
            ],
            // client

            [
                'name' => 'client-list',
                'title' => ['en' => 'client list', 'ar' => 'قائمة العملاء'],
                'category' => ['en' => 'client', 'ar' => 'العملاء'],
            ],
            [
                'name' => 'client-create',
                'title' => ['en' => 'client create', 'ar' => 'انشاء عميل'],
                'category' => ['en' => 'client', 'ar' => 'العملاء'],
            ],
            [
                'name' => 'client-edit',
                'title' => ['en' => 'client edit', 'ar' => 'تعديل عميل'],
                'category' => ['en' => 'client', 'ar' => 'العملاء'],
            ],
            [
                'name' => 'client-delete',
                'title' => ['en' => 'client delete', 'ar' => 'حذف عميل'],
                'category' => ['en' => 'client', 'ar' => 'العملاء'],
            ],
            [
                'name' => 'client-show',
                'title' => ['en' => 'client show', 'ar' => 'عرض عميل'],
                'category' => ['en' => 'client', 'ar' => 'العملاء'],
            ],
            //employee
            [
                'name' => 'employee-list',
                'title' => ['en' => 'employee list', 'ar' => 'قائمة الموظفين'],
                'category' => ['en' => 'employee', 'ar' => 'الموظفين'],
            ],
            [
                'name' => 'employee-create',
                'title' => ['en' => 'employee create', 'ar' => 'انشاء موظف'],
                'category' => ['en' => 'employee', 'ar' => 'الموظفين'],
            ],
            [
                'name' => 'employee-edit',
                'title' => ['en' => 'employee edit', 'ar' => 'تعديل موظف'],
                'category' => ['en' => 'employee', 'ar' => 'الموظفين'],
            ],
            [
                'name' => 'employee-delete',
                'title' => ['en' => 'employee delete', 'ar' => 'حذف موظف'],
                'category' => ['en' => 'employee', 'ar' => 'الموظفين'],
            ],
            [
                'name' => 'employee-show',
                'title' => ['en' => 'employee show', 'ar' => 'عرض موظف'],
                'category' => ['en' => 'employee', 'ar' => 'الموظفين'],
            ],
            [
                'name' => 'employee-procedures',
                'title' => ['en' => 'employee procedures', 'ar' => 'الاجراءات'],
                'category' => ['en' => 'employee', 'ar' => 'الموظفين'],
            ],
            [
                'name' => 'employee-attendance',
                'title' => ['en' => 'employee attendance', 'ar' => 'ادارة حضور'],
                'category' => ['en' => 'employee', 'ar' => 'الموظفين'],
            ],
            [
                'name' => 'employee-leave',
                'title' => ['en' => 'employee leave', 'ar' => 'ادارة الاجازات'],
                'category' => ['en' => 'employee', 'ar' => 'الموظفين'],
            ],
            [
                'name' => 'employee-salary',
                'title' => ['en' => 'employee salary', 'ar' => 'ادارة مرتبات'],
                'category' => ['en' => 'employee', 'ar' => 'الموظفين'],
            ],
            [
                'name' => 'employee-shift',
                'title' => ['en' => 'employee shift', 'ar' => 'ادارة الشيفتات'],
                'category' => ['en' => 'employee', 'ar' => 'الموظفين'],
            ],

            // projects

            [
                'name' => 'project-list',
                'title' => ['en' => 'project list', 'ar' => 'قائمة المشاريع'],
                'category' => ['en' => 'project', 'ar' => 'المشاريع'],
            ],
            [
                'name' => 'project-create',
                'title' => ['en' => 'project create', 'ar' => 'انشاء مشروع'],
                'category' => ['en' => 'project', 'ar' => 'المشاريع'],
            ],
            [
                'name' => 'project-edit',
                'title' => ['en' => 'project edit', 'ar' => 'تعديل مشروع'],
                'category' => ['en' => 'project', 'ar' => 'المشاريع'],
            ],
            [
                'name' => 'project-delete',
                'title' => ['en' => 'project delete', 'ar' => 'حذف مشروع'],
                'category' => ['en' => 'project', 'ar' => 'المشاريع'],
            ],
            [
                'name' => 'project-show',
                'title' => ['en' => 'project show', 'ar' => 'عرض مشروع'],
                'category' => ['en' => 'project', 'ar' => 'المشاريع'],
            
            ],

            //offers
            [
                'name' => 'offer-list',
                'title' => ['en' => 'offer list', 'ar' => 'قائمة العروض'],
                'category' => ['en' => 'offer', 'ar' => 'العروض'],
            ],
            [
                'name' => 'offer-create',
                'title' => ['en' => 'offer create', 'ar' => 'انشاء عرض'],
                'category' => ['en' => 'offer', 'ar' => 'العروض'],
            ],
            [
                'name' => 'offer-edit',
                'title' => ['en' => 'offer edit', 'ar' => 'تعديل عرض'],
                'category' => ['en' => 'offer', 'ar' => 'العروض'],
            ],
            [
                'name' => 'offer-delete',
                'title' => ['en' => 'offer delete', 'ar' => 'حذف عرض'],
                'category' => ['en' => 'offer', 'ar' => 'العروض'],
            ],
            [
                'name' => 'offer-show',
                'title' => ['en' => 'offer show', 'ar' => 'عرض عرض'],
                'category' => ['en' => 'offer', 'ar' => 'العروض'],
            ],

             [
                'name' => 'offer-followup',
                'title' => ['en' => 'offer followup', 'ar' => 'متابعة عرض'],
                'category' => ['en' => 'offer', 'ar' => 'العروض'],
            ],
             [
                'name' => 'offer-status',
                'title' => ['en' => 'offer status', 'ar' => 'حالة عرض'],
                'category' => ['en' => 'offer', 'ar' => 'العروض'],
            ],
            //contracts
            [
                'name' => 'contract-list',
                'title' => ['en' => 'contract list', 'ar' => 'قائمة العقود'],
                'category' => ['en' => 'contract', 'ar' => 'العقود'],
            ],
            [
                'name' => 'contract-create',
                'title' => ['en' => 'contract create', 'ar' => 'انشاء عقد'],
                'category' => ['en' => 'contract', 'ar' => 'العقود'],
            ],

            [
                'name' => 'contract-edit',
                'title' => ['en' => 'contract edit', 'ar' => 'تعديل عقد'],
                'category' => ['en' => 'contract', 'ar' => 'العقود'],
            ],
            [
                'name' => 'contract-delete',
                'title' => ['en' => 'contract delete', 'ar' => 'حذف عقد'],
                'category' => ['en' => 'contract', 'ar' => 'العقود'],
            ],
            [
                'name' => 'contract-show',
                'title' => ['en' => 'contract show', 'ar' => 'عرض عقد'],
                'category' => ['en' => 'contract', 'ar' => 'العقود'],
            ],
            //finance
            [
                'name' => 'finance-list',
                'title' => ['en' => 'finance list', 'ar' => 'قائمة المالية'],
                'category' => ['en' => 'finance', 'ar' => 'المالية'],
            ],
            [
                'name' => 'finance-create',
                'title' => ['en' => 'finance create', 'ar' => 'انشاء مالية'],
                'category' => ['en' => 'finance', 'ar' => 'المالية'],
            ],
            [
                'name' => 'finance-create.expense',
                'title' => ['en' => 'finance create expense', 'ar' => 'انشاء مصروفات مالية'],
                'category' => ['en' => 'finance', 'ar' => 'المالية'],
            ], [
                'name' => 'finance-create.income',
                'title' => ['en' => 'finance create income', 'ar' => 'انشاء إيرادات مالية'],
                'category' => ['en' => 'finance', 'ar' => 'المالية'],
            ],

            [
                'name' => 'finance-edit',
                'title' => ['en' => 'finance edit', 'ar' => 'تعديل مالية'],
                'category' => ['en' => 'finance', 'ar' => 'المالية'],
            ],
            [
                'name' => 'finance-delete',
                'title' => ['en' => 'finance delete', 'ar' => 'حذف مالية'],
                'category' => ['en' => 'finance', 'ar' => 'المالية'],
            ],
            [
                'name' => 'finance-show',
                'title' => ['en' => 'finance show', 'ar' => 'عرض مالية'],
                'category' => ['en' => 'finance', 'ar' => 'المالية'],
            ],
            //attendance
            [
                'name' => 'attendance-list',
                'title' => ['en' => 'attendance list', 'ar' => 'قائمة الحضور'],
                'category' => ['en' => 'attendance', 'ar' => 'الحضور'],
            ],

            [
                'name' => 'attendance-create',
                'title' => ['en' => 'attendance create', 'ar' => 'انشاء حضور'],
                'category' => ['en' => 'attendance', 'ar' => 'الحضور'],
            ],
            [
                'name' => 'attendance-check-in',
                'title' => ['en' => 'attendance check-in', 'ar' => 'تسجيل حضور'],
                'category' => ['en' => 'attendance', 'ar' => 'الحضور'],
            ],
            [
                'name' => 'attendance-check-out',
                'title' => ['en' => 'attendance check-out', 'ar' => 'تسجيل انصراف'],
                'category' => ['en' => 'attendance', 'ar' => 'الحضور'],
            ],

            [
                'name' => 'attendance-edit',
                'title' => ['en' => 'attendance edit', 'ar' => 'تعديل حضور'],
                'category' => ['en' => 'attendance', 'ar' => 'الحضور'],
            ],
            [
                'name' => 'attendance-delete',
                'title' => ['en' => 'attendance delete', 'ar' => 'حذف حضور'],
                'category' => ['en' => 'attendance', 'ar' => 'الحضور'],
            ],
            [
                'name' => 'attendance-show',
                'title' => ['en' => 'attendance show', 'ar' => 'عرض حضور'],
                'category' => ['en' => 'attendance', 'ar' => 'الحضور'],
            ],
            // leaves
            [
                'name' => 'leaves-list',
                'title' => ['en' => 'leaves list', 'ar' => 'قائمة الإجازات'],
                'category' => ['en' => 'leaves', 'ar' => 'الإجازات'],
            ],
            [
                'name' => 'leaves-create',
                'title' => ['en' => 'leaves create', 'ar' => 'انشاء إجازة'],
                'category' => ['en' => 'leaves', 'ar' => 'الإجازات'],
            ],

            [
                'name' => 'leaves-edit',
                'title' => ['en' => 'leaves edit', 'ar' => 'تعديل إجازة'],
                'category' => ['en' => 'leaves', 'ar' => 'الإجازات'],
            ],
            [
                'name' => 'leaves-delete',
                'title' => ['en' => 'leaves delete', 'ar' => 'حذف إجازة'],
                'category' => ['en' => 'leaves', 'ar' => 'الإجازات'],
            ],
            [
                'name' => 'leaves-show',
                'title' => ['en' => 'leaves show', 'ar' => 'عرض إجازة'],
                'category' => ['en' => 'leaves', 'ar' => 'الإجازات'],
            ],

            // Users

            [
                'name' => 'users-list',
                'title' => ['en' => 'users list', 'ar' => 'قائمة المستخدمين'],
                'category' => ['en' => 'Users', 'ar' => 'المستخدمين'],
            ],
            [
                'name' => 'users-create',
                'title' => ['en' => 'users create', 'ar' => 'انشاء المستخدمين'],
                'category' => ['en' => 'Users', 'ar' => 'المستخدمين'],
            ],
            [
                'name' => 'users-edit',
                'title' => ['en' => 'users edit', 'ar' => 'تعديل المستخدمين'],
                'category' => ['en' => 'Users', 'ar' => 'المستخدمين'],
            ],
            [
                'name' => 'users-delete',
                'title' => ['en' => 'users delete', 'ar' => 'حذف المستخدمين'],
                'category' => ['en' => 'Users', 'ar' => 'المستخدمين'],
            ],
            [
                'name' => 'users-show',
                'title' => ['en' => 'users show', 'ar' => 'عرض المستخدمين'],
                'category' => ['en' => 'Users', 'ar' => 'المستخدمين'],
            ],

            [
                'name' => 'users-list',
                'title' => ['en' => 'users list', 'ar' => 'قائمة المستخدمين'],
                'category' => ['en' => 'Users', 'ar' => 'المستخدمين'],
            ],

        ];

        foreach ($permissions as $permissionData) {
            $permission = Permission::where('name', $permissionData['name'])
                ->where('guard_name', 'web')
                ->first();

            if (! $permission) {
                Permission::create([
                    'name' => $permissionData['name'],
                    'title' => $permissionData['title'],
                    'category' => $permissionData['category'],
                    'guard_name' => 'web',
                ]);
            }
        }
    }
}
