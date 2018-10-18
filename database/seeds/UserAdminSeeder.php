<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => \Config::get('user.admin_name'),
            'email' => \Config::get('user.admin_email'),
            'password' => Hash::make(\Config::get('user.admin_password')),
        ]);
        $user->assignRole('admin');

        $user = User::create([
            'name' => \Config::get('user.benevole_name'),
            'email' => \Config::get('user.benevole_email'),
            'password' => Hash::make(\Config::get('user.benevole_password')),
        ]);
        $user->assignRole('benevole');
    }
}
