<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add the connector user for agro organisation first (ID 1)
        $connectorToken = Str::random(40);
        DB::table('users')->insert([
            'name' => 'connector valomnia',
            'email' => 'connector@valomnia.com',
            'password' => Hash::make('password'),
            'role' => 'ROLE_USER',
            'organisation' => 'agro',
            'token' => $connectorToken,
            'password_valomnia' => 'password',
            'cookies' => $connectorToken,
        ]);

        // Then add the Super ADMIN
        $token = Str::random(40); // 40 character random string
        DB::table('users')->insert([
            'name' => 'Super ADMIN',
            'email' => 'super_admin@alert.valomnia.com',
            'password' => Hash::make('password'),
            'role' => 'SUPER_ADMIN',
            'organisation' =>  "valomnia",
            'token' =>$token,
            'password_valomnia'=>NULL,
            'cookies' =>NULL,
        ]);
    }
}
