<?php

namespace Database\Seeders;

use App\Models\Documentation;
use App\Models\KBArticle;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role as SpatieRole;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        foreach (Role::cases() as $case) {
            SpatieRole::findOrCreate($case->value, 'web');
        }

        User::factory()
            ->create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin'),
            ])
            ->assignRole(Role::Admin);

        $kb = Documentation::factory()->create();
        KBArticle::factory()->create([
            'documentation_id' => $kb->id,
        ]);
    }
}
