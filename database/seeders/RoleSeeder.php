<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'organizer']);
    Role::firstOrCreate(['name' => 'attendee']);
    Role::firstOrCreate(['name' => 'speaker']);
    Role::firstOrCreate(['name' => 'company']);
    Role::firstOrCreate(['name' => 'sponsor']);
    }
}
