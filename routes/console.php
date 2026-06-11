<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('admin:promote {email} {role=admin : admin, super-admin, or user}', function (string $email, string $role) {
    if (!in_array($role, ['user', 'admin', 'super-admin'], true)) {
        $this->error('Role must be one of: user, admin, super-admin.');
        return 1;
    }

    $user = User::where('email', $email)->first();

    if (!$user) {
        $this->error("No user found with email {$email}.");
        return 1;
    }

    $user->forceFill(['role' => $role])->save();
    $this->info("Updated {$email} role to {$role}.");

    return 0;
})->purpose('Set an admin panel user role without making role mass assignable.');
