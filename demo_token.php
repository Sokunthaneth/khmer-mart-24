<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

use App\Models\User;

// Get or create a demo user
$user = User::firstOrCreate(
    ['email' => 'demo@example.com'],
    [
        'name' => 'Demo User',
        'password' => bcrypt('demo12345'),
    ]
);

// Create a token with all abilities
$token = $user->createToken('demo-token')->plainTextToken;

echo "Your API token: " . $token . "\n";
echo "Use this in your Authorization header as: Bearer {token}\n";
