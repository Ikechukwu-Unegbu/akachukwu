<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // 'manage utilities',
            'view data utility',
            'create data utility',
            'edit data utility',
            'delete data utility',
            'view cable utility',
            'create cable utility',
            'edit cable utility',
            'delete cable utility',
            'view electricity utility',
            'create electricity utility',
            'edit electricity utility',
            'delete electricity utility',
            // 'manage transactions',
            'view airtime transaction',
            'view data transaction',
            'view cable transaction',
            'view electricity transaction',
            // 'manage hr',
            'view users',
            'view administrators',
            // 'manage api',
            'view vendor api',
            'edit vendor api',
            'view payment api',
            'edit payment api',
            // 'manage settings',
            'view role',
            'create role',
            'edit role',
            'assign role'
        ];
        
        foreach ($permissions as $permission) Permission::create(['name' => $permission]);
    }
}