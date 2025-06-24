<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "Users in database:\n";
echo "==================\n";

$users = User::all();
foreach ($users as $user) {
    echo "ID: {$user->id} - {$user->name} ({$user->email}) - Organisation: {$user->organisation} - Role: {$user->role}\n";
}

echo "\nTotal users: " . $users->count() . "\n";

// Check if connector user exists
$connector = User::where('email', 'connector@valomnia.com')->first();
if ($connector) {
    echo "\nConnector user found with ID: {$connector->id}\n";
} else {
    echo "\nConnector user NOT found!\n";
} 