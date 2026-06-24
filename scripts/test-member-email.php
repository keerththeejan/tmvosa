<?php

require_once dirname(__DIR__) . '/bootstrap.php';

use App\Core\App;
use App\Models\Member;
use App\Helpers\MemberEmail;

App::init();

$testEmail = 'test.member.' . time() . '@example.com';
$membershipNumber = 'TEST-' . time();

$memberId = Member::create([
    'membership_number' => $membershipNumber,
    'full_name_english' => 'Email Test Member',
    'mobile' => '0770000000',
    'email' => null,
    'membership_type_id' => 1,
    'status' => 'active',
    'membership_start_date' => date('Y-m-d'),
    'membership_expiry_date' => date('Y-m-d', strtotime('+1 year')),
]);

$parsed = MemberEmail::parse($testEmail);
if (isset($parsed['error'])) {
    fwrite(STDERR, "Validation failed: {$parsed['error']}\n");
    exit(1);
}

Member::update($memberId, ['email' => $parsed['value']]);
$saved = Member::findById($memberId);

if (!$saved || ($saved['email'] ?? '') !== $testEmail) {
    fwrite(STDERR, 'Email not persisted. Got: ' . json_encode($saved['email'] ?? null) . "\n");
    exit(1);
}

echo "OK member_id={$memberId} email={$saved['email']}\n";
