<?php

require_once dirname(__DIR__) . '/bootstrap.php';

use App\Core\App;
use App\Core\Security;
use App\Core\Auth;
use App\Models\Member;

App::init();

$memberId = 1;
$member = Member::findById($memberId);
if (!$member) {
    fwrite(STDERR, "Member {$memberId} not found\n");
    exit(1);
}

$newEmail = 'updated.' . time() . '@example.com';
$token = Security::generateCsrf();

$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
$_SERVER['HTTP_X_CSRF_TOKEN'] = $token;
$_POST = [
    '_csrf_token' => $token,
    'full_name_english' => $member['full_name_english'],
    'full_name_tamil' => $member['full_name_tamil'] ?? '',
    'gender' => $member['gender'] ?? 'male',
    'status' => $member['status'],
    'nic_number' => $member['nic_number'] ?? '',
    'mobile' => $member['mobile'],
    'email' => $newEmail,
    'country_id' => $member['country_id'] ?? '',
    'occupation' => $member['occupation'] ?? '',
    'membership_type_id' => $member['membership_type_id'],
];

if (!Auth::attempt('admin', 'Slgti@2026@!')) {
    fwrite(STDERR, "Login failed\n");
    exit(1);
}

ob_start();
$controller = new \App\Controllers\MemberController();
$controller->update((string) $memberId);
$output = ob_get_clean();

$response = json_decode($output, true);
if (!is_array($response) || empty($response['success'])) {
    fwrite(STDERR, "Update failed: {$output}\n");
    exit(1);
}

$saved = Member::findById($memberId);
if (($saved['email'] ?? '') !== $newEmail) {
    fwrite(STDERR, 'Email mismatch after controller update: ' . ($saved['email'] ?? 'null') . "\n");
    exit(1);
}

echo "OK controller update email={$saved['email']}\n";
