<?php

namespace Database\Seeders;

use App\Models\Hobby;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Unictive',
            'email' => 'unictive@mail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        Hobby::create([
            'name' => 'Programming',
            'user_id' => $user->id,
        ]);
    }
}
