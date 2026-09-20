<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('providers')->insertOrIgnore(['id' => 1, 'name' => 'Originals', 'slug' => 'originals', 'description' => 'Jogos originais da casa', 'created_at' => now(), 'updated_at' => now()]);

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $influencer = Role::firstOrCreate(['name' => 'influencer', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        foreach (['view-dashboard', 'manage-games', 'manage-users', 'manage-affiliates', 'manage-banners'] as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $admin->syncPermissions(Permission::all());
        $influencer->syncPermissions(['view-dashboard']);

        if (!User::where('email', 'admin@demo.com')->exists()) {
            $adminUser = User::create([
                'name' => 'Admin',
                'last_name' => 'MarioBET',
                'email' => 'admin@demo.com',
                'password' => Hash::make('123456'),
                'status' => 'active',
            ]);
            $adminUser->assignRole('admin');
        }
    }
}
