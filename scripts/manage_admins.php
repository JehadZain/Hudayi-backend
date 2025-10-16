<?php
// Script to list users and admin users, and create an admin if none found.
// Run: php scripts/manage_admins.php

require __DIR__ . '/../vendor/autoload.php';

// Bootstrap the Laravel application
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

echo "Listing users (id, name, email, username, status, role)\n";
$users = User::select('id','name','email','username','status')->orderBy('id','asc')->get();
if ($users->isEmpty()) {
    echo "No users found.\n";
} else {
    foreach ($users as $u) {
        echo "- {$u->id} | {$u->name} | {$u->email} | " . ($u->username ?? '') . " | status={$u->status}\n";
    }
}

// Detect admin users: by role fields or organization link. We'll try common conventions.
echo "\nSearching for admin users (heuristics)...\n";
$admins = [];
// Heuristic 1: users with email containing 'admin'
$admins = User::where('email','like','%admin%')->orWhere('username','like','%admin%')->get();

if ($admins->isEmpty()) {
    echo "No admin-like users found by email/username.\n";
} else {
    echo "Admin-like users:\n";
    foreach ($admins as $a) {
        echo "- {$a->id} | {$a->name} | {$a->email} | status={$a->status}\n";
    }
}

// If no admin-like users, create one.
if ($admins->isEmpty()) {
    echo "\nCreating admin user 'local-admin@example.test' with password 'org_admin' and status=1...\n";
    $u = User::create([
        'name' => 'Local Admin',
        'email' => 'local-admin@example.test',
        'password' => bcrypt('org_admin'),
    ]);
    // Ensure status field exists and set it
    if (Schema::hasColumn('users','status')) {
        $u->status = 1;
        $u->save();
    }
    echo "Created user id={$u->id} email={$u->email}\n";
    echo "Note: If organization linking is required, you may need to create organization and organization_admin records.\n";
}

echo "Done.\n";
