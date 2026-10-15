<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::first();
echo "Testing user: " . $user->name . " (" . $user->role . ")\n";

\App\Services\TelegramNotificationService::sendLoginNotification($user);
echo "sendLoginNotification executed gracefully without exceptions!\n";
