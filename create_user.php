<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

$token = Str::random(40);

try {
    User::create([
        'name' => 'connector valomnia',
        'email' => 'connector@valomnia.com',
        'password' => Hash::make('password'),
        'role' => 'ROLE_USER',
        'organisation' => 'agro',
        'token' => $token,
        'password_valomnia' => 'password',
        'cookies' => $token,
    ]);
    
    echo "User created successfully!\n";
    echo "Email: connector@valomnia.com\n";
    echo "Password: password\n";
    echo "Organisation: agro\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 