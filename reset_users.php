<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

echo "Resetting users table...\n";

// Delete all users
DB::table('users')->delete();

// Reset auto-increment
DB::statement('ALTER TABLE users AUTO_INCREMENT = 1');

echo "Creating users in correct order...\n";

// Create connector user first (ID 1)
$connectorToken = Str::random(40);
User::create([
    'name' => 'connector valomnia',
    'email' => 'connector@valomnia.com',
    'password' => Hash::make('password'),
    'role' => 'ROLE_USER',
    'organisation' => 'agro',
    'token' => $connectorToken,
    'password_valomnia' => 'password',
    'cookies' => $connectorToken,
]);

// Create super admin (ID 2)
$adminToken = Str::random(40);
User::create([
    'name' => 'Super ADMIN',
    'email' => 'super_admin@alert.valomnia.com',
    'password' => Hash::make('password'),
    'role' => 'SUPER_ADMIN',
    'organisation' => 'valomnia',
    'token' => $adminToken,
    'password_valomnia' => null,
    'cookies' => null,
]);

echo "Users created successfully!\n";
echo "==================\n";

$users = User::all();
foreach ($users as $user) {
    echo "ID: {$user->id} - {$user->name} ({$user->email}) - Organisation: {$user->organisation}\n";
}

echo "\nTotal users: " . $users->count() . "\n"; 