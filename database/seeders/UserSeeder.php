<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = Role::all();

        for ($i = 1; $i <= 30; $i++) {
            User::create([
                'first_name' => 'User' . $i,
                'last_name' => 'Last' . $i,
                'email' => 'user' . $i . '@example.com',
                'phone' => '123456789' . $i,
                'location' => 'Location ' . $i,
                'profile_picture' => 'profile' . $i . '.jpg',
                'password' => Hash::make('password' . $i),
                'role_id' => $roles->random()->id,
            ]);
        }
    }
}
