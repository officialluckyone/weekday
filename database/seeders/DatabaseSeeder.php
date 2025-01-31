<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Permission::create(['name'  => 'User Access']);
        Permission::create(['name'  => 'User Create']);
        Permission::create(['name'  => 'User Update']);
        Permission::create(['name'  => 'User Banned']);
        Permission::create(['name'  => 'Project Access']);
        Permission::create(['name'  => 'Project Create']);
        Permission::create(['name'  => 'Project Update']);
        Permission::create(['name'  => 'Project Delete']);
        Permission::create(['name'  => 'Project Status']);
        Permission::create(['name'  => 'Task Access']);
        Permission::create(['name'  => 'Task Create']);
        Permission::create(['name'  => 'Task Update']);
        Permission::create(['name'  => 'Task Delete']);
        Permission::create(['name'  => 'Task Status']);

        $superadmin = Role::create([
            'name'  => 'Super Admin',
        ]);

        $pm = Role::create([
            'name'  => 'Project Manager',
        ]);
        $pm->givePermissionTo([
            'Project Access',
            'Project Create',
            'Project Update',
            'Project Delete',
            'Project Status',
            'Task Access',
            'Task Create',
            'Task Update',
            'Task Delete',
            'Task Status'
        ]);

        $member = Role::create([
            'name'  => 'Member',
        ]);
        $member->givePermissionTo([
            'Project Access',
            'Task Access',
            'Task Status'
        ]);

        $sm = User::create([
            'name'      => 'Administrator',
            'email'     => 'administrator@gmail.com',
            'password'  => Hash::make('#W33kd4ys#'),
            'email_verified_at' => \Carbon\Carbon::now(),
        ]);

        $sm->assignRole('Super Admin');

        $pic = User::create([
            'name'      => 'Project Manager',
            'email'     => 'projectmanagaer@gmail.com',
            'password'  => Hash::make('#W33kd4ys#'),
            'email_verified_at' => \Carbon\Carbon::now(),
        ]);

        $pic->assignRole('Project Manager');

        $member = User::create([
            'name'      => 'Member',
            'email'     => 'member@gmail.com',
            'password'  => Hash::make('#W33kd4ys#'),
            'email_verified_at' => \Carbon\Carbon::now(),
        ]);
        $member->assignRole('Member');
    }
}
