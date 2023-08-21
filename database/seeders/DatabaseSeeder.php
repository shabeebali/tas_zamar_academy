<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Admin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $superAdminRole = Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'admin'
        ]);

        $adminRole = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'admin'
        ]);

        Role::firstOrCreate([
            'name' => 'Staff',
            'guard_name' => 'admin'
        ]);

        $user = Admin::firstOrCreate([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
        ], [
            'password' => Hash::make('Admin@123')
        ]);

        $user->assignRole($superAdminRole);
        $user->assignRole($adminRole);
        $user = Admin::firstOrCreate([
            'name' => 'Admin 2',
            'email' => 'admin@example.com',
        ], [
            'password' => Hash::make('password')
        ]);
        $user->assignRole($adminRole);
    }
}
