<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Package;
class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    Package::create([
        'name' => 'bronze',
        'logo_size' => 'small',
        'booth' => null,
        'speaking_slot' => null,
        'tickets' => 2,
        'price' => 100,
    ]);

    Package::create([
        'name' => 'silver',
        'logo_size' => 'medium',
        'booth' => 'small table',
        'speaking_slot' => '10 minutes',
        'tickets' => 4,
        'price' => 150,
    ]);

    Package::create([
        'name' => 'gold',
        'logo_size' => 'big',
        'booth' => 'booth (3x3)',
        'speaking_slot' => '30 minutes',
        'tickets' => 8,
        'price' => 250,
    ]); 
}
}
