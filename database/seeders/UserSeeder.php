<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'Lanza',
            'email' => 'lanzathm@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('secret'),
            'remember_token' => Str::random(10),
        ]);

        User::factory()->create([
            'name' => 'Dummy',
            'email' => 'dummy@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('secret'),
            'remember_token' => Str::random(10),
        ]);

        User::factory()->create([
            'name' => 'Jhon Doe',
            'email' => 'johndoe@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('secret'),
            'remember_token' => Str::random(10),
        ]);
    }
}
