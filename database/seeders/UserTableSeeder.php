<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $super= User::create([
        	'name' => 'Admin',
        	'email' => 'admin@app.com',
        	'password' => Hash::make('12345678'),
        ]);

        $instructor= User::create([
        	'name' => 'Instructor',
        	'email' => 'instructor@app.com',
        	'password' => Hash::make('12345678'),
        ]);

        $blogger= User::create([
        	'name' => 'Blogger',
        	'email' => 'blogger@app.com',
        	'password' => Hash::make('12345678'),
        ]);
        
        $student= User::create([
        	'name' => 'Student',
        	'email' => 'student@app.com',
        	'password' => Hash::make('12345678'),
        ]);

        $roleSuperAdmin = Role::create(['name' => 'superadmin']);
        $roleInstructor = Role::create(['name' => 'instructor']);
        $roleBlogger = Role::create(['name' => 'blogger']);
        $roleStudent = Role::create(['name' => 'student']);
        //create permission
        $permissions = [
            [
                'group_name' => 'dashboard',
                'permissions' => [
                    'dashboard'
                ]
            ],
             [
                'group_name' => 'update',
                'permissions' => [
                    'update'
                ]
            ],
            [
                'group_name' => 'admin',
                'permissions' => [
                    'admin.create',
                    'admin.edit',
                    'admin.update',
                    'admin.delete',
                    'admin.list',

                ]
            ],
            [
                'group_name' => 'role',
                'permissions' => [
                    'role.create',
                    'role.get',
                    'role.update',
                    'role.delete',
                    'role.list',
                    'permission.list',

                ]
            ],

            [
                'group_name' => 'category',
                'permissions' => [
                    'category.create',
                    'category.get',
                    'category.update',
                    'category.delete',
                    'category.list',

                ]
            ],

            [
                'group_name' => 'course',
                'permissions' => [
                    'course.create',
                    'course.get',
                    'course.update',
                    'course.delete',
                    'course.list',

                ]
            ],

            [
                'group_name' => 'batch',
                'permissions' => [
                    'batch.create',
                    'batch.get',
                    'batch.update',
                    'batch.delete',
                    'batch.list',

                ]
            ],

            [
                'group_name' => 'lesson',
                'permissions' => [
                    'lesson.create',
                    'lesson.get',
                    'lesson.update',
                    'lesson.delete',
                    'lesson.list',

                ]
            ],
        ];

        //assign permission
        foreach ($permissions as $key => $row) {


            foreach ($row['permissions'] as $per) {
                $permission = Permission::create(['name' => $per, 'group_name' => $row['group_name']]);
                $roleSuperAdmin->givePermissionTo($permission);
                $permission->assignRole($roleSuperAdmin);
                $super->assignRole($roleSuperAdmin);
            }
        }

        $instructor->assignRole($roleInstructor);
        $blogger->assignRole($roleBlogger);
        $student->assignRole($roleStudent);
    }
}
