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
            'Human Resource Mgt'
        ];

        $permissions = [
            'view',
            'create',
            'edit',
            'delete'
        ];
        
        foreach ($roles as $role) {
            // Create the role only if it doesn't exist
            $role = Role::firstOrCreate(['name' => $role]);
        
            foreach ($permissions as $permission) {
                // Create the permission only if it doesn't exist
                Permission::firstOrCreate([
                    'name' => Str::lower($permission . ' ' . $role->name)
                ]);
            }
        }

    }
}
