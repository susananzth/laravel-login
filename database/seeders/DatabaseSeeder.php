<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        \App\Models\User::factory()->create([
            'firstname' => 'Susana',
            'lastname' => 'Piñero',
            'username' => 'susananzth',
            'phone' => '982701107',
            'email' => 'me@susananzth.com',
            'password' => bcrypt('123456'),
            'created_at' => now(),
            'updated_at' => now(),

        ]);
        \App\Models\User::factory()->create([
            'firstname' => 'Super',
            'lastname' => 'Admin',
            'username' => 'supersu',
            'phone' => '123456789',
            'email' => 'admin@susananzth.com',
            'password' => bcrypt('123456'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \App\Models\User::factory(10)->create();
    }
}
