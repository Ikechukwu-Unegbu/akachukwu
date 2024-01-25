<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Airtime', 
            'Data', 
            'Electricity',
            'Cable',
            'Wallet',
            'User'
        ];

        $permissions = [
            'view',
            'create',
            'edit',
            'delete'
        ];
        

        foreach ($roles as $role) { 
            $role = Role::create(['name' => $role]);

            foreach ($permissions as $permission) Permission::create(['name' => Str::lower($permission . ' ' . $role->name )]);
        }

    }
}
