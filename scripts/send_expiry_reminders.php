<?php
/**
 * Cron-friendly script: php scripts/send_expiry_reminders.php [days]
 */
require __DIR__ . '/../bootstrap.php';

use App\Controllers\AdminController;

$days = isset($argv[1]) ? (int) $argv[1] : 30;
$sent = AdminController::dispatchExpiryReminders($days);
echo "Sent {$sent} membership expiry reminder(s).\n";
