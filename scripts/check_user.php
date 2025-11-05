<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

$user = User::where('username', 'org_admin')->first();

if ($user) {
    echo "User found:\n";
    print_r($user->toArray());

    $admin = $user->admin;
    if ($admin) {
        echo "\nAdmin found:\n";
        print_r($admin->toArray());

        $orgAdmin = $admin->organizationAdmins;
        if ($orgAdmin->isNotEmpty()) {
            echo "\nOrg Admin found:\n";
            print_r($orgAdmin->toArray());
        } else {
            echo "\nUser is not an Organization Admin.\n";
        }
    } else {
        echo "\nUser is not an Admin.\n";
    }
} else {
    echo "User not found.\n";
}