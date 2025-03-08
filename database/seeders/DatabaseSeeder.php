<?php

namespace Database\Seeders;

use App\Models\UnitType;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        User::create([
            'name'   => 'صادق بیگلر',
            'n_code' => '4400176134',
            'password' => Hash::make('12345678'),
        ]);
        $this->call([
            ProvinceSeeder::class,
            CountySeeder::class,
            UnitTypeSeeder::class,
            UnitTypeRelationshipSeeder::class,
            OrganizationalUnitSeeder::class,


        ]);
    }
}
